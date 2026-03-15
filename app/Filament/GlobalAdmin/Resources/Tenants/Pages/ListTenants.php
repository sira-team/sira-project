<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\Tenants\Pages;

use App\Filament\GlobalAdmin\Resources\Tenants\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
