<?php

declare(strict_types=1);

namespace Modules\Camp\Http\Controllers;

use App\Models\Participant;
use App\Models\Tenant;
use App\Models\Visitor;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Modules\Camp\Enums\CampNotificationType;
use Modules\Camp\Enums\CampPaymentStatus;
use Modules\Camp\Enums\CampRegistrationStatus;
use Modules\Camp\Mails\CampRegistrationReceivedMail;
use Modules\Camp\Mails\CampWaitlistedMail;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampNotificationLog;
use Modules\Camp\Models\CampRegistration;
use Modules\Camp\Services\WaitlistService;

final class CampRegistrationController
{
    use ValidatesRequests;

    public function show(Tenant $tenant, Camp $camp): View
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_open, 403, 'Registration is not open for this camp');

        return view('camp::register', compact('camp', 'tenant'));
    }

    public function store(Tenant $tenant, Camp $camp, WaitlistService $waitlistService): RedirectResponse
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);
        abort_unless($camp->registration_open, 403);

        $validated = $this->validate(request(), [
            'target_group' => 'required|in:myself,child',
            'participants' => 'required|array|min:1',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.date_of_birth' => 'required|date',
            'participants.*.gender' => 'required|in:male,female',
            'participants.*.allergies' => 'nullable|string',
            'participants.*.medications' => 'nullable|string',
            'participants.*.medical_notes' => 'nullable|string',
            'participants.*.emergency_contact_name' => 'required|string|max:255',
            'participants.*.emergency_contact_phone' => 'required|string|max:20',
            'visitor.name' => 'required|string|max:255',
            'visitor.email' => 'required|email:rfc,dns',
            'visitor.phone' => 'nullable|string|max:20',
            'terms_accepted' => 'required|accepted',
        ]);

        return DB::transaction(function () use ($tenant, $camp, $validated, $waitlistService) {
            $visitor = Visitor::firstOrCreate(
                ['email' => $validated['visitor']['email']],
                [
                    'name' => $validated['visitor']['name'],
                    'phone' => $validated['visitor']['phone'] ?? null,
                ]
            );

            $isTarget = $validated['target_group'] === 'myself';
            $confirmedCount = $camp->registrations()
                ->lockForUpdate()
                ->where('status', CampRegistrationStatus::Confirmed)
                ->orWhere('status', CampRegistrationStatus::Pending)
                ->count();

            foreach ($validated['participants'] as $participantData) {
                $participant = Participant::create([
                    'visitor_id' => $visitor->id,
                    'name' => $participantData['name'],
                    'date_of_birth' => $participantData['date_of_birth'],
                    'gender' => $participantData['gender'],
                    'is_self' => $isTarget,
                    'allergies' => $participantData['allergies'] ?? null,
                    'medications' => $participantData['medications'] ?? null,
                    'medical_notes' => $participantData['medical_notes'] ?? null,
                    'emergency_contact_name' => $participantData['emergency_contact_name'],
                    'emergency_contact_phone' => $participantData['emergency_contact_phone'],
                ]);

                if ($confirmedCount < $camp->capacity) {
                    $status = CampRegistrationStatus::Pending;
                    $waitlistPosition = null;
                } else {
                    $status = CampRegistrationStatus::Waitlisted;
                    $waitlistPosition = $waitlistService->assignPosition($camp);
                }

                $registration = CampRegistration::create([
                    'camp_id' => $camp->id,
                    'visitor_id' => $visitor->id,
                    'participant_id' => $participant->id,
                    'status' => $status,
                    'payment_status' => CampPaymentStatus::Pending,
                    'waitlist_position' => $waitlistPosition,
                    'registered_at' => now(),
                ]);

                CampNotificationLog::create([
                    'camp_registration_id' => $registration->id,
                    'notification_type' => $status === CampRegistrationStatus::Pending
                        ? CampNotificationType::RegistrationReceived
                        : CampNotificationType::Waitlisted,
                    'sent_at' => now(),
                ]);

                if ($status === CampRegistrationStatus::Pending) {
                    Mail::queue(new CampRegistrationReceivedMail($registration));
                } else {
                    Mail::queue(new CampWaitlistedMail($registration));
                }

                $confirmedCount++;
            }

            return redirect()->route('camp.register.show', [$tenant->slug, $camp])->with('success', 'Registration submitted successfully!');
        });
    }
}
