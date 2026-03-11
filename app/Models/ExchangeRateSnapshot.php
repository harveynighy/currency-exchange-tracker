<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRateSnapshot extends Model
{
    protected $table = 'exchange_rate_snapshots';

    protected $fillable = [
        'rate_date',
        'base',
        'provider',
        'fetched_at',
        'is_complete',
    ];

    protected $casts = [
        'rate_date' => 'date',
        'fetched_at' => 'datetime',
        'is_complete' => 'boolean',
    ];

    public function rates()
    {
        return $this->hasMany(ExchangeRate::class);
    }
}
