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
        Schema::create('document_acknowledges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('document_id')->index();
            $table->uuid('user_id')->index();
            $table->smallInteger('order');
            $table->boolean('is_acknowledged')->default(false);
            $table->string('remarks')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();

            // constraints
            $table->unique(['document_id', 'user_id']);

            // FK
            $table->foreign('document_id')->references('id')->on('documents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_acknowledges');
    }
};
