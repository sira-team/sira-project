<?php

declare(strict_types=1);

namespace Modules\Camp\Http\Controllers;

use App\Enums\Gender;
use App\Enums\GuardianRelationship;
use App\Enums\NotificationType;
use App\Models\Tenant;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Enums\FormFieldType;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampRegistrationAnswer;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Models\FormTemplateField;
use Modules\Camp\Services\CampRegistrationService;
use Throwable;

final class CampVisitorController
{
    use ValidatesRequests;

    public function show(Tenant $tenant, Camp $camp): View
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_is_open, 403, 'Registration is not open for this camp');

        $customFields = collect();

        if ($camp->form_template_id !== null) {
            $camp->load('formTemplate.fields');
            $customFields = $camp->formTemplate->fields;
        }

        return view('camp::register', compact('camp', 'tenant', 'customFields'));
    }

    /**
     * @throws Throwable
     */
    public function store(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService): RedirectResponse
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_is_open, 403);

        $customFields = collect();

        if ($camp->form_template_id !== null) {
            $camp->load('formTemplate.fields');
            $customFields = $camp->formTemplate->fields;
        }

        match ($camp->target_group) {
            CampTargetGroup::Adults => $this->storeAdults($tenant, $camp, $registrationService, $customFields),
            CampTargetGroup::Children => $this->storeChildren($tenant, $camp, $registrationService, $customFields),
            CampTargetGroup::Family => $this->storeFamily($tenant, $camp, $registrationService, $customFields),
        };

        return redirect()->route('camp.register.show', [$tenant->slug, $camp])->with('success', __('Registration submitted successfully!'));
    }

    /**
     * @param  Collection<int, FormTemplateField>  $customFields
     *
     * @throws Throwable
     */
    private function storeAdults(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService, Collection $customFields): void
    {
        $rules = [
            'visitor.name' => 'required|string|max:255',
            'visitor.email' => 'required|email:rfc,dns',
            'visitor.phone' => 'nullable|string|max:20',
            'visitor.gender' => 'required|in:male,female',
            'terms_accepted' => 'required|accepted',
        ];

        foreach ($customFields as $field) {
            if (! $field->type->isStructural()) {
                $rules["custom_fields.{$field->id}"] = $this->fieldValidationRule($field);
            }
        }

        $validated = $this->validate(request(), $rules);

        $visitor = Visitor::firstOrCreate(
            ['email' => $validated['visitor']['email']],
            [
                'name' => $validated['visitor']['name'],
                'phone' => $validated['visitor']['phone'] ?? null,
                'gender' => Gender::tryFrom($validated['visitor']['gender']),
            ],
        );

        $registration = $registrationService->registerVisitor($camp, $visitor);

        if ($customFields->count() > 0) {
            $this->saveAnswers($registration, $customFields, $validated['custom_fields'] ?? []);
        }

        $registration->notify($registration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);
    }

    /**
     * @param  Collection<int, FormTemplateField>  $customFields
     *
     * @throws Throwable
     */
    private function storeChildren(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService, Collection $customFields): void
    {
        $rules = [
            'visitor.name' => 'required|string|max:255',
            'visitor.email' => 'required|email:rfc,dns',
            'visitor.phone' => 'nullable|string|max:20',
            'participants' => 'required|array|min:1',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.gender' => 'required|in:male,female',
            'participants.*.phone' => 'nullable|string|max:20',
            'participants.*.email' => 'nullable|email:rfc,dns',
            'terms_accepted' => 'required|accepted',
        ];

        foreach ($customFields as $field) {
            if (! $field->type->isStructural()) {
                $rules["participants.*.custom_fields.{$field->id}"] = $this->fieldValidationRule($field);
            }
        }

        $validated = $this->validate(request(), $rules);

        $guardian = Visitor::firstOrCreate(
            ['email' => $validated['visitor']['email']],
            [
                'name' => $validated['visitor']['name'],
                'phone' => $validated['visitor']['phone'] ?? null,
            ],
        );

        foreach ($validated['participants'] as $participantData) {
            $child = Visitor::create([
                'name' => $participantData['name'],
                'gender' => Gender::tryFrom($participantData['gender']),
                'email' => $participantData['email'] ?? null,
                'phone' => $participantData['phone'] ?? null,
            ]);

            $relationship = $guardian->gender === Gender::Male ? GuardianRelationship::Father : GuardianRelationship::Mother;
            $child->guardians()->attach($guardian, ['relationship' => $relationship]);

            $registration = $registrationService->registerVisitor($camp, $child);

            if ($customFields->count() > 0) {
                $this->saveAnswers($registration, $customFields, $participantData['custom_fields'] ?? []);
            }

            $registration->notify($registration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);
        }
    }

    /**
     * @param  Collection<int, FormTemplateField>  $customFields
     *
     * @throws Throwable
     */
    private function storeFamily(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService, Collection $customFields): void
    {
        $rules = [
            'visitor.name' => 'required|string|max:255',
            'visitor.email' => 'required|email:rfc,dns',
            'visitor.phone' => 'nullable|string|max:20',
            'participants' => 'nullable|array',
            'participants.*.name' => 'required_with:participants|string|max:255',
            'terms_accepted' => 'required|accepted',
        ];

        foreach ($customFields as $field) {
            if (! $field->type->isStructural()) {
                $rules["custom_fields.{$field->id}"] = $this->fieldValidationRule($field);
                $rules["participants.*.custom_fields.{$field->id}"] = $this->fieldValidationRule($field);
            }
        }

        $validated = $this->validate(request(), $rules);

        $guardian = Visitor::firstOrCreate(
            ['email' => $validated['visitor']['email']],
            [
                'name' => $validated['visitor']['name'],
                'phone' => $validated['visitor']['phone'] ?? null,
            ],
        );

        $registration = $registrationService->registerVisitor($camp, $guardian);

        if ($customFields->count() > 0) {
            $this->saveAnswers($registration, $customFields, $validated['custom_fields'] ?? []);
        }

        $registration->notify($registration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);

        if (! empty($validated['participants'])) {
            foreach ($validated['participants'] as $participantData) {
                $member = Visitor::create([
                    'name' => $participantData['name'],
                ]);

                $member->guardians()->attach($guardian, ['relationship' => GuardianRelationship::Father]);

                $memberRegistration = $registrationService->registerVisitor($camp, $member);

                if ($customFields->count() > 0) {
                    $this->saveAnswers($memberRegistration, $customFields, $participantData['custom_fields'] ?? []);
                }

                $memberRegistration->notify($memberRegistration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);
            }
        }
    }

    private function fieldValidationRule(FormTemplateField $field): string
    {
        $base = $field->required ? 'required' : 'nullable';

        return $base.match ($field->type) {
            FormFieldType::Number => '|numeric',
            FormFieldType::Email => '|email',
            FormFieldType::Date => '|date',
            FormFieldType::Select, FormFieldType::Radio => '|string|max:255',
            FormFieldType::Checkbox => '|array',
            FormFieldType::Boolean => '|boolean',
            default => '|string|max:5000',
        };
    }

    /**
     * @param  Collection<int, FormTemplateField>  $fields
     * @param  array<int|string, mixed>  $rawAnswers
     */
    private function saveAnswers(CampVisitor $registration, Collection $fields, array $rawAnswers): void
    {
        foreach ($fields as $field) {
            if ($field->type->isStructural()) {
                continue;
            }

            $raw = $rawAnswers[$field->id] ?? null;

            $value = is_array($raw) ? json_encode($raw) : ($raw !== null ? (string) $raw : null);

            CampRegistrationAnswer::create([
                'camp_visitor_id' => $registration->id,
                'form_template_field_id' => $field->id,
                'field_label' => $field->label,
                'field_type' => $field->type->value,
                'value' => $value,
            ]);
        }
    }
}
