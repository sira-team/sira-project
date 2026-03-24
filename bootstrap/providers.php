<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\Filament\GlobalAdminPanelProvider;
use App\Providers\Filament\TenantAdminPanelProvider;
use Modules\Academy\Providers\Filament\AcademyContentPanelProvider;
use Modules\Academy\Providers\Filament\AcademyPanelProvider;
use Modules\Camp\Providers\Filament\CampPanelProvider;
use Modules\Expo\Providers\Filament\ExpoPanelProvider;

return [
    // Laravel Standard App Service Provider
    AppServiceProvider::class,

    // Panel without a Tenant
    GlobalAdminPanelProvider::class,
    AcademyContentPanelProvider::class,

    // Panels with Tenant
    TenantAdminPanelProvider::class,
    CampPanelProvider::class,
    ExpoPanelProvider::class,
    AcademyPanelProvider::class,
];
