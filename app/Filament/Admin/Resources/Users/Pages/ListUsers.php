<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Mail\UserInvitation;
use App\Models\Tenant;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

final class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('invite')
                ->label(__('Invite User'))
                ->icon('heroicon-o-envelope')
                ->schema([
                    TextInput::make('email')
                        ->label(__('Email Address'))
                        ->email()
                        ->required(),
                    TextInput::make('name')
                        ->label(__('Name'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    /** @var Tenant $tenant */
                    $tenant = Filament::getTenant();

                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => Str::password(32),
                        'tenant_id' => $tenant->id,
                        'email_verified_at' => null,
                    ]);

                    setPermissionsTeamId($tenant->id);
                    $user->assignRole('member');

                    $setupUrl = URL::temporarySignedRoute(
                        'account.setup',
                        now()->addDays(7),
                        ['user' => $user->id]
                    );

                    Mail::to($user->email)->send(
                        new UserInvitation($user, $tenant, $setupUrl)
                    );

                    Notification::make()
                        ->title("Einladung gesendet an {$user->email}")
                        ->success()
                        ->send();
                }),
        ];
    }
}
