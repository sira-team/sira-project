<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;
use Modules\Camp\Models\CampVisitor;

final class CampVisitorExporter extends Exporter
{
    protected static ?string $model = CampVisitor::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('visitor.name')->label(__('Name')),
            ExportColumn::make('visitor.gender')->label(__('Gender')),
            ExportColumn::make('room.name')->label(__('Room')),
            ExportColumn::make('room.floor')->label(__('Floor')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your camp visitor export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
