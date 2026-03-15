<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use App\Mail\TenantInvitation;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;

final class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    private ?string $ownerEmail = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract owner_email before saving to Tenant model
        $this->ownerEmail = $data['owner_email'] ?? null;

        unset($data['owner_email']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $ownerEmail = $this->ownerEmail ?? null;

        if ($ownerEmail) {
            $user = User::firstOrCreate(
                ['email' => $ownerEmail],
                [
                    'name' => explode('@', $ownerEmail)[0],
                    'tenant_id' => $this->record->id,
                    'password' => bcrypt(str()->random(16)),
                ]
            );

            // Assign tenant_admin role to user
            setPermissionsTeamId($this->record->id);
            $role = Role::where('name', 'tenant_admin')
                ->where('tenant_id', $this->record->id)
                ->first();
            if ($role) {
                $user->roles()->syncWithPivotValues([$role->id], [
                    'tenant_id' => $this->record->id,
                ]);
            }

            // Generate signed URL for account setup
            $signedUrl = URL::temporarySignedRoute(
                'account.setup',
                now()->addDays(7),
                ['user' => $user->id]
            );

            // Send invitation email
            Mail::queue(new TenantInvitation($user, $this->record, $signedUrl));
        }
    }
}
