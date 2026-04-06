<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Support\Enums\Width;

final class Register extends BaseRegister
{
    protected string|Width|null $maxWidth = '2xl';

    protected string $view = 'filament.admin.auth.register';

    public function mount(): void
    {
        if (! session()->has('join_token')) {
            redirect()->to(filament()->getLoginUrl());

            return;
        }

        parent::mount();
    }

    public function getSubheading(): null
    {
        return null;
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        session()->pull('join_token');
        $data['tenant_id'] = session()->pull('join_tenant_id');

        return $data;
    }
}
