<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('stripe_invoice_id', 255)->unique();
            $table->string('stripe_subscription_id', 255)->nullable();
            $table->string('status', 64)->nullable();
            $table->string('currency', 12)->nullable();
            $table->unsignedBigInteger('amount_due')->default(0);
            $table->unsignedBigInteger('amount_paid')->default(0);
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();
            $table->string('hosted_invoice_url', 1024)->nullable();
            $table->string('invoice_pdf', 1024)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_invoices');
    }
};