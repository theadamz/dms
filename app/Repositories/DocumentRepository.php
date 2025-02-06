<?php

namespace App\Repositories;

use App\Data\DMS\DocumentCreateData;
use App\Data\DMS\DocumentData;
use App\Models\DMS\Document;
use App\Models\DMS\DocumentAcknowledge;
use App\Models\DMS\DocumentApproval;
use App\Models\DMS\DocumentFile;
use App\Models\DMS\DocumentLogs;
use App\Models\DMS\DocumentReview;
use App\Repositories\Contracts\DocumentRepository as Contract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class DocumentRepository implements Contract
{

    public function store(DocumentCreateData $data, Collection $files, Collection $approvalUsers, ?Collection $reviewUsers = null, ?Collection $acknowledgementUsers = null): DocumentData
    {
        // create new document
        $document = new Document($data->toArray());
        $document->date = $data->date->setTimezone(Session::get('timezone'));
        if ($data->due_date) {
            $document->due_date = $data->due_date->setTimezone(Session::get('timezone'));
        }
        $document->is_reviewed = false;
        $document->is_acknowledged = false;
        $document->save();

        // document files
        foreach ($files as $file) {
            // add document_id to to document file
            $dataDocumentFile = collect($file)->merge(['document_id' => $document->id])->toArray();
            $documentFile = DocumentFile::create($dataDocumentFile);

            // approval users
            foreach ($approvalUsers as $user) {
                // create data document approval
                DocumentApproval::create([
                    'document_file_id' => $documentFile->id,
                    'user_id' => $user->id,
                    'order' => $user->order,
                ]);
            } // ./ approval users

            // if review is required
            if ($data->is_review_required) {
                foreach ($reviewUsers as $user) {
                    DocumentReview::create([
                        'document_file_id' => $documentFile->id,
                        'user_id' => $user->id,
                        'order' => $user->order,
                    ]);
                }
            }

            // if acknowledgement is required
            if ($data->is_acknowledgement_required) {
                foreach ($acknowledgementUsers as $user) {
                    DocumentAcknowledge::create([
                        'document_file_id' => $documentFile->id,
                        'user_id' => $user->id,
                        'order' => $user->order,
                    ]);
                }
            }
        } // ./ document files

        // create log
        DocumentLogs::create([
            'document_id' => $document->id,
            'action' => 'Added document'
        ]);

        return DocumentData::from($document);
    }
}
