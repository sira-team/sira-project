<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\Tenant;
use App\Models\TenantInviteLink;
use App\ValueObjects\TenantSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

/**
 * @property Tenant $tenant
 */
final class EditTenant extends EditTenantProfile
{
    protected static bool $shouldRegisterNavigation = true;

    public static function getLabel(): string
    {
        return 'Tenant';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Details'))
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label(__('Name')),
                    TextInput::make('email')->email()->label(__('Email')),
                ]),
            Section::make(__('Bank Details'))
                ->columns(2)
                ->schema([
                    TextInput::make('bank_recipient')->label(__('Bank Recipient Name')),
                    TextInput::make('bank_name')->label(__('Bank Name')),
                    TextInput::make('iban')
                        ->label(__('IBAN'))
                        ->mask('aa99 9999 9999 9999 9999 99')
                        ->stripCharacters(' ')
                        ->rule('regex:/^[A-Z]{2}[0-9]{20}$/')
                        ->placeholder(__('DE12 3456 7890 1234 5678 90')),
                    TextInput::make('bic')
                        ->label(__('BIC'))
                        ->regex('/^[A-Z]{6}[0-9A-Z]{2}([0-9A-Z]{3})?$/'),
                ]),
            Section::make(__('Invite Link'))
                ->columns(2)
                ->headerActions([
                    Action::make('enable_invite')
                        ->label(__('Enable Invite'))
                        ->visible(fn () => ! $this->tenant->inviteLink)
                        ->color(Color::Green)
                        ->icon(Heroicon::OutlinedUserPlus)
                        ->requiresConfirmation()
                        ->action(function (): void {
                            TenantInviteLink::create([
                                'tenant_id' => $this->tenant->id,
                                'token' => Str::uuid(),
                                'expires_at' => now()->addDays(30),
                            ]);

                            $this->tenant->refresh();
                        }),

                    Action::make('disable_invite')
                        ->label(__('Disable Invite'))
                        ->visible(fn () => $this->tenant->inviteLink)
                        ->color(Color::Red)
                        ->icon(Heroicon::OutlinedUserMinus)
                        ->requiresConfirmation()
                        ->action(function (): void {
                            $this->tenant->inviteLink?->delete();
                            $this->tenant->refresh();
                        }),

                    Action::make('regenerate_invite')
                        ->label(__('Regenerate Invite Link'))
                        ->visible(fn () => $this->tenant->inviteLink && ! $this->tenant->inviteLink->isValid())
                        ->icon(Heroicon::OutlinedArrowPath)
                        ->color(Color::Gray)
                        ->requiresConfirmation()
                        ->action(function (): void {
                            $this->tenant->inviteLink->update(['expires_at' => now()->addDays(30)]);
                            $this->tenant->refresh();
                        }),
                ])
                ->description(__('Share this link so members can join via Google login. Valid 30 days.'))
                ->schema([
                    TextEntry::make('invite_url')
                        ->label(__('Invite URL'))
                        ->state(fn () => $this->tenant->inviteLink?->isValid()
                            ? route('app.join', $this->tenant->inviteLink->token)
                            : __('No active invite link. Use "New Invite Link" above.')
                        )
                        ->copyable()
                        ->copyMessage(__('Copied!'))
                        ->dehydrated(false),

                    TextEntry::make('invite_expires_at')
                        ->label(__('Expires'))
                        ->state(fn () => $this->tenant->inviteLink?->expires_at?->toFormattedDateString() ?? '—')
                        ->dehydrated(false),
                ]),
            Section::make(__('Tenant Settings'))
                ->description(__('Configure tenant-specific settings'))
                ->schema([
                    Select::make('settings.default_role_id')
                        ->label(__('Default Role'))
                        ->options(function () {
                            // Adjust this to your actual Role model
                            return \App\Models\Role::pluck('name', 'id');
                        })
                        ->native(false)
                        ->searchable()
                        ->placeholder(__('Select a default role'))
                        ->helperText(__('Role assigned to new users when they join')),

                    Select::make('settings.locale')
                        ->label(__('Locale'))
                        ->options([
                            'en' => 'English',
                            'es' => 'Español',
                            'fr' => 'Français',
                            'de' => 'Deutsch',
                            'it' => 'Italiano',
                            'nl' => 'Nederlands',
                            'pt' => 'Português',
                        ])
                        ->native(false)
                        ->default('en')
                        ->helperText(__('Default language for this tenant')),

                    Select::make('settings.timezone')
                        ->label(__('Timezone'))
                        ->options(function () {
                            $timezones = [];
                            foreach (timezone_identifiers_list() as $tz) {
                                // Group by continent/city for better UX
                                if (str_contains($tz, '/')) {
                                    $parts = explode('/', $tz);
                                    $group = $parts[0];
                                    $timezones[$group][$tz] = str_replace('_', ' ', $parts[1] ?? $tz);
                                } else {
                                    $timezones['Other'][$tz] = $tz;
                                }
                            }

                            return $timezones;
                        })
                        ->searchable()
                        ->native(false)
                        ->default('UTC')
                        ->helperText(__('Time zone for dates and times')),

                    TextInput::make('settings.instagram')
                        ->label(__('Instagram Handle'))
                        ->prefix('@')
                        ->placeholder('username')
                        ->maxLength(30)
                        ->helperText(__('Leave empty if not used')),

                    // Add more settings here in the future - just use settings. prefix
                    // TextInput::make('settings.facebook')
                    //     ->label(__('Facebook Page'))
                    //     ->url(),
                    //
                    // TextInput::make('settings.theme')
                    //     ->label(__('Theme'))
                    //     ->select(['light' => 'Light', 'dark' => 'Dark']),
                ])
                ->columns(2),

        ])->columns(1);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get settings from tenant (ensures we have a TenantSettings object)
        $settings = $this->tenant->settings ?? new TenantSettings();

        // Convert to array for form fields (dot notation will work)
        $data['settings'] = $settings->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Convert settings array to value object if it exists
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
}
