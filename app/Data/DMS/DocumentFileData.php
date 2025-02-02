<?php

namespace App\Data\DMS;

use Spatie\LaravelData\Data;

class DocumentFileData extends Data
{
    public function __construct(
        public string $id,
        public string $document_id,
        public string $file_origin_name,
        public string $file_name,
        public int $file_size, // in byte
        public string $file_ext,
        public string $file_mime,
    ) {}
}
