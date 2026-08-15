<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('referral_code', 20)->unique();
            $table->decimal('commission_rate', 5, 2)->default(10.00); // percentage
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('total_earned', 10, 2)->default(0);
            $table->decimal('total_paid', 10, 2)->default(0);
            $table->integer('total_referrals')->default(0);
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->timestamps();
        });

        Schema::create('affiliate_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->onDelete('cascade');
            $table->foreignId('referred_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('commission', 10, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->timestamps();
        });

        Schema::create('affiliate_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->onDelete('cascade');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_payouts');
        Schema::dropIfExists('affiliate_referrals');
        Schema::dropIfExists('affiliates');
    }
};
