<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyTenant\Resources\Enrollments\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Academy\Filament\AcademyTenant\Resources\Enrollments\EnrollmentResource;

final class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;
}
