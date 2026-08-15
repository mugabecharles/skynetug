<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ssl_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hosting_account_id')->nullable()->constrained()->onDelete('set null');
            $table->string('domain');
            $table->enum('type', ['dv', 'ov', 'ev', 'wildcard', 'lets_encrypt'])->default('lets_encrypt');
            $table->enum('status', ['pending', 'active', 'expired', 'revoked', 'failed'])->default('pending');
            $table->string('provider')->nullable(); // lets_encrypt, comodo, etc.
            $table->text('certificate')->nullable();
            $table->text('private_key')->nullable();
            $table->text('ca_bundle')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('renewal_started_at')->nullable();
            $table->timestamp('expiry_reminder_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ssl_certificates');
    }
};
