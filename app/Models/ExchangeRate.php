<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $table = 'exchange_rates';

    protected $fillable = [
        'exchange_rate_snapshot_id',
        'currency',
        'rate',
    ];

    public function snapshot()
    {
        return $this->belongsTo(ExchangeRateSnapshot::class);
    }
}
