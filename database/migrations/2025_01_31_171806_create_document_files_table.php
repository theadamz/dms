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
        Schema::create('document_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('document_id')->index();
            $table->string('file_origin_name');
            $table->string('file_name', 50)->unique();
            $table->bigInteger('file_size')->comment('in bytes');
            $table->string('file_ext', 50);
            $table->string('file_type', 100);
            $table->uuid('created_by')->index();
            $table->timestamp('created_at')->index();

            // unique
            $table->unique(['document_id', 'file_name']);

            // FK
            $table->foreign('document_id')->references('id')->on('documents')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_files');
    }
};
