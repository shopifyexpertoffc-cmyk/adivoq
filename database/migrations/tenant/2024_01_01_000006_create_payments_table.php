<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('INR');
            $table->date('payment_date');
            
            $table->string('payment_method')->nullable(); // bank_transfer, upi, cash, cheque, etc.
            $table->string('transaction_id')->nullable();
            $table->string('reference_number')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            
            $table->timestamps();
            
            $table->index(['invoice_id', 'status']);
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};