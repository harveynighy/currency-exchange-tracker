<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingInvoice extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_invoice_id',
        'stripe_subscription_id',
        'status',
        'currency',
        'amount_due',
        'amount_paid',
        'period_start',
        'period_end',
        'hosted_invoice_url',
        'invoice_pdf',
        'paid_at',
    ];

    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}