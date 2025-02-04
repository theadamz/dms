<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('password');
            $table->uuid('role_id')->index();
            $table->uuid('department_id')->index();
            $table->string('timezone', 50)->default(config('setting.local.timezone'));
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_change_password_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('picture', 50)->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // FK
            $table->foreign('role_id')->references('id')->on('roles')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('department_id')->references('id')->on('departments')->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
