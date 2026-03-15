<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\ExpoRequests\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Expo\Filament\Resources\ExpoRequests\ExpoRequestResource;

class CreateExpoRequest extends CreateRecord
{
    protected static string $resource = ExpoRequestResource::class;
}
