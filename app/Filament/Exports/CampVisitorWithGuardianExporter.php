<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Modules\Camp\Models\CampVisitor;

final class CampVisitorWithGuardianExporter extends Exporter
{
    protected static ?string $model = CampVisitor::class;

    public static function getColumns(): array
    {
        return [
            ...CampVisitorExporter::getColumns(),
            ExportColumn::make('visitor.guardian.name')->label(__('Guardian')),
            ExportColumn::make('visitor.guardian.email')->label(__('Guardian Email')),
            ExportColumn::make('visitor.guardian.phone')->label(__('Guardian Phone')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return CampVisitorExporter::getCompletedNotificationBody($export);
    }
}
