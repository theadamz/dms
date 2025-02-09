<?php

use App\Enums\DocumentCommentType;
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
        Schema::create('document_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('document_id')->index();
            $table->uuid('document_file_id')->nullable()->index()->comment('when comment is on document level, this field is null');
            $table->string('type', 50)->index()->default(DocumentCommentType::COMMENT->value)->comment('get from DocumentCommentType enum');
            $table->uuid('user_id')->index();
            $table->text('comment');
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();

            // constraints
            $table->unique(['document_id', 'document_file_id', 'user_id']);

            // FK
            $table->foreign('document_id')->references('id')->on('documents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('document_file_id')->references('id')->on('document_files')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_comments');
    }
};
