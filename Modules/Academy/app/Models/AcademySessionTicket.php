<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academy\Database\Factories\AcademySessionTicketFactory;

class AcademySessionTicket extends Model
{
    use HasFactory;

    protected $fillable = ['academy_enrollment_id', 'academy_session_id', 'code', 'issued_at'];

    protected function casts(): array
    {
        return ['issued_at' => 'datetime'];
    }

    protected static function newFactory(): AcademySessionTicketFactory
    {
        return AcademySessionTicketFactory::new();
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(AcademyEnrollment::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(AcademySession::class, 'academy_session_id');
    }
}
