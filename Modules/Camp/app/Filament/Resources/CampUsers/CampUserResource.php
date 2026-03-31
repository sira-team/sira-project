<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampUsers;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Camps\CampResource;
use Modules\Camp\Filament\Resources\CampUsers\Pages\ListCampUsers;
use Modules\Camp\Filament\Resources\CampUsers\Tables\CampUsersTable;
use Modules\Camp\Models\CampUser;

final class CampUserResource extends Resource
{
    protected static ?string $model = CampUser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $parentResource = CampResource::class;

    protected static bool $isScopedToTenant = false;

    public static function table(Table $table): Table
    {
        return CampUsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampUsers::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }
}
