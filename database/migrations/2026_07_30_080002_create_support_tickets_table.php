<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('subject');
            $table->enum('category', ['technical', 'billing', 'sales', 'general'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'waiting_reply', 'resolved', 'closed'])->default('open');
            $table->text('description');
            $table->timestamp('last_reply_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_staff_reply')->default(false);
            $table->timestamps();
        });

        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->nullable()->constrained('support_tickets')->onDelete('cascade');
            $table->foreignId('reply_id')->nullable()->constrained('ticket_replies')->onDelete('cascade');
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->integer('file_size');
            $table->timestamps();
        });

        Schema::create('knowledge_base', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('category');
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->integer('views')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_base');
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('support_tickets');
    }
};
