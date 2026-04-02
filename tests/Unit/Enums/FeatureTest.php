<?php

declare(strict_types=1);

use App\Enums\FeatureFlag;

describe('Feature enum', function () {
    it('has correct raw values', function () {
        expect(FeatureFlag::ExpoPanel->value)->toBe('expo')
            ->and(FeatureFlag::AcademyPanel->value)->toBe('academy')
            ->and(FeatureFlag::AcademyManager->value)->toBe('academy-content');
    });

    it('tenantFeatures returns only tenant-scoped features', function () {
        $features = FeatureFlag::tenantFeatures();
        expect($features)->toContain(FeatureFlag::ExpoPanel)
            ->and($features)->toContain(FeatureFlag::AcademyPanel)
            ->and($features)->not->toContain(FeatureFlag::AcademyManager);
    });

    it('userFeatures returns only user-scoped features', function () {
        $features = FeatureFlag::userFeatures();
        expect($features)->toContain(FeatureFlag::AcademyManager)
            ->and($features)->not->toContain(FeatureFlag::ExpoPanel);
    });

    it('all features have a label', function () {
        foreach (FeatureFlag::cases() as $feature) {
            expect($feature->label())->toBeString()->not->toBeEmpty();
        }
    });
});
