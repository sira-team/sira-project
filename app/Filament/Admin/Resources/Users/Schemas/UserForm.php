<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

final class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('E-Mail-Adresse')
                    ->email()
                    ->required(),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->saveRelationshipsUsing(function (Model $record, mixed $state) {
                        $record->roles()->syncWithPivotValues($state, [
                            config('permission.column_names.tenant_foreign_key') => getPermissionsTeamId(),
                        ]);
                    })
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }
}
