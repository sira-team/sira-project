<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\AcademyLevelResource;

final class CreateAcademyLevel extends CreateRecord
{
    protected static string $resource = AcademyLevelResource::class;
}
