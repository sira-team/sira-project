<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\EmailTemplates;

use App\Enums\FeatureFlag;
use App\Models\EmailTemplate;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Expo\Filament\Resources\EmailTemplates\Pages\EditEmailTemplate;
use Modules\Expo\Filament\Resources\EmailTemplates\Pages\ListEmailTemplates;

final class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('scope', FeatureFlag::ExpoPanel->value);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('subject')
                ->label(__('Subject'))
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            RichEditor::make('body')
                ->label(__('Content'))
                ->required()
                ->columnSpanFull()
                ->mergeTags(fn (EmailTemplate $record): array => collect($record->key->mergeTags())
                    ->mapWithKeys(fn (string $tag): array => [
                        $tag => ucwords(str_replace('_', ' ', $tag)),
                    ])
                    ->all())
                ->toolbarButtons([
                    ['bold', 'italic', 'underline', 'strike', 'link'],
                    ['h2', 'h3'],
                    ['bulletList', 'orderedList', 'blockquote'],
                    ['undo', 'redo'],
                    ['mergeTags'],
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label(__('Type'))
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable(),

                TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label(__('Last Updated'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('key');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailTemplates::route('/'),
            'edit' => EditEmailTemplate::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('Email Templates');
    }
}
