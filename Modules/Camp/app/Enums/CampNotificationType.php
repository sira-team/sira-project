<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampNotificationType: string
{
    case RegistrationReceived = 'registration_received';
    case Confirmed = 'confirmed';
    case Waitlisted = 'waitlisted';
    case WaitlistPromoted = 'waitlist_promoted';
    case PaymentReminder = 'payment_reminder';
    case RoomAssigned = 'room_assigned';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::RegistrationReceived => 'Registration Received',
            self::Confirmed => 'Confirmed',
            self::Waitlisted => 'Waitlisted',
            self::WaitlistPromoted => 'Waitlist Promoted',
            self::PaymentReminder => 'Payment Reminder',
            self::RoomAssigned => 'Room Assigned',
            self::Cancelled => 'Cancelled',
        };
    }
}
