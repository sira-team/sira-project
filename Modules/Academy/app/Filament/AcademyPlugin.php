<?php

declare(strict_types=1);

namespace Modules\Academy\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class AcademyPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Academy';
    }

    public function getId(): string
    {
        return 'academy';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
