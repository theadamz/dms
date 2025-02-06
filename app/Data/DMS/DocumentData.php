<?php

namespace App\Data\DMS;

use App\Enums\DocumentStatus;
use App\Enums\WorkflowType;
use DateTime;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class DocumentData extends Data
{
    public function __construct(
        public string $id,
        public string $doc_no,
        public DateTime $date,
        public ?DateTime $due_date = null,
        public string $owner_id,
        public string $category_sub_id,
        public string $department_id,
        public ?string $ref_doc_id = null,
        public ?string $notes = null,
        #[WithCast(EnumCast::class)]
        public WorkflowType $approval_workflow_type,
        #[WithCast(EnumCast::class)]
        public WorkflowType $review_workflow_type,
        public bool $is_review_required,
        public bool $is_reviewed,
        #[WithCast(EnumCast::class)]
        public WorkflowType $acknowledgement_workflow_type,
        public bool $is_acknowledgement_required,
        public bool $is_acknowledged,
        public bool $is_locked,
        public bool $is_public,
        #[WithCast(EnumCast::class)]
        public DocumentStatus $status,
        public ?string $created_by = null,
        public ?string $updated_by = null,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: "Y-m-d H:i:s")]
        public ?DateTime $created_at = null,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: "Y-m-d H:i:s")]
        public ?DateTime $updated_at = null,
    ) {}
}
