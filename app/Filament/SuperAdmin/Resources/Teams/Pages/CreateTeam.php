<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\Teams\Pages;

use App\Filament\SuperAdmin\Resources\Teams\TeamResource;
use App\Mail\TenantInvitation;
use App\Models\Team;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract owner_email before saving to Team model
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
                    'team_id' => $this->record->id,
                    'password' => bcrypt(str()->random(16)),
                ]
            );

            // Assign tenant_admin role to user
            setPermissionsTeamId($this->record->id);
            $user->assignRole('tenant_admin');

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

    private ?string $ownerEmail = null;
}
