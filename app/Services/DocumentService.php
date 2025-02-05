<?php

namespace App\Services;

use App\Data\DMS\DocumentApprovalCreateData;
use App\Data\DMS\DocumentCreateData;
use App\Data\DMS\DocumentData;
use App\Data\DMS\DocumentFileCreateData;
use App\Enums\DocumentStatus;
use App\Enums\WorkflowType;
use App\Exceptions\CustomException;
use App\Models\DMS\DocumentSequence;
use App\Repositories\Contracts\DocumentRepository;
use DateTime;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentService
{
    protected DocumentRepository $repository;

    public function __construct(DocumentRepository $repository)
    {
        $this->repository = $repository;
    }

    private function generateTicketNo(DateTime $docDate): string
    {
        // variables
        $newTicketNo = "";
        $year = Date::parse($docDate)->format('Y');
        $seqLength = config('setting.sequence.length');
        $seqYear = Date::parse($docDate)->format(config('setting.sequence.year_format'));
        $seqMonth = Date::parse($docDate)->format(config('setting.sequence.month_format'));

        $sequence = DocumentSequence::where([
            ['year', '=', $year],
        ])->first();

        if (empty($sequence)) {
            $sequence = DocumentSequence::create([
                'year' => $year,
                'next_no' => 1
            ]);
        }

        $newTicketNo = $seqYear . $seqMonth . str($sequence->next_no)->padLeft($seqLength, '0');

        // update next_no
        $sequence->increment('next_no');

        return $newTicketNo;
    }

    private function handleFiles(array $files): Collection
    {
        // variables
        $documentFiles = collect();

        // loop attachments
        foreach ($files as $file) {
            // if file invalid
            if (!$file->isValid()) continue;

            // save file with hash filename
            $file->storeAs(config('setting.other.path_to_upload'), $file->hashName());

            // push data
            $documentFiles->push([
                'file_origin_name' => $file->getClientOriginalName(),
                'file_name' => $file->hashName(),
                'file_size' => $file->getSize(),
                'file_ext' => $file->extension(),
                'file_mime' => $file->getClientMimeType(),
            ]);
        }

        return $documentFiles;
    }

    public function store(
        DateTime $docDate,
        ?DateTime $dueDate = null,
        string $ownerId,
        string $categorySubId,
        string $departmentId,
        ?string $refDocId = null,
        ?string $notes = null,
        WorkflowType $approvalWorkflowType,
        WorkflowType $reviewWorkflowType,
        bool $isReviewRequired,
        WorkflowType $acknowledgementWorkflowType,
        bool $isAcknowledgementRequired,
        bool $isLocked,
        bool $isPublic,
        array $files,
        array $approvalUsers,
        ?array $reviewUsers = null,
        ?array $acknowledgementUsers = null,
    ): DocumentData {
        try {
            // handle files
            $files = $this->handleFiles($files);

            DB::beginTransaction();

            // prepare data
            $data = new DocumentCreateData(
                doc_no: $this->generateTicketNo($docDate),
                date: $docDate,
                due_date: $dueDate,
                category_sub_id: $categorySubId,
                owner_id: $ownerId,
                notes: $notes,
                approval_workflow_type: $approvalWorkflowType,
                is_locked: $isLocked,
                review_workflow_type: $reviewWorkflowType,
                req_review: $reqReview,
                acknowledgement_workflow_type: $acknowledgementWorkflowType,
                req_acknowledgement: $reqAcknowledgement,
                status: DocumentStatus::DRAFT
            );

            // prepare files
            $files = DocumentFileCreateData::collect($files);

            // prepare approval users
            $approvalUsers = DocumentApprovalCreateData::collect(collect($approvalUsers));

            // create
            $document = $this->repository->store(data: $data, files: $files, approvalUsers: $approvalUsers);

            DB::commit();

            return $document;
        } catch (\Exception $e) {
            // rollback
            DB::rollBack();

            // if $attachmentFiles not empty
            if (!empty($files)) {
                foreach ($files->toArray() as $file) {
                    Storage::delete(config('setting.other.path_to_upload') . '/' . $file['file_name']);
                }
            }

            // Custom exception
            if ($e instanceof CustomException) throw new HttpResponseException($e->getResponse());

            // other exception
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }
}
