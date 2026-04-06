<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampExpenses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
            ToggleButtons::make('is_paid_by_camp')
                ->label(__('Paid by'))
                ->boolean(
                    trueLabel: __('Camp'),
                    falseLabel: __('Me'),
                )
                ->icons([
                    true => Heroicon::OutlinedBuildingLibrary,
                    false => Heroicon::OutlinedUser,
                ])
                ->colors([
                    true => 'success',
                    false => 'warning',
                ])
                ->default(false)
                ->inline()
                ->required(),
            FileUpload::make('receipt')
                ->label(__('Receipt'))
                ->disk('local')
                ->maxSize(5120)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']),
            Textarea::make('description')
                ->label(__('Description'))
                ->rows(3),
            Hidden::make('user_id')->default(auth()->id()),
        ]);
    }
}
