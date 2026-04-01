<?php

declare(strict_types=1);

use Tests\TestCase;

pest()->extend(TestCase::class)
    ->in('Unit', 'Feature');

require_once __DIR__.'/../../../tests/Helpers/TenantHelper.php';