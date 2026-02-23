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
        Schema::create('waitlist', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique(); // CP + timestamp + random
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('creator_type')->nullable();
            $table->string('followers')->nullable();
            $table->string('monthly_invoices')->nullable();
            $table->string('source')->default('website');
            $table->integer('position')->default(0);
            $table->enum('status', ['pending', 'invited', 'registered'])->default('pending');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamps();
            
            $table->index(['email', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waitlist');
    }
};