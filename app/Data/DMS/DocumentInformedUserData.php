<?php

namespace App\Data\DMS;

use Spatie\LaravelData\Data;

class DocumentInformedUserData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
    ) {
        //
    }
}
