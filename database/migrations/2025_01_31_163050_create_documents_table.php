<?php

use App\Enums\DocumentStatus;
use App\Enums\WorkflowType;
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
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('doc_no', 100)->unique();
            $table->date('date')->index();
            $table->date('due_date')->nullable()->index();
            $table->uuid('owner_id')->index(); // User ID
            $table->uuid('category_sub_id')->index();
            $table->uuid('department_id')->index();
            $table->uuid('ref_doc_id')->index()->nullable();
            $table->string('notes')->nullable();
            $table->string('approval_workflow_type', 20)->default(WorkflowType::INORDER->value)->comment('get from WorkflowType enum');
            $table->string('review_workflow_type', 20)->default(WorkflowType::INORDER->value)->comment('get from WorkflowType enum');
            $table->boolean('is_review_required')->default(false);
            $table->boolean('is_reviewed')->default(false);
            $table->string('acknowledgement_workflow_type', 20)->default(WorkflowType::INORDER->value)->comment('get from WorkflowType enum');
            $table->boolean('is_acknowledgement_required')->default(false);
            $table->boolean('is_acknowledged')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_public')->default(false);
            $table->string('status', 20)->default(DocumentStatus::DRAFT->value)->index();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();

            // FK
            $table->foreign('owner_id')->references('id')->on('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('category_sub_id')->references('id')->on('category_subs')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('department_id')->references('id')->on('departments')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
