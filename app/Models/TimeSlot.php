<?php
declare(strict_types=1);

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_at',
        'end_at',
        'appointment_id',
    ];

    protected $casts = [
        'start_at' => 'datetime:Y-m-d\TH:i:s\Z',
        'end_at' => 'datetime:Y-m-d\TH:i:s\Z',
    ];

    protected $appends = [
        'business_timezone',
    ];

    public function scopeAvailable(Builder $query): void
    {
        $query->whereNull('appointment_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function assignTo(Appointment $appointment): void
    {
        $this->update([
            'appointment_id' => $appointment->id
        ]);
    }

    public function release(): void
    {
        $this->update([
            'appointment_id' => null
        ]);
    }

    public function isPast(): bool
    {
        return Carbon::parse($this->start_at)->isPast();
    }

    public function getBusinessTimezoneAttribute(): string
    {
        return config('app.business_timezone');
    }

    public function businessStart(): Carbon
    {
        return Carbon::parse($this->start_at)->timezone(config('app.business_timezone'));
    }

    public function businessEnd(): Carbon
    {
        return Carbon::parse($this->end_at)->timezone(config('app.business_timezone'));
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d\TH:i:s\.v\Z');
    }
}
