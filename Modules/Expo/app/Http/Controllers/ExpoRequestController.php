<?php

declare(strict_types=1);

namespace Modules\Expo\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Expo\Enums\ExpoRequestStatus;
use Modules\Expo\Models\ExpoRequest;

class ExpoRequestController extends Controller
{
    public function form(Tenant $tenant): View
    {
        return view('expo::request-form', ['tenant' => $tenant]);
    }

    public function store(Tenant $tenant, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'organisation_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'preferred_date_from' => 'nullable|date',
            'preferred_date_to' => 'nullable|date|after_or_equal:preferred_date_from',
            'expected_visitors' => 'nullable|integer|min:1',
            'message' => 'nullable|string|max:1000',
        ]);

        $expoRequest = ExpoRequest::create([
            ...$validated,
            'tenant_id' => $tenant->id,
            'status' => ExpoRequestStatus::New,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Your expo request has been received. We will be in touch shortly.');
    }
}
