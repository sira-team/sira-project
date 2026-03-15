<?php

declare(strict_types=1);

use App\Enums\Feature;

describe('Feature enum', function () {
    it('has correct raw values', function () {
        expect(Feature::ExpoPanel->value)->toBe('expo-panel');
        expect(Feature::AcademyPanel->value)->toBe('academy-panel');
        expect(Feature::AcademyContentManagement->value)->toBe('academy-content-management');
    });

    it('tenantFeatures returns only tenant-scoped features', function () {
        $features = Feature::tenantFeatures();
        expect($features)->toContain(Feature::ExpoPanel);
        expect($features)->toContain(Feature::AcademyPanel);
        expect($features)->not->toContain(Feature::AcademyContentManagement);
    });

    it('userFeatures returns only user-scoped features', function () {
        $features = Feature::userFeatures();
        expect($features)->toContain(Feature::AcademyContentManagement);
        expect($features)->not->toContain(Feature::ExpoPanel);
    });

    it('all features have a label', function () {
        foreach (Feature::cases() as $feature) {
            expect($feature->label())->toBeString()->not->toBeEmpty();
        }
    });
});
