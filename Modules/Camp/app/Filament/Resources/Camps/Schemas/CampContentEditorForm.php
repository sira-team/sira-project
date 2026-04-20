<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class CampContentEditorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Builder::make('content')
                ->label(__('Page Content'))
                ->addActionLabel(__('Add Block'))
                ->collapsible()
                ->cloneable()
                ->blocks([
                    self::heroBlock(),
                    self::paragraphBlock(),
                    self::imageBlock(),
                    self::highlightsBlock(),
                    self::infoBoxBlock(),
                    self::quoteBlock(),
                    self::scheduleBlock(),
                    self::ctaBlock(),
                ]),
        ])->columns(1);
    }

    private static function heroBlock(): Block
    {
        return Block::make('hero')
            ->label(__('Hero Banner'))
            ->icon('heroicon-o-sparkles')
            ->schema([
                TextInput::make('tagline')
                    ->label(__('Tagline'))
                    ->maxLength(200),
                FileUpload::make('image')
                    ->label(__('Background Image'))
                    ->image()
                    ->imageEditor()
                    ->maxSize(4096)
                    ->columnSpanFull(),
            ])->columns(1);
    }

    private static function paragraphBlock(): Block
    {
        return Block::make('paragraph')
            ->label(__('Text'))
            ->icon('heroicon-o-document-text')
            ->schema([
                TextInput::make('heading')
                    ->label(__('Heading'))
                    ->maxLength(150),
                RichEditor::make('body')
                    ->label(__('Content'))
                    ->required()
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'link'],
                        ['h2', 'h3'],
                        ['bulletList', 'orderedList', 'blockquote'],
                        ['undo', 'redo'],
                    ]),
            ])->columns(1);
    }

    private static function imageBlock(): Block
    {
        return Block::make('image')
            ->label(__('Image'))
            ->icon('heroicon-o-photo')
            ->schema([
                FileUpload::make('url')
                    ->label(__('Image'))
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->maxSize(4096)
                    ->columnSpanFull(),
                TextInput::make('caption')
                    ->label(__('Caption'))
                    ->maxLength(200),
                TextInput::make('alt')
                    ->label(__('Alt Text'))
                    ->maxLength(200),
            ])->columns(2);
    }

    private static function highlightsBlock(): Block
    {
        return Block::make('highlights')
            ->label(__('Highlights'))
            ->icon('heroicon-o-star')
            ->schema([
                TextInput::make('heading')
                    ->label(__('Section Heading'))
                    ->maxLength(120),
                Repeater::make('items')
                    ->label(__('Items'))
                    ->schema([
                        Select::make('icon')
                            ->label(__('Icon'))
                            ->options(self::iconOptions())
                            ->searchable()
                            ->required(),
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(80),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(2)
                            ->maxLength(300),
                    ])
                    ->columns(3)
                    ->minItems(1)
                    ->maxItems(12)
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
            ])->columns(1);
    }

    private static function infoBoxBlock(): Block
    {
        return Block::make('info_box')
            ->label(__('Info Box'))
            ->icon('heroicon-o-information-circle')
            ->schema([
                TextInput::make('heading')
                    ->label(__('Heading'))
                    ->default(__('Key Details'))
                    ->maxLength(120),
                Repeater::make('items')
                    ->label(__('Items'))
                    ->schema([
                        TextInput::make('label')
                            ->label(__('Label'))
                            ->required()
                            ->maxLength(60),
                        TextInput::make('value')
                            ->label(__('Value'))
                            ->required()
                            ->maxLength(200),
                    ])
                    ->columns(2)
                    ->minItems(1)
                    ->maxItems(10)
                    ->reorderable()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
            ])->columns(1);
    }

    private static function quoteBlock(): Block
    {
        return Block::make('quote')
            ->label(__('Quote'))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->schema([
                Textarea::make('text')
                    ->label(__('Quote Text'))
                    ->required()
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),
                TextInput::make('author')
                    ->label(__('Author'))
                    ->maxLength(100),
                TextInput::make('role')
                    ->label(__('Author Role / Context'))
                    ->maxLength(100),
            ])->columns(2);
    }

    private static function scheduleBlock(): Block
    {
        return Block::make('schedule')
            ->label(__('Schedule'))
            ->icon('heroicon-o-calendar-days')
            ->schema([
                TextInput::make('heading')
                    ->label(__('Section Heading'))
                    ->default(__('Program'))
                    ->maxLength(120),
                Repeater::make('days')
                    ->label(__('Days'))
                    ->schema([
                        TextInput::make('day')
                            ->label(__('Day / Date'))
                            ->required()
                            ->maxLength(60),
                        Repeater::make('slots')
                            ->label(__('Time Slots'))
                            ->schema([
                                TextInput::make('time')
                                    ->label(__('Time'))
                                    ->placeholder('09:00')
                                    ->maxLength(20),
                                TextInput::make('activity')
                                    ->label(__('Activity'))
                                    ->required()
                                    ->maxLength(120),
                            ])
                            ->columns(2)
                            ->minItems(1)
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => mb_trim(($state['time'] ?? '').' '.($state['activity'] ?? '')) ?: null),
                    ])
                    ->minItems(1)
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['day'] ?? null),
            ])->columns(1);
    }

    private static function ctaBlock(): Block
    {
        return Block::make('cta')
            ->label(__('Call to Action'))
            ->icon('heroicon-o-cursor-arrow-ripple')
            ->schema([
                TextInput::make('headline')
                    ->label(__('Headline'))
                    ->required()
                    ->maxLength(120)
                    ->columnSpanFull(),
                Textarea::make('subtext')
                    ->label(__('Subtext'))
                    ->rows(2)
                    ->maxLength(300)
                    ->columnSpanFull(),
                TextInput::make('button_label')
                    ->label(__('Button Label'))
                    ->default(__('Register Now'))
                    ->maxLength(60),
                Toggle::make('show_deadline')
                    ->label(__('Show Registration Deadline'))
                    ->default(true),
            ])->columns(2);
    }

    /**
     * @return array<string, string>
     */
    private static function iconOptions(): array
    {
        return [
            'heroicon-o-star' => __('Star'),
            'heroicon-o-heart' => __('Heart'),
            'heroicon-o-bolt' => __('Bolt'),
            'heroicon-o-fire' => __('Fire'),
            'heroicon-o-check-circle' => __('Check Circle'),
            'heroicon-o-shield-check' => __('Shield Check'),
            'heroicon-o-academic-cap' => __('Academic Cap'),
            'heroicon-o-book-open' => __('Book Open'),
            'heroicon-o-globe-alt' => __('Globe'),
            'heroicon-o-map-pin' => __('Map Pin'),
            'heroicon-o-users' => __('Users'),
            'heroicon-o-user-group' => __('User Group'),
            'heroicon-o-cake' => __('Cake'),
            'heroicon-o-sun' => __('Sun'),
            'heroicon-o-moon' => __('Moon'),
            'heroicon-o-camera' => __('Camera'),
            'heroicon-o-musical-note' => __('Music'),
            'heroicon-o-trophy' => __('Trophy'),
            'heroicon-o-puzzle-piece' => __('Puzzle'),
            'heroicon-o-hand-raised' => __('Hand Raised'),
            'heroicon-o-chat-bubble-left-ellipsis' => __('Chat'),
            'heroicon-o-clock' => __('Clock'),
            'heroicon-o-home' => __('Home'),
            'heroicon-o-building-office' => __('Building'),
            'heroicon-o-tree' => __('Tree'),
        ];
    }
}
