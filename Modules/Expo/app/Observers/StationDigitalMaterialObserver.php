<?php

declare(strict_types=1);

namespace Modules\Expo\Observers;

use Illuminate\Support\Facades\Storage;
use Modules\Expo\Enums\DigitalMaterialType;
use Modules\Expo\Models\StationDigitalMaterial;

final class StationDigitalMaterialObserver
{
    public function creating(StationDigitalMaterial $material): void
    {
        $this->populateFileMetadata($material);
    }

    public function updating(StationDigitalMaterial $material): void
    {
        if ($material->isDirty('file_path')) {
            $this->populateFileMetadata($material);
        }
    }

    private function populateFileMetadata(StationDigitalMaterial $material): void
    {
        if (! $material->file_path) {
            return;
        }

        // Extract file extension from file_path
        $extension = pathinfo($material->file_path, PATHINFO_EXTENSION);
        $material->file_type = DigitalMaterialType::from($extension);

        // Get file size from storage
        $filePath = 'private/'.$material->file_path;
        if (Storage::exists($filePath)) {
            $material->file_size = Storage::size($filePath);
        }
    }
}
