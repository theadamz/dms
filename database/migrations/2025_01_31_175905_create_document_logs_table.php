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
        Schema::create('document_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('document_id')->index();
            $table->uuid('user_id')->index();
            $table->string('action');
            $table->timestamp('created_at')->index();

            // constraints
            $table->unique(['document_id', 'user_id', 'created_at']);

            // FK
            $table->foreign('document_id')->references('id')->on('documents')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_logs');
    }
};
