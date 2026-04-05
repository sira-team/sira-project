<?php

declare(strict_types=1);

namespace Modules\Camp\Http\Controllers;

use App\Enums\Gender;
use App\Enums\GuardianRelationship;
use App\Enums\NotificationType;
use App\Models\Tenant;
use App\Models\Visitor;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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

        return view('camp::register', compact('camp', 'tenant'));
    }

    /**
     * @throws Throwable
     */
    public function store(Tenant $tenant, Camp $camp, CampRegistrationService $registrationService): RedirectResponse
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_is_open, 403);

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
}
