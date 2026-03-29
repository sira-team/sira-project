<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampEmailTemplates\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
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
                ->toolbarButtons([
                    ['bold', 'italic', 'underline', 'strike', 'link'],
                    ['h1', 'h2', 'h3'],
                    ['bulletList', 'orderedList', 'blockquote'],
                    ['undo', 'redo'],
                ]),

            Placeholder::make('available_tags')
                ->label(__('Available merge tags'))
                ->content(function (CampEmailTemplate $record): HtmlString {
                    $tags = collect($record->key->mergeTags())
                        ->map(fn (string $tag): string => '<code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:0.8em">'.$tag.'</code>')
                        ->implode(' ');

                    return new HtmlString($tags);
                })
                ->columnSpanFull(),
        ]);
    }
}
