<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\FeatureFlag;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Laravel\Pennant\Feature;
use Spatie\Permission\PermissionRegistrar;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();

        setPermissionsTeamId($tenant->id);

        foreach (self::users() as $definition) {
            $user = User::firstOrCreate(
                ['email' => $definition['email']],
                [
                    'name' => $definition['name'],
                    'password' => 'password',
                    'email_verified_at' => now(),
                    'tenant_id' => $tenant->id,
                ]
            );

            $user->syncRoles([$definition['role']]);

            foreach ($definition['flags'] as $flag) {
                Feature::for($user)->activate($flag->value);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Test users created for local development and testing.
     *
     * All users belong to the default tenant and use 'password' as their password.
     * The admin user already exists from app:setup — we use firstOrCreate to be safe.
     *
     * @return array<int, array<string, mixed>>
     */
    private static function users(): array
    {
        return [
            [
                'email' => 'admin@example.com',
                'name' => 'Admin User',
                'role' => 'tenant_admin',
                'flags' => [FeatureFlag::GlobalAdmin, FeatureFlag::AcademyManager],
            ],
            [
                'email' => 'expo@example.com',
                'name' => 'Expo Manager',
                'role' => 'expo_manager',
                'flags' => [],
            ],
            [
                'email' => 'camp@example.com',
                'name' => 'Camp Manager',
                'role' => 'camp_manager',
                'flags' => [],
            ],
            [
                'email' => 'academy@example.com',
                'name' => 'Academy Manager',
                'role' => 'academy_manager',
                'flags' => [],
            ],
            [
                'email' => 'academy-manager@example.com',
                'name' => 'Curriculum Manager',
                'role' => 'member',
                'flags' => [FeatureFlag::AcademyManager],
            ],
        ];
    }
}
