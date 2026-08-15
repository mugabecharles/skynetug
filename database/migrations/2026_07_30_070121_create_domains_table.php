<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->string('domain_name');
            $table->string('tld', 20);
            $table->enum('status', ['pending', 'active', 'expired', 'transferred', 'cancelled', 'grace', 'redemption'])->default('pending');
            $table->enum('registration_type', ['register', 'transfer'])->default('register');
            $table->decimal('registration_price', 10, 2)->default(0);
            $table->decimal('renewal_price', 10, 2)->default(0);
            $table->date('registration_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('registrar')->nullable(); // reseller_club, opensrs, namesilo
            $table->string('registrar_id')->nullable();
            $table->string('epp_code')->nullable();
            $table->boolean('is_locked')->default(true);
            $table->boolean('whois_privacy')->default(false);
            $table->boolean('auto_renew')->default(true);
            $table->string('nameserver_1')->nullable();
            $table->string('nameserver_2')->nullable();
            $table->string('nameserver_3')->nullable();
            $table->string('nameserver_4')->nullable();
            $table->timestamp('expiry_reminder_30_sent')->nullable();
            $table->timestamp('expiry_reminder_7_sent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
