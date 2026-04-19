<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use BackedEnum;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Enums\CampChecklistItem;
use Modules\Camp\Filament\Resources\Camps\CampResource;
use Modules\Camp\Filament\Resources\Camps\Schemas\CampChecklistForm;
use Modules\Camp\Models\Camp;
use Modules\Camp\ValueObjects\CampChecklist;

/**
 * @property Camp $record
 */
final class ChecklistCamp extends EditRecord
{
    protected static string $resource = CampResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function getNavigationLabel(): string
    {
        return __('Checklist');
    }

    public function form(Schema $schema): Schema
    {
        return CampChecklistForm::configure($schema);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('checklist', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->loadMissing('contract');

        $checklist = $this->record->checklist;

        foreach (CampChecklistItem::cases() as $item) {
            $data[$item->value] = $item->isComputed()
                ? $item->check($this->record)
                : $checklist->isChecked($item);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $toggles = [];

        foreach (CampChecklistItem::toggleableItems() as $item) {
            $toggles[$item->value] = (bool) ($data[$item->value] ?? false);
        }

        return ['checklist' => CampChecklist::fromArray($toggles)];
    }
}
