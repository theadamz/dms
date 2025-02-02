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

class DocumentCreateData extends Data
{
    public function __construct(
        public string $doc_no,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: "Y-m-d")]
        public DateTime $date,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: "Y-m-d")]
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
        #[WithCast(EnumCast::class)]
        public WorkflowType $acknowledgement_workflow_type,
        public bool $req_acknowledgement,
        #[WithCast(EnumCast::class)]
        public DocumentStatus $status,
    ) {}
}
