<?php

declare(strict_types=1);

namespace Modules\Expo\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Storage;
use Modules\Expo\Models\Station;
use Modules\Expo\Models\StationDigitalMaterial;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DigitalMaterialController extends Controller
{
    public function download(Tenant $tenant, Station $station, StationDigitalMaterial $material): BinaryFileResponse
    {
        // Verify user belongs to the tenant
        if (auth()->user()?->tenants->where('id', $tenant->id)->isEmpty() ?? true) {
            throw new AuthorizationException;
        }

        // Verify the material belongs to the station and tenant
        if ($material->station_id !== $station->id || $material->tenant_id !== $tenant->id) {
            throw new AuthorizationException;
        }

        if (! Storage::disk('private')->exists($material->file_path)) {
            abort(404);
        }

        return Storage::disk('private')->download(
            $material->file_path,
            $material->title.'.'.$material->file_type->value
        );
    }
}
