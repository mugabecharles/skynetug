<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hosting_account_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['full', 'files', 'database', 'manual'])->default('full');
            $table->enum('schedule', ['daily', 'weekly', 'monthly'])->nullable();
            $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
            $table->string('filename')->nullable();
            $table->string('storage_path')->nullable();
            $table->bigInteger('size_bytes')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
