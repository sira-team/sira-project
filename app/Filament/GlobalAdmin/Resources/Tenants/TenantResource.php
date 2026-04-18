<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\Tenants;

use App\Filament\GlobalAdmin\Resources\Tenants\Schemas\TenantForm;
use App\Filament\GlobalAdmin\Resources\Tenants\Tables\TenantsTable;
use App\Models\Tenant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $modelLabel = 'Tenant';

    protected static ?string $navigationLabel = 'Tenants';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return TenantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return self::getPluralModelLabel();
    }

    public static function getModelLabel(): string
    {
        return __('Tenant');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tenants');
    }
}
