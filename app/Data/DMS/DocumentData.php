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
        public string $category_sub_id,
        public string $owner_id,
        public ?string $notes = null,
        #[WithCast(EnumCast::class)]
        public WorkflowType $approval_workflow_type,
        public bool $is_locked,
        #[WithCast(EnumCast::class)]
        public WorkflowType $review_workflow_type,
        public bool $req_review,
        public bool $is_reviewed,
        #[WithCast(EnumCast::class)]
        public WorkflowType $acknowledgement_workflow_type,
        public bool $req_acknowledgement,
        public bool $is_acknowledged,
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
