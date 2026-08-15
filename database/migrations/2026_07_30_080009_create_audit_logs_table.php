<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action_type'); // login, create, update, delete, suspend, etc.
            $table->string('resource_type'); // user, invoice, hosting_account, domain, etc.
            $table->string('resource_id')->nullable();
            $table->text('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type'); // email, sms
            $table->string('event'); // invoice_generated, payment_confirmed, etc.
            $table->string('recipient');
            $table->string('subject')->nullable();
            $table->enum('status', ['queued', 'sent', 'failed'])->default('queued');
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tax_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country', 2)->nullable(); // null = all countries
            $table->decimal('rate', 5, 2); // percentage
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('dns_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['A', 'AAAA', 'CNAME', 'MX', 'TXT', 'NS', 'SRV', 'SOA'])->default('A');
            $table->string('name');
            $table->text('value');
            $table->integer('ttl')->default(3600);
            $table->integer('priority')->default(0); // for MX/SRV
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dns_records');
        Schema::dropIfExists('tax_rules');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('audit_logs');
    }
};
