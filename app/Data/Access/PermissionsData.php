<?php

namespace App\Data\Access;

use Spatie\LaravelData\Data;

class AssetOSLicenseCreateData extends Data
{
    public function __construct(
        public string $permission,
        public bool $is_allowed,
    ) {}
}
