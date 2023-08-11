<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class WheelPointsRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'value',
    ];

    protected $casts = [
        'user_id'=> 'integer',
        'value'=> 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
