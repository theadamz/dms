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
        Schema::create('role_accesses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('role_id')->index();
            $table->string('code', 50)->index();
            $table->string('permission', 25)->comment('read,edit,delete,validation,etc');
            $table->boolean('is_allowed')->default(true);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();

            // indexes
            $table->unique(['role_id', 'code', 'permission']);
            $table->index(['role_id', 'code']);

            // FK
            $table->foreign('role_id')->references('id')->on('roles')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_accesses');
    }
};
