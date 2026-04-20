<?php

declare(strict_types=1);

namespace Modules\Camp\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Camp\Models\Camp;

final class CampController
{
    public function show(Tenant $tenant, Camp $camp): View
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);

        return view('camp::show', compact('camp', 'tenant'));
    }

    public function serveContentImage(Request $request, Tenant $tenant, Camp $camp): Response
    {
        abort_unless($camp->tenant_id === $tenant->id, 404);

        $path = $request->query('path', '');

        abort_if(empty($path), 404);
        abort_unless(Storage::exists($path), 404);

        return response(Storage::get($path), 200, [
            'Content-Type' => Storage::mimeType($path),
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
