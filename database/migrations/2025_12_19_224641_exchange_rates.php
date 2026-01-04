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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exchange_rate_snapshot_id')
                ->constrained('exchange_rate_snapshots')
                ->cascadeOnDelete();

            $table->char('currency', 3);

            $table->decimal('rate', 18, 8);

            $table->timestamps();

            $table->unique(['exchange_rate_snapshot_id', 'currency'], 'rates_unique_snapshot_currency');
            $table->index(['currency', 'exchange_rate_snapshot_id'], 'rates_currency_snapshot_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
