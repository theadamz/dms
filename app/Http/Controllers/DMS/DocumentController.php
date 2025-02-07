<?php

namespace App\Http\Controllers\DMS;

use App\Enums\DocumentStatus;
use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Models\Basic\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class DocumentController extends Controller
{
    public function index(): View
    {
        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/dms/document/index.js'
        ]);

        // category
        $categories = Category::where('is_active', true)->get(['id', 'name']);

        return view('dms.document.index')->with(compact('categories'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $queries = DB::table("document_files", "df")
            ->leftJoin("documents AS d", "d.id", "=", "df.document_id")
            ->leftJoin("category_subs AS cs", "cs.id", "=", "d.category_sub_id")
            ->leftJoin("categories AS c", "c.id", "=", "cs.category_id")
            ->leftJoin("users AS u", "u.id", "=", "d.owner_id")
            ->selectRaw("df.id, df.document_id, d.doc_no, df.file_origin_name AS file_name, df.file_mime AS file_type, df.file_size, d.updated_at AS publish_at,
                        d.is_review_required, d.is_reviewed, d.is_acknowledgement_required, d.is_acknowledged,
                        c.name AS category_name, cs.name AS category_sub_name, u.name AS owner_name")
            // ->where("d.is_public", true)->where("d.is_locked", false)
            // ->where("d.status", DocumentStatus::APPROVED)
            ->when($request->filled('category'), function ($query) use ($request) {
                return $query->where('cs.category_id', $request->get('category'));
            })
            ->when($request->filled('category_sub'), function ($query) use ($request) {
                return $query->where('d.category_sub_id', $request->get('category_sub'));
            });

        return DataTables::query($queries)->toJson();
    }
}
