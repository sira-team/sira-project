<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Filament\Resources\Camps\CampResource;
use Modules\Camp\Filament\Resources\Camps\Schemas\CampContentEditorForm;
use Modules\Camp\Models\Camp;

/**
 * @property Camp $record
 */
final class EditCampContent extends EditRecord
{
    protected static string $resource = CampResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCursorArrowRipple;

    protected static ?string $navigationLabel = 'Content';

    public static function getNavigationLabel(): string
    {
        return __('Page');
    }

    public function getTitle(): string
    {
        return __('Content');
    }

    public function form(Schema $schema): Schema
    {
        return CampContentEditorForm::configure($schema);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label(__('View'))
                ->openUrlInNewTab()
                ->icon(Heroicon::OutlinedGlobeAlt)
                ->url(fn () => route('camp.show', ['camp' => $this->record, 'tenant' => $this->record->tenant])),
            Action::make('back')
                ->color('gray')
                ->label(__('Back'))
                ->url(EditCamp::getUrl(['record' => $this->record])),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return null;
    }
}
