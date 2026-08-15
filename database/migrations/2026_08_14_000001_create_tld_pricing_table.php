<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tld_pricing', function (Blueprint $table) {
            $table->id();
            $table->string('tld', 20)->unique();          // .com, .ug, .co.ug
            $table->decimal('register_price', 10, 2);     // 1-year registration
            $table->decimal('renew_price', 10, 2);        // annual renewal
            $table->decimal('transfer_price', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tld_pricing');
    }
};
