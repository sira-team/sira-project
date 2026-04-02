<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\EmailTemplates;

use App\Enums\FeatureFlag;
use App\Models\EmailTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Camp\Filament\Resources\EmailTemplates\Schemas\EmailTemplateForm;
use Modules\Camp\Filament\Resources\EmailTemplates\Tables\EmailTemplatesTable;

final class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Email Templates';

    protected static ?string $recordTitleAttribute = 'subject';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('scope', FeatureFlag::CampPanel->value);
    }

    public static function form(Schema $schema): Schema
    {
        return EmailTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailTemplates::route('/'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('Email Templates');
    }
}
