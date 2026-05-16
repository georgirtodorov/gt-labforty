<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'identifier',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
