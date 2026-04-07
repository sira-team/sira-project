<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\FeatureFlag;
use App\Enums\Gender;
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
                    'gender' => $definition['gender'],
                ]
            );

            $user->syncRoles([trans('roles.'.$definition['role'])]);

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
                'name' => 'Ahmad Abdul-Rahman',
                'gender' => Gender::Male,
                'role' => 'tenant_admin',
                'flags' => [FeatureFlag::GlobalAdmin, FeatureFlag::AcademyManager],
            ],
            [
                'email' => 'expo@example.com',
                'name' => 'Hussam Malek',
                'gender' => Gender::Male,
                'role' => 'expo_manager',
                'flags' => [],
            ],
            [
                'email' => 'camp@example.com',
                'name' => 'Baraa Al-Hassan',
                'gender' => Gender::Female,
                'role' => 'camp_manager',
                'flags' => [],
            ],
            [
                'email' => 'academy@example.com',
                'name' => 'Rawan Uthman',
                'gender' => Gender::Female,
                'role' => 'academy_manager',
                'flags' => [],
            ],
            [
                'email' => 'academy-manager@example.com',
                'name' => 'Husseyn Firas',
                'gender' => Gender::Male,
                'role' => 'member',
                'flags' => [FeatureFlag::AcademyManager],
            ],
        ];
    }
}
