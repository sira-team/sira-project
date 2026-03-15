<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\AcademySessionResource;

final class CreateAcademySession extends CreateRecord
{
    protected static string $resource = AcademySessionResource::class;
}
