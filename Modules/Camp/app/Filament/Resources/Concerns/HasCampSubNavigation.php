<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Concerns;

use Modules\Camp\Filament\Resources\Camps\CampResource;

trait HasCampSubNavigation
{
    public function getSubNavigation(): array
    {
        return CampResource::getRecordSubNavigation($this);
    }

    public function getSubNavigationParameters(): array
    {
        return [
            'record' => $this->getParentRecord(),
        ];
    }
}
