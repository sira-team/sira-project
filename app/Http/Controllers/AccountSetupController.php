<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountSetupController extends Controller
{
    public function show(Request $request, User $user)
    {
        // Verify the signed URL is valid
        if (! $request->hasValidSignature()) {
            abort(403, 'This link has expired or is invalid.');
        }

        return view('account.setup', ['user' => $user]);
    }

    public function store(Request $request, User $user)
    {
        // Verify the signed URL is valid
        if (! $request->hasValidSignature()) {
            abort(403, 'This link has expired or is invalid.');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        event(new PasswordReset($user));

        // Redirect to tenant admin panel
        $team = $user->team;
        $host = $request->getHost();
        $parts = explode('.', $host);

        if (count($parts) >= 3) {
            $parts[0] = $team->slug;
            $newHost = implode('.', $parts);
            $scheme = $request->getScheme();

            return redirect()->to("{$scheme}://{$newHost}/admin");
        }

        // Fallback: redirect to home if subdomain structure is unexpected
        return redirect('/');
    }
}
