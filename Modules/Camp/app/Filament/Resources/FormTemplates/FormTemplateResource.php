<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\FormTemplates;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\FormTemplates\Pages\CreateFormTemplate;
use Modules\Camp\Filament\Resources\FormTemplates\Pages\EditFormTemplate;
use Modules\Camp\Filament\Resources\FormTemplates\Pages\ListFormTemplates;
use Modules\Camp\Filament\Resources\FormTemplates\Schemas\FormTemplateForm;
use Modules\Camp\Filament\Resources\FormTemplates\Tables\FormTemplatesTable;
use Modules\Camp\Models\FormTemplate;

final class FormTemplateResource extends Resource
{
    protected static ?string $model = FormTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return FormTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFormTemplates::route('/'),
            'create' => CreateFormTemplate::route('/create'),
            'edit' => EditFormTemplate::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Form Template');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Form Templates');
    }

    public static function getNavigationLabel(): string
    {
        return __('Form Templates');
    }
}
