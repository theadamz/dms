<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class IpInfoData extends Data
{
    public function __construct(
        public string $ip,
        public string $city,
        public string $region,
        public string $country,
        public string $loc,
        public string $org,
        public string $timezone,
    ) {}
}
