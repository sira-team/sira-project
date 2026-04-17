<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\FormTemplates\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Modules\Camp\Enums\FormFieldType;

final class FormTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label(__('Template Name'))
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            Textarea::make('description')
                ->label(__('Description'))
                ->nullable()
                ->rows(2)
                ->columnSpanFull(),
            Repeater::make('fields')
                ->label(__('Fields'))
                ->relationship('fields')
                ->orderColumn('order')
                ->columnSpanFull()
                ->schema(self::fieldSchema())
                ->addActionLabel(__('Add field'))
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
        ]);
    }

    /**
     * @return array<mixed>
     */
    public static function fieldSchema(): array
    {
        return [
            TextInput::make('label')
                ->label(__('Label'))
                ->required()
                ->maxLength(255)
                ->columnSpan(2),
            Select::make('type')
                ->label(__('Type'))
                ->options(FormFieldType::class)
                ->required()
                ->live()
                ->columnSpan(2),
            Toggle::make('required')
                ->label(__('Required'))
                ->default(false)
                ->columnSpan(1)
                ->visible(fn (Get $get): bool => ! $get('type')?->isStructural()),
            TextInput::make('help_text')
                ->label(__('Help Text'))
                ->maxLength(255)
                ->columnSpan(3)
                ->visible(fn (Get $get): bool => ! $get('type')?->isStructural()),
            TagsInput::make('options')
                ->label(__('Options'))
                ->helperText(__('Enter each option and press Enter.'))
                ->columnSpanFull()
                ->visible(fn (Get $get): bool => (bool) $get('type')?->hasOptions()),
        ];
    }
}
