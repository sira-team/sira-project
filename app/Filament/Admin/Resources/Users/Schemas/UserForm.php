<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('Email Address'))
                    ->email()
                    ->required(),
                Select::make('roles')
                    ->label(__('Roles'))
                    ->relationship('roles', 'name')
                    ->saveRelationshipsUsing(function (User $record, mixed $state) {
                        $record->roles()->syncWithPivotValues($state, [
                            'tenant_id' => $record->tenant_id,
                        ]);
                    })
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }
}
