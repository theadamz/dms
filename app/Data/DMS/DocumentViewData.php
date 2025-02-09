<?php

namespace App\Data\DMS;

use App\Enums\DocumentStatus;
use App\Enums\WorkflowType;
use DateTime;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class DocumentViewData extends Data
{
    public function __construct(
        public string $id,
        public string $doc_no,
        public DateTime $date,
        public ?DateTime $due_date = null,
        public string $owner_id,
        public string $owner_name,
        public string $category_id,
        public string $category_name,
        public string $category_sub_id,
        public string $category_sub_name,
        public string $department_id,
        public string $department_name,
        public ?string $ref_doc_id = null,
        public ?string $ref_doc_no = null,
        public ?string $notes = null,
        #[WithCast(EnumCast::class)]
        public WorkflowType $approval_workflow_type,
        #[DataCollectionOf(DocumentApprovalUserData::class)]
        public DataCollection|Collection $approval_users,
        #[DataCollectionOf(DocumentInformedUserData::class)]
        public DataCollection|Collection $informed_users,
        #[WithCast(EnumCast::class)]
        public WorkflowType $review_workflow_type,
        public bool $is_review_required,
        public bool $is_reviewed,
        #[DataCollectionOf(DocumentReviewUserData::class)]
        public DataCollection|Collection|null $review_users = null,
        #[WithCast(EnumCast::class)]
        public WorkflowType $acknowledgement_workflow_type,
        public bool $is_acknowledgement_required,
        public bool $is_acknowledged,
        #[DataCollectionOf(DocumentAcknowledgeUserData::class)]
        public DataCollection|Collection|null $acknowledge_users = null,
        public bool $is_locked,
        public bool $is_public,
        #[WithCast(EnumCast::class)]
        public DocumentStatus $status,
        #[DataCollectionOf(DocumentFileData::class)]
        public DataCollection|Collection $files,
        public ?string $created_by = null,
        public ?string $updated_by = null,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: "Y-m-d H:i:s")]
        public ?DateTime $created_at = null,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: "Y-m-d H:i:s")]
        public ?DateTime $updated_at = null,
    ) {}
}
