<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyTenant\Resources\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Academy\Filament\AcademyTenant\Resources\EnrollmentResource;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
