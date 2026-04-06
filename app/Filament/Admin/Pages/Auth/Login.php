<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

final class Login extends BaseLogin
{
    public function getSubheading(): string|Htmlable|null
    {
        if (! session()->has('join_token')) {
            return null;
        }

        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return __('filament-panels::auth/pages/login.multi_factor.subheading');
        }

        if (! filament()->hasRegistration()) {
            return null;
        }

        return new HtmlString(__('filament-panels::auth/pages/login.actions.register.before').' '.$this->registerAction->toHtml());
    }
}
