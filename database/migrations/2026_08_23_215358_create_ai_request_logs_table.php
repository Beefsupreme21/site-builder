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
        Schema::create('ai_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('block_id')->nullable()->constrained()->nullOnDelete();
            $table->string('agent');
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->string('conversation_id', 36)->nullable();
            $table->text('prompt');
            $table->longText('reply')->nullable();
            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->unsignedInteger('reasoning_tokens')->default(0);
            $table->json('skills')->nullable();
            $table->string('status', 20)->default('succeeded');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['site_id', 'created_at']);
            $table->index(['block_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_request_logs');
    }
};
