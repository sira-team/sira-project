<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Camp\Filament\Resources\Camps\CampResource;

final class CreateCamp extends CreateRecord
{
    protected static string $resource = CampResource::class;
}
