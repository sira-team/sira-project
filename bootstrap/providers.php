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
    AppServiceProvider::class,
    GlobalAdminPanelProvider::class,
    TenantAdminPanelProvider::class,
    CampPanelProvider::class,
    ExpoPanelProvider::class,
    AcademyContentPanelProvider::class,
    AcademyPanelProvider::class,
];
