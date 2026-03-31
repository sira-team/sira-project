<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Unit', 'Feature');

require_once __DIR__.'/../../../tests/Helpers/TenantHelper.php';
