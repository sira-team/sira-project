<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyTenant\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Academy\Filament\AcademyTenant\Resources\EnrollmentResource;

final class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;
}
