<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampVisitors\Pages;

use Filament\Resources\Pages\Concerns\InteractsWithParentRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Modules\Camp\Filament\Resources\CampVisitors\CampVisitorResource;
use Modules\Camp\Filament\Resources\CampVisitors\Schemas\CampVisitorInfolist;
use Modules\Camp\Models\CampVisitor;

final class ViewCampVisitor extends ViewRecord
{
    use InteractsWithParentRecord;

    protected static string $resource = CampVisitorResource::class;

    public function infolist(Schema $schema): Schema
    {
        /** @var CampVisitor $record */
        $record = $this->getRecord();
        $record->load('answers.field');

        return CampVisitorInfolist::configure($schema);
    }
}
