<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\Tenants\Schemas;

use App\Enums\Country;
use App\Enums\FeatureFlag;
use App\Models\Role;
use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Laravel\Pennant\Feature as PennantFeature;

final class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label(__('Slug'))
                    ->required()
                    ->unique(Tenant::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('city')
                    ->label(__('City'))
                    ->maxLength(255),
                Select::make('country')
                    ->label(__('Country'))
                    ->options(Country::class)
                    ->default(Country::Germany->value),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->maxLength(255),
                TextInput::make('owner_email')
                    ->label(__('Owner Email'))
                    ->email()
                    ->maxLength(255)
                    ->visible(fn (string $operation) => $operation === 'create')
                    ->helperText(__('Email for the first tenant admin. They will receive an invitation.')),
                Section::make(__('Module Access'))
                    ->description(__('Enable or disable modules for this tenant.'))
                    ->visible(fn (string $operation) => $operation === 'edit')
                    ->components(
                        collect(FeatureFlag::tenantFeatures())
                            ->map(fn (FeatureFlag $feature) => Toggle::make($feature->value)
                                ->label($feature->label())
                                ->helperText($feature->description())
                                ->afterStateHydrated(function (Toggle $component, ?Tenant $record) use ($feature) {
                                    $component->state(
                                        PennantFeature::for($record)->active($feature->value)
                                    );
                                })
                                ->dehydrated(false)
                            )
                            ->values()
                            ->toArray()
                    ),
                Section::make(trans('tenant.settings.label'))
                    ->description(trans('tenant.settings.description'))
                    ->visible(fn (string $operation) => $operation === 'edit')
                    ->columns(2)
                    ->components([
                        TextEntry::make('settings.default_role_id.description')
                            ->label(trans('tenant.settings.default_role_id.label'))
                            ->state(trans('tenant.settings.default_role_id.description')),
                        Select::make('settings.default_role_id')
                            ->label(__('Default Role'))
                            ->hiddenLabel()
                            ->searchable()
                            ->placeholder(trans('tenant.settings.default_role_id.placeholder'))
                            ->options(fn (?Tenant $record) => $record
                                ? Role::query()->whereTenantId($record->id)->pluck('name', 'id')
                                : []
                            ),
                    ]),
                Section::make(trans('tenant.invite_link.label'))
                    ->description(trans('tenant.invite_link.description'))
                    ->visible(fn (string $operation) => $operation === 'edit')
                    ->columns(2)
                    ->components([
                        TextEntry::make('invite_url')
                            ->label(__('Invite URL'))
                            ->state(fn (?Tenant $record) => $record?->inviteLink?->isValid()
                                ? route('app.join', $record->inviteLink->token)
                                : __('No active invite link.')
                            )
                            ->copyable()
                            ->copyMessage(__('Copied!'))
                            ->dehydrated(false),
                        TextEntry::make('invite_expires_at')
                            ->label(__('Expires'))
                            ->date('d.m.Y H:i:s')
                            ->state(fn (?Tenant $record) => $record?->inviteLink?->expires_at)
                            ->visible(fn (?Tenant $record) => (bool) $record?->inviteLink?->expires_at)
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
