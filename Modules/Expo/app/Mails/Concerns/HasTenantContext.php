<?php

declare(strict_types=1);

namespace Modules\Expo\Mails\Concerns;

trait HasTenantContext
{
    protected function subjectWithTenant(string $subject): string
    {
        return $subject.' — '.$this->expoRequest->tenant->name;
    }
}
