<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\ExpoRequests;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Expo\Filament\Resources\ExpoRequests\Pages\CreateExpoRequest;
use Modules\Expo\Filament\Resources\ExpoRequests\Pages\EditExpoRequest;
use Modules\Expo\Filament\Resources\ExpoRequests\Pages\ListExpoRequests;
use Modules\Expo\Filament\Resources\ExpoRequests\Schemas\ExpoRequestForm;
use Modules\Expo\Filament\Resources\ExpoRequests\Tables\ExpoRequestsTable;
use Modules\Expo\Models\ExpoRequest;

final class ExpoRequestResource extends Resource
{
    protected static ?string $model = ExpoRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ExpoRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpoRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpoRequests::route('/'),
            'create' => CreateExpoRequest::route('/create'),
            'edit' => EditExpoRequest::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
