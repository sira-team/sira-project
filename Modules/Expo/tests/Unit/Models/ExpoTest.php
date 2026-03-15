<?php

declare(strict_types=1);

use Modules\Expo\Models\Expo;
use Modules\Expo\Models\ExpoRequest;

describe('Expo model', function () {
    it('can be created from an expo request', function () {
        $request = ExpoRequest::factory()->create();
        $expo = Expo::factory()->fromRequest($request)->create();
        expect($expo->expo_request_id)->toBe($request->id);
        expect($expo->tenant_id)->toBe($request->tenant_id);
    });

    it('soft deletes', function () {
        $expo = Expo::factory()->create();
        $expo->delete();
        expect(Expo::count())->toBe(0);
        expect(Expo::withTrashed()->count())->toBe(1);
    });
});
