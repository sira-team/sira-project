<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampExpenses\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Camp\Enums\CampExpenseCategory;

final class CampExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('category')
                ->label(__('Category'))
                ->options(CampExpenseCategory::class)
                ->required(),
            TextInput::make('title')
                ->label(__('Title'))
                ->required()
                ->maxLength(255),
            TextInput::make('amount')
                ->required()
                ->label(__('Amount (EUR)')),
            Textarea::make('description')
                ->label(__('Description'))
                ->rows(3),
            Hidden::make('user_id')->default(auth()->id()),
        ]);
    }
}
