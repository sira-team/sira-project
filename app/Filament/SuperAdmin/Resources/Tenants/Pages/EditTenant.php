<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Enums\Feature;
use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Laravel\Pennant\Feature as PennantFeature;

final class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Sync feature toggles with Pennant
        foreach (Feature::tenantFeatures() as $feature) {
            $isActive = $this->data[$feature->value] ?? false;

            if ($isActive) {
                PennantFeature::for($this->record)->activate($feature->value);
            } else {
                PennantFeature::for($this->record)->deactivate($feature->value);
            }
        }
    }
}
