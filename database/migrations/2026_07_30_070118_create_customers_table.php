<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['shared', 'wordpress', 'vps', 'email', 'backup', 'design', 'ssl'])->default('shared');
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly', 10, 2)->default(0);
            $table->decimal('price_biennially', 10, 2)->default(0);
            $table->integer('disk_space_mb')->default(0); // 0 = unlimited
            $table->integer('bandwidth_mb')->default(0);  // 0 = unlimited
            $table->integer('email_accounts')->default(0);
            $table->integer('databases')->default(0);
            $table->integer('subdomains')->default(0);
            $table->integer('addon_domains')->default(0);
            $table->integer('parked_domains')->default(0);
            $table->boolean('ssl_included')->default(false);
            $table->boolean('softaculous_included')->default(false);
            $table->boolean('backup_included')->default(false);
            $table->json('features')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_packages');
    }
};
