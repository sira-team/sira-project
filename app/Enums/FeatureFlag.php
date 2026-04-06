<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Tenant;
use App\Models\User;
use App\Providers\Filament\GlobalAdminPanelProvider;
use App\Providers\Filament\TenantAppPanelProvider;
use InvalidArgumentException;
use Modules\Academy\Providers\Filament\AcademyContentPanelProvider;
use Modules\Academy\Providers\Filament\AcademyPanelProvider;
use Modules\Camp\Providers\Filament\CampPanelProvider;
use Modules\Expo\Providers\Filament\ExpoPanelProvider;

enum FeatureFlag: string
{
    // Scoped to Tenant
    case TenantApp = TenantAppPanelProvider::ID;
    case CampPanel = CampPanelProvider::ID;
    case ExpoPanel = ExpoPanelProvider::ID;
    case AcademyPanel = AcademyPanelProvider::ID;

    // Scoped to User
    case AcademyManager = AcademyContentPanelProvider::ID;
    case GlobalAdmin = GlobalAdminPanelProvider::ID;

    /**
     * Returns all features that are scoped to a Tenant model.
     */
    public static function tenantFeatures(): array
    {
        return [
            self::TenantApp,
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
            self::AcademyManager,
            self::GlobalAdmin,
        ];
    }

    public static function fromPanelId(string $panel): FeatureFlag
    {
        return match ($panel) {
            CampPanelProvider::ID => self::CampPanel,
            AcademyPanelProvider::ID => self::AcademyPanel,
            ExpoPanelProvider::ID => self::ExpoPanel,
            GlobalAdminPanelProvider::ID => self::GlobalAdmin,
            AcademyContentPanelProvider::ID => self::AcademyManager,
            TenantAppPanelProvider::ID => self::TenantApp,
            default => throw new InvalidArgumentException("No feature flag associated with panel ID: {$panel}"),
        };
    }

    public function for(): string
    {
        return match ($this) {
            self::TenantApp, self::CampPanel, self::AcademyPanel, self::ExpoPanel => Tenant::class,
            self::AcademyManager, self::GlobalAdmin => User::class,
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
            self::AcademyManager => 'Academy Content Management',
            self::GlobalAdmin => 'Global Admin',
            self::TenantApp => 'Tenant App',
        };
    }

    /**
     * Description shown in the Super Admin Panel.
     */
    public function description(): string
    {
        return match ($this) {
            self::TenantApp => 'Grants this tenant access to the Tenant App panel for managing members and roles.',
            self::CampPanel => 'Grants this tenant access to the Camp organization panel including camps, hostels and volunteers.',
            self::AcademyPanel => 'Grants this tenant access to the Sira Academy panel including enrollments, tickets and quizzes.',
            self::ExpoPanel => 'Grants this tenant access to the Expo panel including station inventory and expo request management.',
            self::AcademyManager => 'Grants this specific user access to the global Academy Content Panel to manage levels, sessions and quizzes.',
            self::GlobalAdmin => 'Grants this specific user access to the global Admin Panel to manage tenants, users and roles.',
        };
    }
}
