<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hostname');
            $table->string('ip_address', 45);
            $table->enum('type', ['shared', 'vps', 'dedicated'])->default('shared');
            $table->string('username')->nullable();
            $table->text('api_hash')->nullable();
            $table->integer('max_accounts')->default(500);
            $table->boolean('is_active')->default(true);
            $table->boolean('nameserver_1')->default(false);
            $table->string('ns1')->nullable();
            $table->string('ns2')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
