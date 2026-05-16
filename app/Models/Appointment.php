<?php
declare(strict_types=1);

namespace App\Models;

use App\Events;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes, HasFactory;

    const string STATUS_REQUESTED = 'requested';
    const string STATUS_CONFIRMED = 'confirmed';

    protected $table = 'appointments';

    protected $fillable = [
        'client_id',
        'status',
        'notification_type',
        'description',
    ];

    protected $attributes = [
        'status' => self::STATUS_REQUESTED,
    ];

    protected $dispatchesEvents = [
        'created' => Events\Appointments\AppointmentCreated::class,
        'updated' => Events\Appointments\AppointmentUpdated::class,
        'deleted' => Events\Appointments\AppointmentDeleted::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function timeSlot(): HasOne
    {
        return $this->hasOne(TimeSlot::class, 'appointment_id');
    }
}
