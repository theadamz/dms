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
        Schema::create('approval_set_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('approval_set_id')->index();
            $table->uuid('user_id')->index();
            $table->smallInteger('order');
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();

            // constraints
            $table->unique(['approval_set_id', 'user_id']);

            // FK
            $table->foreign('approval_set_id')->references('id')->on('approval_sets')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_set_users');
    }
};
