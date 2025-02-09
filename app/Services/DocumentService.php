<?php

namespace App\Services;

use App\Data\DMS\DocumentApprovalUserData;
use App\Data\DMS\DocumentCreateData;
use App\Data\DMS\DocumentData;
use App\Data\DMS\DocumentFileCreateData;
use App\Data\DMS\DocumentInformedUserData;
use App\Data\DMS\DocumentUpdateData;
use App\Data\DMS\DocumentViewData;
use App\Enums\DocumentStatus;
use App\Enums\WorkflowType;
use App\Exceptions\CustomException;
use App\Models\DMS\Document;
use App\Models\DMS\DocumentAcknowledge;
use App\Models\DMS\DocumentApproval;
use App\Models\DMS\DocumentFile;
use App\Models\DMS\DocumentInfomedUser;
use App\Models\DMS\DocumentReview;
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
                'file_type' => $file->getClientMimeType(),
            ]);
        }

        return $documentFiles;
    }

    private function isUserAuthorizeView(string $documentId, string $userId): bool
    {
        // get data is_public and owner_id
        $doc = Document::where('id', $documentId)->first(['owner_id', 'is_public']);

        // if $userId is the owner
        if ($doc->owner_id === $userId) return true;

        // check if document is for public
        if ($doc->is_public) return true;

        // check if user_id has to approve document
        if (DocumentApproval::where('user_id', $userId)->whereIn('document_id', $documentId)->exists()) return true;

        // check if user_id has review document
        if (DocumentReview::where('user_id', $userId)->whereIn('document_id', $documentId)->exists()) return true;

        // check if user_id has acknowledge document
        if (DocumentAcknowledge::where('user_id', $userId)->whereIn('document_id', $documentId)->exists()) return true;

        // check if user_id has acknowledge document
        if (DocumentInfomedUser::where('user_id', $userId)->whereIn('document_id', $documentId)->exists()) return true;

        return false;
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
        array $approvalUsers,
        ?array $informedUsers = null,
        bool $isReviewRequired,
        WorkflowType $reviewWorkflowType,
        ?array $reviewUsers = null,
        bool $isAcknowledgementRequired,
        WorkflowType $acknowledgementWorkflowType,
        ?array $acknowledgementUsers = null,
        bool $isLocked,
        bool $isPublic,
        array $files,
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
                owner_id: $ownerId,
                category_sub_id: $categorySubId,
                department_id: $departmentId,
                ref_doc_id: $refDocId,
                notes: $notes,
                approval_workflow_type: $approvalWorkflowType,
                review_workflow_type: $reviewWorkflowType,
                is_review_required: $isReviewRequired,
                acknowledgement_workflow_type: $acknowledgementWorkflowType,
                is_acknowledgement_required: $isAcknowledgementRequired,
                is_locked: $isLocked,
                is_public: $isPublic,
                status: DocumentStatus::DRAFT
            );

            // prepare files
            $files = DocumentFileCreateData::collect($files);

            // prepare approval users
            $approvalUsers = DocumentApprovalUserData::collect(collect($approvalUsers));

            // prepare informed users
            $informedUsers = !empty($informedUsers) ? DocumentInformedUserData::collect(collect($informedUsers)) : null;

            // prepare review users
            $reviewUsers = !empty($reviewUsers) ? DocumentApprovalUserData::collect(collect($reviewUsers)) : null;

            // prepare acknowledgement users
            $acknowledgementUsers = !empty($acknowledgementUsers) ? DocumentApprovalUserData::collect(collect($acknowledgementUsers)) : null;

            // create
            $document = $this->repository->store(data: $data, files: $files, approvalUsers: $approvalUsers, informedUsers: $informedUsers, reviewUsers: $reviewUsers, acknowledgementUsers: $acknowledgementUsers);

            DB::commit();

            return $document;
        } catch (\Exception $e) {
            // rollback
            DB::rollBack();

            // if $attachmentFiles not empty
            if ($files->isNotEmpty()) {
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

    public function view(string $id, string $userId): DocumentViewData
    {
        // check if data exist
        if (!Document::where('id', $id)->exists()) {
            abort(Response::HTTP_NOT_FOUND, 'Document not fond');
        }

        // check if user authothorize to view
        if (!$this->isUserAuthorizeView($id, $userId)) {
            abort(Response::HTTP_FORBIDDEN, 'Document is forbidden');
        }

        return $this->repository->view(id: $id);
    }

    public function update(
        string $id,
        ?DateTime $dueDate = null,
        string $ownerId,
        string $categorySubId,
        string $departmentId,
        ?string $refDocId = null,
        ?string $notes = null,
        WorkflowType $approvalWorkflowType,
        array $approvalUsers,
        ?array $informedUsers = null,
        bool $isReviewRequired,
        WorkflowType $reviewWorkflowType,
        ?array $reviewUsers = null,
        bool $isAcknowledgementRequired,
        WorkflowType $acknowledgementWorkflowType,
        ?array $acknowledgementUsers = null,
        bool $isLocked,
        bool $isPublic,
        ?array $files = null,
        ?array $documentFiles = null,
    ): DocumentData {
        try {
            // get previous file names
            $fileNames = DocumentFile::where('document_id', $id)->pluck('file_name');

            // get only file_name from $documentFiles
            $documentFileNames = collect($documentFiles)->pluck('file_name');

            // get file name that not in the $documentFiles
            $filesToDelete = $fileNames->filter(function ($file) use ($documentFileNames) {
                return !$documentFileNames->contains($file);
            });
            // dd($fileNames, $documentFileNames, $filesToDelete);

            // handle files
            if (!empty($files)) {
                $files = $this->handleFiles($files);
            }

            DB::beginTransaction();

            // prepare data
            $data = new DocumentUpdateData(
                due_date: $dueDate,
                owner_id: $ownerId,
                category_sub_id: $categorySubId,
                department_id: $departmentId,
                ref_doc_id: $refDocId,
                notes: $notes,
                approval_workflow_type: $approvalWorkflowType,
                review_workflow_type: $reviewWorkflowType,
                is_review_required: $isReviewRequired,
                acknowledgement_workflow_type: $acknowledgementWorkflowType,
                is_acknowledgement_required: $isAcknowledgementRequired,
                is_locked: $isLocked,
                is_public: $isPublic,
            );

            // prepare files
            $files = !empty($files) ? DocumentFileCreateData::collect($files) : collect([]);

            // prepare approval users
            $approvalUsers = !empty($approvalUsers) ? DocumentApprovalUserData::collect(collect($approvalUsers)) : collect([]);

            // prepare informed users
            $informedUsers = !empty($informedUsers) ? DocumentInformedUserData::collect(collect($informedUsers)) : collect([]);

            // prepare review users
            $reviewUsers = !empty($reviewUsers) ? DocumentApprovalUserData::collect(collect($reviewUsers)) : collect([]);

            // prepare acknowledgement users
            $acknowledgementUsers = !empty($acknowledgementUsers) ? DocumentApprovalUserData::collect(collect($acknowledgementUsers)) : collect([]);

            // create
            $document = $this->repository->update(id: $id, data: $data, files: $files, filesToDelete: $filesToDelete, approvalUsers: $approvalUsers, informedUsers: $informedUsers, reviewUsers: $reviewUsers, acknowledgementUsers: $acknowledgementUsers);

            DB::commit();

            // delete files if not empty
            if ($filesToDelete->isNotEmpty()) {
                foreach ($filesToDelete as $file) {
                    Storage::delete(config('setting.other.path_to_upload') . '/' . $file);
                }
            }

            return $document;
        } catch (\Exception $e) {
            // rollback
            DB::rollBack();

            dd($files);

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
