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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Who performed the action
            $table->string('causer_type')->nullable(); // App\Models\Admin, App\Models\Tenant
            $table->unsignedBigInteger('causer_id')->nullable();
            
            // What was affected
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('tenant_id')->nullable();
            
            // Action details
            $table->string('action'); // created, updated, deleted, login, etc.
            $table->string('description')->nullable();
            $table->json('properties')->nullable(); // old/new values
            
            // Request info
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            $table->index(['causer_type', 'causer_id']);
            $table->index(['subject_type', 'subject_id']);
            $table->index('tenant_id');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};