<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('hosting_package_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('server_id')->nullable()->constrained()->onDelete('set null');
            $table->string('domain');
            $table->string('username')->unique();
            $table->string('cpanel_password')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'terminated'])->default('pending');
            $table->enum('billing_cycle', ['monthly', 'yearly', 'biennially'])->default('yearly');
            $table->decimal('price', 10, 2)->default(0);
            $table->date('registration_date')->nullable();
            $table->date('next_due_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('cpanel_url')->nullable();
            $table->text('suspension_reason')->nullable();
            $table->bigInteger('disk_used_mb')->default(0);
            $table->bigInteger('bandwidth_used_mb')->default(0);
            $table->timestamp('cpanel_created_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_accounts');
    }
};
