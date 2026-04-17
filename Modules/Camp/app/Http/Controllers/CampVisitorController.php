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

        if ($camp->form_template_id !== null) {
            $camp->load('formTemplate.fields');
            [$preFields, $postFields, $hasRepeater] = $this->splitFields($camp->formTemplate->fields);

            return view('camp::register-template', compact('camp', 'tenant', 'preFields', 'postFields', 'hasRepeater'));
        }

        return view('camp::register', compact('camp', 'tenant'));
    }

    /**
     * @throws Throwable
     */
    public function store(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService): RedirectResponse
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_is_open, 403);

        if ($camp->form_template_id !== null) {
            $camp->load('formTemplate.fields');

            return $this->storeWithTemplate($tenant, $camp, $registrationService);
        }

        return $this->storeHardcoded($tenant, $camp, $registrationService);
    }

    /**
     * @throws Throwable
     */
    private function storeHardcoded(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService): RedirectResponse
    {
        $validated = $this->validate(request(), [
            'participants' => 'required|array|min:1',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.date_of_birth' => 'required|date',
            'participants.*.gender' => 'required|in:'.implode(',', array_column($camp->gender_policy->getGenders(), 'value')),
            'participants.*.allergies' => 'nullable|string',
            'participants.*.medications' => 'nullable|string',
            'participants.*.wishes' => 'nullable|string|max:255',
            'visitor.name' => 'required|string|max:255',
            'visitor.email' => 'required|email:rfc,dns',
            'visitor.phone' => 'nullable|string|max:20',
            'visitor.gender' => 'nullable|in:male,female',
            'visitor.date_of_birth' => 'nullable|date',
            'terms_accepted' => 'required|accepted',
        ]);

        $guardian = Visitor::firstOrCreate(
            ['email' => $validated['visitor']['email']],
            [
                'name' => $validated['visitor']['name'],
                'phone' => $validated['visitor']['phone'] ?? null,
                'gender' => $validated['visitor']['gender'] ?? null,
                'date_of_birth' => $validated['visitor']['date_of_birth'] ?? null,
            ]
        );

        foreach ($validated['participants'] as $participantData) {
            $child = Visitor::create([
                'name' => $participantData['name'],
                'date_of_birth' => $participantData['date_of_birth'],
                'gender' => $participantData['gender'],
                'allergies' => $participantData['allergies'] ?? null,
                'medications' => $participantData['medications'] ?? null,
            ]);

            $child->guardians()->attach($guardian, ['relationship' => $guardian->gender === Gender::Male ? GuardianRelationship::Father : GuardianRelationship::Mother]);

            $registration = $registrationService->registerVisitor($camp, $child, $participantData['wishes']);
            $registration->notify($registration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);
        }

        return redirect()->route('camp.register.show', [$tenant->slug, $camp])->with('success', 'Registration submitted successfully!');
    }

    /**
     * @throws Throwable
     */
    private function storeWithTemplate(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService): RedirectResponse
    {
        [$preFields, $postFields, $hasRepeater] = $this->splitFields($camp->formTemplate->fields);

        $rules = [];

        foreach ($preFields as $field) {
            if ($field->type->isStructural()) {
                continue;
            }

            $rules["custom_fields.{$field->id}"] = $this->fieldValidationRule($field);
        }

        if ($hasRepeater) {
            $rules['participants'] = 'required|array|min:1';

            foreach ($postFields as $field) {
                if ($field->type->isStructural()) {
                    continue;
                }

                $rules["participants.*.custom_fields.{$field->id}"] = $this->fieldValidationRule($field);
            }
        }

        $validated = $this->validate(request(), $rules);

        $preAnswers = $validated['custom_fields'] ?? [];

        if ($hasRepeater) {
            foreach ($validated['participants'] as $participantData) {
                $participant = Visitor::create(['name' => 'Participant']);

                $registration = $registrationService->registerVisitor($camp, $participant, null);
                $registration->notify($registration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);

                $this->saveAnswers($registration, $preFields, $preAnswers);
                $this->saveAnswers($registration, $postFields, $participantData['custom_fields'] ?? []);
            }
        } else {
            $participant = Visitor::create(['name' => 'Participant']);

            $registration = $registrationService->registerVisitor($camp, $participant, null);
            $registration->notify($registration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);

            $this->saveAnswers($registration, $preFields, $preAnswers);
        }

        return redirect()->route('camp.register.show', [$tenant->slug, $camp])->with('success', 'Registration submitted successfully!');
    }

    /**
     * @param  Collection<int, FormTemplateField>  $fields
     * @return array{0: Collection<int, FormTemplateField>, 1: Collection<int, FormTemplateField>, 2: bool}
     */
    private function splitFields(Collection $fields): array
    {
        $repeaterIndex = $fields->search(fn (FormTemplateField $f) => $f->type === FormFieldType::Repeater);

        if ($repeaterIndex === false) {
            return [$fields, collect(), false];
        }

        return [
            $fields->slice(0, $repeaterIndex),
            $fields->slice($repeaterIndex + 1),
            true,
        ];
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

            // Array values (checkbox) are JSON-encoded; everything else stored as plain text
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
