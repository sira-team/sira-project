<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\AcademySessionResource;

class EditAcademySession extends EditRecord
{
    protected static string $resource = AcademySessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
