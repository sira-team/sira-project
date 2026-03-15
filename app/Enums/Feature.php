<?php

declare(strict_types=1);

namespace App\Enums;

enum Feature: string
{
    // Scoped to Team
    case ExpoPanel = 'expo-panel';
    case AcademyPanel = 'academy-panel';

    // Scoped to User
    case AcademyContentManagement = 'academy-content-management';

    /**
     * Returns all features that are scoped to a Team model.
     */
    public static function teamFeatures(): array
    {
        return [
            self::ExpoPanel,
            self::AcademyPanel,
        ];
    }

    /**
     * Returns all features that are scoped to a User model.
     */
    public static function userFeatures(): array
    {
        return [
            self::AcademyContentManagement,
        ];
    }

    /**
     * Human-readable label for display in Filament UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::ExpoPanel => 'Expo Module',
            self::AcademyPanel => 'Sira Academy Module',
            self::AcademyContentManagement => 'Academy Content Management',
        };
    }

    /**
     * Description shown in the Super Admin Panel.
     */
    public function description(): string
    {
        return match ($this) {
            self::ExpoPanel => 'Grants this tenant access to the Expo panel including station inventory and expo request management.',
            self::AcademyPanel => 'Grants this tenant access to the Sira Academy panel including enrollments, tickets and quizzes.',
            self::AcademyContentManagement => 'Grants this specific user access to the global Academy Content Panel to manage levels, sessions and quizzes.',
        };
    }
}
