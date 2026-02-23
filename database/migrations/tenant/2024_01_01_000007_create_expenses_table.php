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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category'); // equipment, travel, software, etc.
            
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('INR');
            $table->date('expense_date');
            
            $table->string('receipt')->nullable(); // file path
            $table->string('vendor')->nullable();
            
            $table->boolean('is_billable')->default(false);
            $table->boolean('is_reimbursed')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['category', 'expense_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};