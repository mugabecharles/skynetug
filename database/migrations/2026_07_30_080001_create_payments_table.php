<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('gateway', ['mtn_mobile_money', 'airtel_money', 'flutterwave', 'pesapal', 'manual', 'bank_transfer'])->default('manual');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->string('gateway_transaction_ref')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->text('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('processed_by')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('reason');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payments');
    }
};
