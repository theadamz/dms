<?php

namespace App\Data\DMS;

use Spatie\LaravelData\Data;

class DocumentApprovalCreateData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public int $order,
    ) {}
}
