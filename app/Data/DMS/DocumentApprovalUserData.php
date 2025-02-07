<?php

namespace App\Data\DMS;

use Spatie\LaravelData\Data;

class DocumentApprovalUserData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public int $order,
        public bool $is_approved = false,
        public ?string $remarks = null,
    ) {
        $this->remarks = null;
    }
}
