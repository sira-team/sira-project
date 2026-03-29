<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampEmailTemplates\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Camp\Models\CampEmailTemplate;

final class CampEmailTemplateForm
{
    public static function configure(Schema $schema): Schema
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
                ->mergeTags(fn (CampEmailTemplate $record): array => collect($record->key->mergeTags())
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
}
