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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('platform')->nullable(); // instagram, youtube, etc.
            $table->string('campaign_type')->nullable(); // sponsored, barter, affiliate
            
            // Financials
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('INR');
            $table->decimal('advance_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            
            // Dates
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('deliverable_date')->nullable();
            
            // Status
            $table->enum('status', [
                'draft',
                'negotiation',
                'confirmed',
                'in_progress',
                'delivered',
                'completed',
                'cancelled'
            ])->default('draft');
            
            $table->enum('payment_status', [
                'pending',
                'partial',
                'completed'
            ])->default('pending');
            
            // Deliverables
            $table->json('deliverables')->nullable();
            $table->text('notes')->nullable();
            
            // Agency/Manager Split
            $table->decimal('agency_commission_percent', 5, 2)->default(0);
            $table->decimal('manager_commission_percent', 5, 2)->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['brand_id', 'status']);
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};