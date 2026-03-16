<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Camp\Filament\Resources\Hostels\HostelResource;

final class CreateHostel extends CreateRecord
{
    protected static string $resource = HostelResource::class;
}
