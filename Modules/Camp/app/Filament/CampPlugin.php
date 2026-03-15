<?php

declare(strict_types=1);

namespace Modules\Camp\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

final class CampPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Camp';
    }

    public function getId(): string
    {
        return 'camp';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
