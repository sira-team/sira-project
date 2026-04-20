<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Modules\Camp\Filament\Resources\Camps\CampResource;
use Modules\Camp\Filament\Resources\Camps\Schemas\CampWizard;

final class CreateCamp extends CreateRecord
{
    public static string|Alignment $formActionsAlignment = Alignment::End;

    protected static string $resource = CampResource::class;

    protected static bool $canCreateAnother = false;

    public function form(Schema $schema): Schema
    {
        return CampWizard::configure($schema);
    }
}
