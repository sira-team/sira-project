<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationType: string
{
    case CampReceived = 'camp-received';
    case CampWaitlisted = 'camp-waitlisted';
    case CampWaitlistPromoted = 'camp-waitlist_promoted';
    case CampConfirmed = 'camp-confirmed';
    case CampCancelled = 'camp-cancelled';
    case ExpoRequestReceived = 'expo-request-received';
    case UserInvited = 'user-invited';

    public function label(): string
    {
        return match ($this) {
            self::CampReceived => 'Registration Received',
            self::CampWaitlisted => 'Waitlisted',
            self::CampWaitlistPromoted => 'Promoted from Waitlist',
            self::CampConfirmed => 'Registration Confirmed',
            self::CampCancelled => 'Registration Cancelled',
            self::ExpoRequestReceived => 'Expo Request Received',
            self::UserInvited => 'User Invited',
        };
    }

    public function isCampNotification(): bool
    {
        return str_starts_with($this->value, 'camp-');
    }

    /**
     * @return list<string>
     */
    public function mergeTags(): array
    {
        $common = ['visitor_name', 'camp_name', 'tenant_name', 'room_name'];
        $bankDetails = ['iban', 'bank_recipient', 'bank_name', 'bic'];

        return match ($this) {
            self::CampReceived, self::CampWaitlistPromoted => [...$common, 'price', ...$bankDetails],
            self::CampConfirmed => [...$common, 'payment_due_date'],
            self::CampWaitlisted => [...$common, 'waitlist_position'],
            self::CampCancelled => $common,
            self::ExpoRequestReceived => ['contact_name', 'organisation_name', 'tenant_name'],
            self::UserInvited => ['user_name', 'tenant_name', 'setup_url'],
        };
    }
}
