<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Models\CampRegistration;
use Modules\Camp\Models\CampRoomAssignment;
use Modules\Camp\Models\HostelRoom;

/**
 * @extends Factory<CampRoomAssignment>
 */
final class CampRoomAssignmentFactory extends Factory
{
    protected $model = CampRoomAssignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'camp_registration_id' => CampRegistration::factory(),
            'hostel_room_id' => HostelRoom::factory(),
            'assigned_at' => now(),
            'assigned_by' => User::factory(),
        ];
    }
}
