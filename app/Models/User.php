<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_key',
        'api_plan',
        'stripe_customer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_key',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function apiMonthlyUsages(): HasMany
    {
        return $this->hasMany(ApiMonthlyUsage::class);
    }

    public function apiSubscription(): HasOne
    {
        return $this->hasOne(ApiSubscription::class);
    }

    public function billingInvoices(): HasMany
    {
        return $this->hasMany(BillingInvoice::class);
    }

    public function apiPlanConfig(): array
    {
        $plan = $this->api_plan ?: config('api_plans.default', 'free');

        return config("api_plans.plans.{$plan}", config('api_plans.plans.free'));
    }

    public function apiPlanName(): string
    {
        return $this->apiPlanConfig()['name'] ?? 'Free';
    }

    public function monthlyApiRequestLimit(): int
    {
        return (int) ($this->apiPlanConfig()['monthly_requests'] ?? 750);
    }

    public function currentApiUsageMonth(): Carbon
    {
        return now()->startOfMonth();
    }

    public function currentMonthlyApiUsage(): int
    {
        return (int) $this->apiMonthlyUsages()
            ->whereDate('usage_month', $this->currentApiUsageMonth())
            ->value('request_count');
    }

    public function remainingMonthlyApiRequests(): int
    {
        return max(0, $this->monthlyApiRequestLimit() - $this->currentMonthlyApiUsage());
    }

    public function conversions(): HasMany
    {
        return $this->hasMany(Conversion::class);
    }
}
