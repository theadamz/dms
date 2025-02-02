<?php

namespace App\Repositories\Contracts;

use App\Data\DMS\DocumentCreateData;
use App\Data\DMS\DocumentData;
use Illuminate\Support\Collection;

interface DocumentRepository
{
    public function store(DocumentCreateData $data, Collection $files, Collection $approvalUsers): DocumentData;
}
