<?php

declare(strict_types=1);

use App\Enums\FeatureFlag;

describe('Feature enum', function () {
    it('has correct raw values', function () {
        expect(FeatureFlag::ExpoPanel->value)->toBe('expo-panel');
        expect(FeatureFlag::AcademyPanel->value)->toBe('academy-panel');
        expect(FeatureFlag::AcademyManager->value)->toBe('academy-content-management');
    });

    it('tenantFeatures returns only tenant-scoped features', function () {
        $features = FeatureFlag::tenantFeatures();
        expect($features)->toContain(FeatureFlag::ExpoPanel);
        expect($features)->toContain(FeatureFlag::AcademyPanel);
        expect($features)->not->toContain(FeatureFlag::AcademyManager);
    });

    it('userFeatures returns only user-scoped features', function () {
        $features = FeatureFlag::userFeatures();
        expect($features)->toContain(FeatureFlag::AcademyManager);
        expect($features)->not->toContain(FeatureFlag::ExpoPanel);
    });

    it('all features have a label', function () {
        foreach (FeatureFlag::cases() as $feature) {
            expect($feature->label())->toBeString()->not->toBeEmpty();
        }
    });
});
