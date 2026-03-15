<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyTenant\Resources\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Academy\Filament\AcademyTenant\Resources\EnrollmentResource;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
