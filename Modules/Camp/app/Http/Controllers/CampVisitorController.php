<?php

declare(strict_types=1);

namespace Modules\Camp\Http\Controllers;

use App\Enums\GuardianRelationship;
use App\Enums\NotificationType;
use App\Models\Tenant;
use App\Models\Visitor;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Services\CampRegistrationService;
use Throwable;

final class CampVisitorController
{
    use ValidatesRequests;

    public function show(Tenant $tenant, Camp $camp): View
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_is_open, 403, 'Registration is not open for this camp');

        return match ($camp->target_group) {
            CampTargetGroup::Adults => view('camp::registration/register-self', compact('camp', 'tenant')),
            CampTargetGroup::Family => view('camp::registration/register-family', compact('camp', 'tenant')),
            CampTargetGroup::Children, CampTargetGroup::Teenagers => view('camp::registration/register-children', compact('camp', 'tenant')),
        };
    }

    /**
     * @throws Throwable
     */
    public function storeAdult(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService): RedirectResponse
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_is_open, 403);
        abort_unless($camp->target_group === CampTargetGroup::Adults, 404);

        $validated = $this->validate(request(), [
            'visitor.name' => 'required|string|max:255',
            'visitor.email' => 'required|email:rfc,dns',
            'visitor.phone' => 'nullable|string|max:20',
            'visitor.date_of_birth' => 'nullable|date',
            'visitor.gender' => 'nullable|in:male,female',
            'visitor.allergies' => 'nullable|string',
            'visitor.medications' => 'nullable|string',
            'visitor.wishes' => 'nullable|string|max:255',
            'terms_accepted' => 'required|accepted',
        ]);

        $adult = Visitor::updateOrCreate(
            ['email' => $validated['visitor']['email']],
            [
                'name' => $validated['visitor']['name'],
                'phone' => $validated['visitor']['phone'] ?? null,
                'date_of_birth' => $validated['visitor']['date_of_birth'] ?? null,
                'gender' => $validated['visitor']['gender'] ?? null,
                'allergies' => $validated['visitor']['allergies'] ?? null,
                'medications' => $validated['visitor']['medications'] ?? null,
            ]
        );

        $registration = $registrationService->registerVisitor($camp, $adult, $validated['visitor']['wishes']);
        $registration->notify($registration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);

        return redirect()->route('camp.register.show', [$tenant->slug, $camp])->with('success', __('Registration submitted successfully!'));
    }

    /**
     * @throws Throwable
     */
    public function storeChildren(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService): RedirectResponse
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_is_open, 403);
        abort_unless(in_array($camp->target_group, [CampTargetGroup::Children, CampTargetGroup::Teenagers], true), 404);

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
            'terms_accepted' => 'required|accepted',
        ]);

        $guardian = Visitor::updateOrCreate(
            ['email' => $validated['visitor']['email']],
            [
                'name' => $validated['visitor']['name'],
                'phone' => $validated['visitor']['phone'] ?? null,
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

            $child->guardians()->attach($guardian, ['relationship' => GuardianRelationship::Parent->value]);

            $registration = $registrationService->registerVisitor($camp, $child, $participantData['wishes']);
            $registration->notify($registration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);
        }

        return redirect()->route('camp.register.show', [$tenant->slug, $camp])->with('success', __('Registration submitted successfully!'));
    }

    /**
     * @throws Throwable
     */
    public function storeFamily(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService): RedirectResponse
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_is_open, 403);
        abort_unless($camp->target_group === CampTargetGroup::Family, 404);

        $validated = $this->validate(request(), [
            'visitor.name' => 'required|string|max:255',
            'visitor.email' => 'required|email:rfc,dns',
            'visitor.phone' => 'nullable|string|max:20',
            'visitor.date_of_birth' => 'nullable|date',
            'visitor.gender' => 'nullable|in:male,female',
            'visitor.allergies' => 'nullable|string',
            'visitor.medications' => 'nullable|string',
            'visitor.wishes' => 'nullable|string|max:255',
            'participants' => 'nullable|array',
            'participants.*.name' => 'required_with:participants|string|max:255',
            'participants.*.date_of_birth' => 'required_with:participants|date',
            'participants.*.gender' => 'required_with:participants|in:'.implode(',', array_column($camp->gender_policy->getGenders(), 'value')),
            'participants.*.allergies' => 'nullable|string',
            'participants.*.medications' => 'nullable|string',
            'participants.*.wishes' => 'nullable|string|max:255',
            'terms_accepted' => 'required|accepted',
        ]);

        $guardian = Visitor::updateOrCreate(
            ['email' => $validated['visitor']['email']],
            [
                'name' => $validated['visitor']['name'],
                'phone' => $validated['visitor']['phone'] ?? null,
                'date_of_birth' => $validated['visitor']['date_of_birth'] ?? null,
                'gender' => $validated['visitor']['gender'] ?? null,
                'allergies' => $validated['visitor']['allergies'] ?? null,
                'medications' => $validated['visitor']['medications'] ?? null,
            ]
        );

        // Register the guardian themselves
        $guardianRegistration = $registrationService->registerVisitor($camp, $guardian, $validated['visitor']['wishes']);
        $guardianRegistration->notify($guardianRegistration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);

        // Register each family member
        foreach ($validated['participants'] ?? [] as $participantData) {
            $familyMember = Visitor::create([
                'name' => $participantData['name'],
                'date_of_birth' => $participantData['date_of_birth'],
                'gender' => $participantData['gender'],
                'allergies' => $participantData['allergies'] ?? null,
                'medications' => $participantData['medications'] ?? null,
            ]);

            $familyMember->guardians()->attach($guardian, ['relationship' => GuardianRelationship::Member->value]);

            $registration = $registrationService->registerVisitor($camp, $familyMember, $participantData['wishes']);
            $registration->notify($registration->status === VisitorStatus::Pending ? NotificationType::CampReceived : NotificationType::CampWaitlisted);
        }

        return redirect()->route('camp.register.show', [$tenant->slug, $camp])->with('success', __('Registration submitted successfully!'));
    }
}
