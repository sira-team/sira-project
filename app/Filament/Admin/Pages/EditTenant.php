<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\Tenant;
use App\Models\TenantInviteLink;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

/**
 * @extends EditTenantProfile<Tenant>
 *
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
            TextInput::make('name')->label(__('Name')),
            TextInput::make('email')->email()->label(__('Email')),
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
            Section::make(__('Invite Link'))
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
                ])
                ->columns(1),
        ])->columns(2);
    }

    protected function getHeaderActions(): array
    {
        return [
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
        ];
    }
}
