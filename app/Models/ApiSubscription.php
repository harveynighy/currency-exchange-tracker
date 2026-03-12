<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan',
        'status',
        'stripe_subscription_id',
        'stripe_customer_id',
        'current_period_end',
        'canceled_at',
    ];

    protected $casts = [
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}