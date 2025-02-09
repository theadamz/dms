<?php

namespace App\Repositories;

use App\Data\DMS\DocumentAcknowledgeUserData;
use App\Data\DMS\DocumentApprovalUserData;
use App\Data\DMS\DocumentCreateData;
use App\Data\DMS\DocumentData;
use App\Data\DMS\DocumentFileData;
use App\Data\DMS\DocumentInformedUserData;
use App\Data\DMS\DocumentReviewUserData;
use App\Data\DMS\DocumentUpdateData;
use App\Data\DMS\DocumentViewData;
use App\Enums\DocumentStatus;
use App\Enums\WorkflowType;
use App\Models\DMS\Document;
use App\Models\DMS\DocumentAcknowledge;
use App\Models\DMS\DocumentApproval;
use App\Models\DMS\DocumentFile;
use App\Models\DMS\DocumentInfomedUser;
use App\Models\DMS\DocumentLogs;
use App\Models\DMS\DocumentReview;
use App\Repositories\Contracts\DocumentRepository as Contract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class DocumentRepository implements Contract
{

    public function store(DocumentCreateData $data, Collection $files, Collection $approvalUsers, ?Collection $informedUsers = null, ?Collection $reviewUsers = null, ?Collection $acknowledgementUsers = null): DocumentData
    {
        // create new document
        $document = new Document($data->toArray());
        $document->date = $data->date->setTimezone(Session::get('timezone'));
        if (!empty($data->due_date)) {
            $document->due_date = $data->due_date->setTimezone(Session::get('timezone'));
        }
        $document->is_reviewed = false;
        $document->is_acknowledged = false;
        $document->save();

        // document files
        foreach ($files as $file) {
            // add document_id to to document file
            $dataDocumentFile = collect($file)->merge(['document_id' => $document->id])->toArray();
            DocumentFile::create($dataDocumentFile);
        } // ./ document files

        // approval users
        foreach ($approvalUsers as $user) {
            // create data document approval
            DocumentApproval::create([
                'document_id' => $document->id,
                'user_id' => $user->id,
                'order' => $user->order,
            ]);
        }

        // if informed users not empty
        if (!empty($informedUsers)) {
            foreach ($informedUsers as $user) {
                DocumentInfomedUser::create([
                    'document_id' => $document->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        // if review is required
        if ($data->is_review_required) {
            foreach ($reviewUsers as $user) {
                DocumentReview::create([
                    'document_id' => $document->id,
                    'user_id' => $user->id,
                    'order' => $user->order,
                ]);
            }
        }

        // if acknowledgement is required
        if ($data->is_acknowledgement_required) {
            foreach ($acknowledgementUsers as $user) {
                DocumentAcknowledge::create([
                    'document_id' => $document->id,
                    'user_id' => $user->id,
                    'order' => $user->order,
                ]);
            }
        }

        // create log
        DocumentLogs::create([
            'document_id' => $document->id,
            'action' => 'Added document'
        ]);

        return DocumentData::from($document);
    }

    public function view(string $id): DocumentViewData
    {
        // variables
        $approvalUsers = [];
        $informedUsers = null;
        $reviewUsers = null;
        $acknowledgeUsers = null;

        // get data
        $document = Document::where('id', $id)
            ->with([
                'owner:id,name',
                'category_sub:id,name,category_id',
                'category_sub.category:id,name',
                'department:id,name',
                'doc_parent:id,ref_doc_id,doc_no',
                'document_files:id,document_id,file_origin_name,file_name,file_size,file_ext,file_type',
                'document_approvals:document_id,user_id,order,is_approved,remarks',
                'document_approvals.user:id,name,email',
                'document_informed_users:document_id,user_id',
                'document_informed_users.user:id,name,email',
                'document_reviews:document_id,user_id,order,is_reviewed,remarks',
                'document_reviews.user:id,name,email',
                'document_acknowledges:document_id,user_id,order,is_acknowledged,remarks',
                'document_acknowledges.user:id,name,email',
            ])->first();

        // refactor document_approvals to collect
        $approvalUsers = $document->document_approvals->map(function ($item) {
            return [
                'id' => $item->user_id,
                'name' => $item->user->name,
                'email' => $item->user->email,
                'order' => $item->order,
                'is_approved' => $item->is_approved,
                'remarks' => $item->remarks,
            ];
        });

        // replace data
        $approvalUsers = DocumentApprovalUserData::collect($approvalUsers);

        // if is_review_required = true then refactor
        if (!empty($document->document_informed_users)) {
            $informedUsers = $document->document_informed_users->map(function ($item) {
                return [
                    'id' => $item->user_id,
                    'name' => $item->user->name,
                    'email' => $item->user->email,
                ];
            });

            // replace data
            $informedUsers = DocumentInformedUserData::collect($informedUsers);
        }

        // if is_review_required = true then refactor
        if ($document->is_review_required) {
            $reviewUsers = $document->document_reviews->map(function ($item) {
                return [
                    'id' => $item->user_id,
                    'name' => $item->user->name,
                    'email' => $item->user->email,
                    'order' => $item->order,
                    'is_reviewed' => $item->is_reviewed,
                    'remarks' => $item->remarks,
                ];
            });

            // replace data
            $reviewUsers = DocumentReviewUserData::collect($reviewUsers);
        }

        // if is_acknowledgement_required = true then refactor
        if ($document->is_acknowledgement_required) {
            $acknowledgeUsers = $document->document_acknowledges->map(function ($item) {
                return [
                    'id' => $item->user_id,
                    'name' => $item->user->name,
                    'email' => $item->user->email,
                    'order' => $item->order,
                    'is_acknowledged' => $item->is_acknowledged,
                    'remarks' => $item->remarks,
                ];
            });

            // replace data
            $acknowledgeUsers = DocumentAcknowledgeUserData::collect($acknowledgeUsers);
        }

        return new DocumentViewData(
            id: $document->id,
            doc_no: $document->doc_no,
            date: $document->date,
            due_date: $document->due_date,
            owner_id: $document->owner_id,
            owner_name: $document->owner->name,
            category_id: $document->category_sub->category_id,
            category_name: $document->category_sub->category->name,
            category_sub_id: $document->category_sub_id,
            category_sub_name: $document->category_sub->name,
            department_id: $document->department_id,
            department_name: $document->department->name,
            ref_doc_id: $document->ref_doc_id,
            ref_doc_no: $document->doc_parent->doc_no ?? null,
            notes: $document->notes,
            approval_workflow_type: WorkflowType::tryFrom($document->approval_workflow_type),
            approval_users: $approvalUsers,
            informed_users: $informedUsers,
            review_workflow_type: WorkflowType::tryFrom($document->review_workflow_type),
            is_review_required: $document->is_review_required,
            is_reviewed: $document->is_reviewed,
            review_users: $reviewUsers,
            acknowledgement_workflow_type: WorkflowType::tryFrom($document->acknowledgement_workflow_type),
            is_acknowledgement_required: $document->is_acknowledgement_required,
            is_acknowledged: $document->is_acknowledged,
            acknowledge_users: $acknowledgeUsers,
            is_locked: $document->is_locked,
            is_public: $document->is_public,
            status: DocumentStatus::tryFrom($document->status),
            files: DocumentFileData::collect($document->document_files),
        );
    }

    public function update(string $id, DocumentUpdateData $data, ?Collection $files = null, Collection $filesToDelete, Collection $approvalUsers, Collection $informedUsers, Collection $reviewUsers, Collection $acknowledgementUsers): DocumentData
    {
        // get previous data
        $preDoc = Document::where('id', $id)
            ->with([
                'document_approvals:document_id,user_id',
                'document_informed_users:document_id,user_id',
                'document_reviews:document_id,user_id',
                'document_acknowledges:document_id,user_id',
            ])->first();

        // create new document
        $document = Document::find($id);
        $document->fill($data->toArray());
        if (!empty($data->due_date)) {
            $document->due_date = $data->due_date->setTimezone(Session::get('timezone'));
        }
        $document->save();

        // files ============================================================

        // if $filesToDelete is not empty then delete document files data
        if ($filesToDelete->isNotEmpty()) {
            DocumentFile::where('document_id', $document->id)->whereIn('file_name', $filesToDelete)->delete();
        }

        // if files not empty
        if ($files->isNotEmpty()) {
            foreach ($files as $file) {
                // add document_id to to document file
                $dataDocumentFile = collect($file)->merge(['document_id' => $document->id])->toArray();
                DocumentFile::create($dataDocumentFile);
            } // ./ document files
        }

        // ./ files ============================================================

        // approval users ============================================================

        // remove previous users
        DocumentInfomedUser::where('document_id', $document->id)->delete();

        // if approval users not empty
        if ($informedUsers->isNotEmpty()) {
            // remove users
            DocumentInfomedUser::whereIn('document_id', $document->id)->delete();

            foreach ($informedUsers as $user) {
                // create data
                DocumentInfomedUser::create([
                    'document_id' => $document->id,
                    'user_id' => $user->id,
                    'order' => $user->order,
                ]);
            } // ./ approval users
        }

        // approval users ============================================================

        // if approval users not empty
        if ($approvalUsers->isNotEmpty()) {
            foreach ($approvalUsers as $user) {
                // if data exist then update
                if (DocumentApproval::where('document_id', $document->id)->where('user_id', $user->id)->exists()) {
                    // update data
                    DocumentApproval::where('document_id', $document->id)->where('user_id', $user->id)->update([
                        'order' => $user->order,
                    ]);
                } else {
                    // create data
                    DocumentApproval::create([
                        'document_id' => $document->id,
                        'user_id' => $user->id,
                        'order' => $user->order,
                    ]);
                }
            } // ./ approval users
        }

        // ./ approval users ============================================================

        // review users ============================================================

        // if previous review required is true then the new one is false then delete all review users
        if ($preDoc->is_review_required && $data->is_review_required === false) {
            DocumentReview::where('document_id', $document->id)->delete();
        }

        // if review is required and review users not empty
        if ($data->is_review_required && $reviewUsers->isNotEmpty()) {
            foreach ($reviewUsers as $user) {
                // if data exist then update
                if (DocumentReview::where('document_id', $document->id)->where('user_id', $user->id)->exists()) {
                    // update data
                    DocumentReview::where('document_id', $document->id)->where('user_id', $user->id)->update([
                        'order' => $user->order,
                    ]);
                } else {
                    // create data
                    DocumentReview::create([
                        'document_id' => $document->id,
                        'user_id' => $user->id,
                        'order' => $user->order,
                    ]);
                }
            }
        }

        // ./ review users ============================================================

        // acknowledgement users ============================================================

        // if previous acknowledgement required is true then the new one is false then delete all acknowledgement users
        if ($preDoc->is_acknowledgement_required && $data->is_acknowledgement_required === false) {
            DocumentAcknowledge::where('document_id', $document->id)->delete();
        }

        // if acknowledgement is required and acknowledge users not empty
        if ($data->is_acknowledgement_required && $acknowledgementUsers->isNotEmpty()) {
            foreach ($acknowledgementUsers as $user) {
                // if data exist then update
                if (DocumentAcknowledge::where('document_id', $document->id)->where('user_id', $user->id)->exists()) {
                    // update data
                    DocumentAcknowledge::where('document_id', $document->id)->where('user_id', $user->id)->update([
                        'order' => $user->order,
                    ]);
                } else {
                    // create data
                    DocumentAcknowledge::create([
                        'document_id' => $document->id,
                        'user_id' => $user->id,
                        'order' => $user->order,
                    ]);
                }
            }
        }

        // ./ acknowledgement users ============================================================

        // create log
        DocumentLogs::create([
            'document_id' => $document->id,
            'action' => 'Updated document'
        ]);

        return DocumentData::from($document);
    }
}
