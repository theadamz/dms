<?php

namespace App\Data\DMS;

use Spatie\LaravelData\Data;

class DocumentReviewUserData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public int $order,
        public bool $is_reviewed = false,
        public ?string $remarks = null,
    ) {
        $this->remarks = null;
    }
}
