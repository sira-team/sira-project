<?php

declare(strict_types=1);

namespace Modules\Expo\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class ExpoPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Expo';
    }

    public function getId(): string
    {
        return 'expo';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
