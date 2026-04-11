<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\Tenants\Pages;

use App\Enums\FeatureFlag;
use App\Filament\GlobalAdmin\Resources\Tenants\TenantResource;
use App\Models\TenantInviteLink;
use App\ValueObjects\TenantSettings;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Laravel\Pennant\Feature as PennantFeature;

final class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('enable_invite')
                ->label(__('Enable Invite'))
                ->visible(fn () => ! $this->record->inviteLink)
                ->color(Color::Green)
                ->icon(Heroicon::OutlinedUserPlus)
                ->requiresConfirmation()
                ->action(function (): void {
                    TenantInviteLink::create([
                        'tenant_id' => $this->record->id,
                        'token' => Str::uuid(),
                        'expires_at' => now()->addDays(30)->endOfDay(),
                    ]);

                    $this->record->refresh();
                }),

            Actions\Action::make('disable_invite')
                ->label(__('Disable Invite'))
                ->visible(fn () => $this->record->inviteLink)
                ->color(Color::Red)
                ->icon(Heroicon::OutlinedUserMinus)
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->inviteLink?->delete();
                    $this->record->refresh();
                }),

            Actions\Action::make('regenerate_invite')
                ->label(__('Regenerate Invite Link'))
                ->visible(fn () => $this->record->inviteLink && ! $this->record->inviteLink->isValid())
                ->icon(Heroicon::OutlinedArrowPath)
                ->color(Color::Gray)
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->inviteLink->update(['expires_at' => now()->addDays(30)]);
                    $this->record->refresh();
                }),

            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $settings = $this->record->settings ?? new TenantSettings();
        $data['settings'] = $settings->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['settings']) && is_array($data['settings'])) {
            $data['settings'] = new TenantSettings(
                default_role_id: $data['settings']['default_role_id'] ?? null,
                locale: $data['settings']['locale'] ?? 'en',
                timezone: $data['settings']['timezone'] ?? 'UTC',
                instagram: $data['settings']['instagram'] ?? null,
            );
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Sync feature toggles with Pennant
        foreach (FeatureFlag::tenantFeatures() as $feature) {
            $isActive = $this->data[$feature->value] ?? false;

            if ($isActive) {
                PennantFeature::for($this->record)->activate($feature->value);
            } else {
                PennantFeature::for($this->record)->deactivate($feature->value);
            }
        }
    }
}
