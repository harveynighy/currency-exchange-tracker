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

    public function rates()
    {
        return $this->hasMany(ExchangeRate::class);
    }
}
