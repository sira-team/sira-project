<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Tenant;
use App\Models\User;

enum FeatureFlag: string
{
    // Scoped to Tenant
    case CampPanel = 'camp-panel';
    case ExpoPanel = 'expo-panel';
    case AcademyPanel = 'academy-panel';

    // Scoped to User
    case AcademyContentManagement = 'academy-content-management';
    case GlobalAdmin = 'global-admin';

    /**
     * Returns all features that are scoped to a Tenant model.
     */
    public static function tenantFeatures(): array
    {
        return [
            self::ExpoPanel,
            self::AcademyPanel,
            self::CampPanel,
        ];
    }

    /**
     * Returns all features that are scoped to a User model.
     */
    public static function userFeatures(): array
    {
        return [
            self::AcademyContentManagement,
            self::GlobalAdmin,
        ];
    }

    public function for(): string
    {
        return match ($this) {
            self::CampPanel, self::AcademyPanel, self::ExpoPanel => Tenant::class,
            self::AcademyContentManagement, self::GlobalAdmin => User::class,
        };
    }

    /**
     * Human-readable label for display in Filament UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::CampPanel => 'Camp organization',
            self::AcademyPanel => 'Sira Academy Module',
            self::ExpoPanel => 'Expo Module',
            self::AcademyContentManagement => 'Academy Content Management',
            self::GlobalAdmin => 'Global Admin',
        };
    }

    /**
     * Description shown in the Super Admin Panel.
     */
    public function description(): string
    {
        return match ($this) {
            self::CampPanel => 'Grants this tenant access to the Camp organization panel including camps, hostels and volunteers.',
            self::AcademyPanel => 'Grants this tenant access to the Sira Academy panel including enrollments, tickets and quizzes.',
            self::ExpoPanel => 'Grants this tenant access to the Expo panel including station inventory and expo request management.',
            self::AcademyContentManagement => 'Grants this specific user access to the global Academy Content Panel to manage levels, sessions and quizzes.',
            self::GlobalAdmin => 'Grants this specific user access to the global Admin Panel to manage tenants, users and roles.',
        };
    }
}
