<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hosting_account_id')->nullable()->constrained()->onDelete('set null');
            $table->string('email')->unique();
            $table->string('domain');
            $table->string('username');
            $table->integer('quota_mb')->default(1024); // 1GB default
            $table->integer('used_mb')->default(0);
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->json('forwarding_rules')->nullable();
            $table->timestamp('quota_warning_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
