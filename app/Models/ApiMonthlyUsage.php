<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiMonthlyUsage extends Model
{
    protected $fillable = [
        'user_id',
        'usage_month',
        'request_count',
    ];

    protected $casts = [
        'usage_month' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}