<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Camp\Filament\Resources\Hostels\HostelResource;

final class ViewHostel extends ViewRecord
{
    protected static string $resource = HostelResource::class;
}
