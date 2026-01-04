<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exchange_rate_snapshots', function (Blueprint $table) {
            $table->id();

            // day of snapshot
            $table->date('rate_date');

            // Base currency of snapshot
            $table->char('base', 3)->default('USD');

            // Provider of fX data (don't need now but may be helpful if a change of providers happens). Could be helpful for debugging down the line.
            $table->string('provider', 50)->default('default');

            $table->timestamp('fetched_at')->nullable();

            // Have all currencies been added to snapshot?
            $table->boolean('is_complete')->default(false);

            $table->timestamps();

            // prevent dupes and make look ups faster - base is important here due to how the provider calculates the data + maths rounding and precision issues.
            $table->unique(['rate_date', 'base', 'provider'], 'snapshots_unique_day_base_provider');
            $table->index(['rate_date', 'provider'], 'snapshots_rate_date_provider_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rate_snapshots');
    }
};
