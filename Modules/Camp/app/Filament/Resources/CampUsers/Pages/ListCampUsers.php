<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampUsers\Pages;

use BackedEnum;
use Filament\Resources\Pages\Concerns\InteractsWithParentRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Filament\Resources\CampUsers\CampUserResource;
use Modules\Camp\Filament\Resources\Concerns\HasCampSubNavigation;
use Modules\Camp\Models\Camp;

/**
 * @property Camp $parentRecord
 */
final class ListCampUsers extends ListRecords
{
    use HasCampSubNavigation;
    use InteractsWithParentRecord;

    protected static string $resource = CampUserResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function getNavigationLabel(): string
    {
        return __('Staff');
    }

    public function getTitle(): string
    {
        return $this->parentRecord->name.' › '.__('Staff');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
