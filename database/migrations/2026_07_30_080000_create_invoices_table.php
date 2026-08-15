<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['draft', 'unpaid', 'paid', 'overdue', 'cancelled', 'refunded'])->default('unpaid');
            $table->enum('type', ['new_order', 'renewal', 'upgrade', 'addon'])->default('new_order');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('credit', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->date('date_created');
            $table->date('date_due');
            $table->date('date_paid')->nullable();
            $table->decimal('late_fee', 10, 2)->default(0);
            $table->timestamp('late_fee_applied_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
