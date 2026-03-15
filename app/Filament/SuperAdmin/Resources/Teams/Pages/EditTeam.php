<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\Teams\Pages;

use App\Enums\Feature;
use App\Filament\SuperAdmin\Resources\Teams\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Laravel\Pennant\Feature as PennantFeature;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Sync feature toggles with Pennant
        foreach (Feature::teamFeatures() as $feature) {
            $isActive = $this->data[$feature->value] ?? false;

            if ($isActive) {
                PennantFeature::for($this->record)->activate($feature->value);
            } else {
                PennantFeature::for($this->record)->deactivate($feature->value);
            }
        }
    }
}
