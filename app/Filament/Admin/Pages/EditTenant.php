<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;

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
            TextInput::make('name'),
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
        ])->columns(2);
    }
}
