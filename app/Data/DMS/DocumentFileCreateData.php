<?php

namespace App\Data\DMS;

use Spatie\LaravelData\Data;

class DocumentFileCreateData extends Data
{
    public function __construct(
        public string $file_origin_name,
        public string $file_name,
        public int $file_size,
        public string $file_ext,
        public string $file_type,
    ) {}
}
