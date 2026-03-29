<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampEmailTemplates;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\CampEmailTemplates\Schemas\CampEmailTemplateForm;
use Modules\Camp\Filament\Resources\CampEmailTemplates\Tables\CampEmailTemplatesTable;
use Modules\Camp\Models\CampEmailTemplate;

final class CampEmailTemplateResource extends Resource
{
    protected static ?string $model = CampEmailTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Email Templates';

    protected static ?string $recordTitleAttribute = 'subject';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return CampEmailTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampEmailTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampEmailTemplates::route('/'),
            'edit' => Pages\EditCampEmailTemplate::route('/{record}/edit'),
        ];
    }
}
