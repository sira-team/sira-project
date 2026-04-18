<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Enums\CampChecklistItem;

final class CampChecklistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)
                ->columns(4)
                ->schema(self::buildRows(CampChecklistItem::cases())),
        ])->columns(1);
    }

    /** @param CampChecklistItem[] $items */
    private static function buildRows(array $items): array
    {
        return array_merge(...array_map(
            fn (CampChecklistItem $item) => [
                TextEntry::make($item->value.'_info')
                    ->label($item->getLabel())
                    ->state($item->getDescription())
                    ->columnSpan(3),
                Toggle::make($item->value)
                    ->hiddenLabel()
                    ->inline(false)
                    ->disabled($item->isComputed())
                    ->onIcon($item->isComputed() ? Heroicon::Bolt : null)
                    ->offIcon($item->isComputed() ? Heroicon::Bolt : null)
                    ->columnSpan(1),
            ],
            $items,
        ));
    }
}
