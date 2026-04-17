<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\FormTemplates;

use App\Filament\GlobalAdmin\Resources\FormTemplates\Pages\CreateFormTemplate;
use App\Filament\GlobalAdmin\Resources\FormTemplates\Pages\EditFormTemplate;
use App\Filament\GlobalAdmin\Resources\FormTemplates\Pages\ListFormTemplates;
use App\Filament\GlobalAdmin\Resources\FormTemplates\Schemas\FormTemplateForm;
use App\Filament\GlobalAdmin\Resources\FormTemplates\Tables\FormTemplatesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Camp\Models\FormTemplate;

final class FormTemplateResource extends Resource
{
    protected static ?string $model = FormTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

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
