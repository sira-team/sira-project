<?php

declare(strict_types=1);

namespace App\Facade;

use App\Models\Tenant;
use Filament\Facades\Filament;

final class SiraApp
{
    public static function getTenant(): ?Tenant
    {
        $tenant = Filament::getTenant();

        return $tenant instanceof Tenant ? $tenant : null;
    }
}
