<?php

namespace App\Repositories\Contracts;

use App\Data\DMS\DocumentCreateData;
use App\Data\DMS\DocumentData;
use App\Data\DMS\DocumentViewData;
use Illuminate\Support\Collection;

interface DocumentRepository
{
    public function store(DocumentCreateData $data, Collection $files, Collection $approvalUsers, ?Collection $reviewUsers = null, ?Collection $acknowledgementUsers = null): DocumentData;
    public function view(string $id): DocumentViewData;
}
