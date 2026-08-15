<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['fixed', 'percentage'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->decimal('minimum_order', 10, 2)->default(0);
            $table->integer('usage_limit')->default(0); // 0 = unlimited
            $table->integer('usage_count')->default(0);
            $table->date('starts_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->json('applicable_packages')->nullable(); // null = all packages
            $table->enum('applicable_billing', ['monthly', 'yearly', 'biennially', 'all'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
