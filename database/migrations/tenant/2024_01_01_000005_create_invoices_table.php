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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('milestone_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Invoice Details
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('currency', 3)->default('INR');
            
            // Amounts
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->string('discount_type')->default('fixed'); // fixed, percent
            
            // Tax
            $table->boolean('tax_enabled')->default(true);
            $table->string('tax_type')->default('gst'); // gst, vat, none
            $table->decimal('tax_rate', 5, 2)->default(18);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->string('gst_number')->nullable();
            
            // TDS
            $table->boolean('tds_applicable')->default(false);
            $table->decimal('tds_rate', 5, 2)->default(0);
            $table->decimal('tds_amount', 12, 2)->default(0);
            
            // Totals
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_amount', 12, 2)->default(0);
            
            // Status
            $table->enum('status', [
                'draft',
                'sent',
                'viewed',
                'partial',
                'paid',
                'overdue',
                'cancelled'
            ])->default('draft');
            
            // Client Info (snapshot)
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->text('client_address')->nullable();
            $table->string('client_gst')->nullable();
            
            // Items (JSON)
            $table->json('items');
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            
            // Tracking
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            
            // PDF
            $table->string('pdf_path')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['brand_id', 'status']);
            $table->index('invoice_date');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};