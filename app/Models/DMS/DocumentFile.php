<?php

namespace App\Models\DMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;

class DocumentFile extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['created_by'];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (DocumentFile $model) {
            $model->created_by = Auth::id();
            $model->created_at = now();
        });
    }

    public function document_approvals(): HasManyThrough
    {
        return $this->HasManyThrough(DocumentApproval::class, Document::class);
    }

    public function document_reviews(): HasManyThrough
    {
        return $this->HasManyThrough(DocumentReview::class, Document::class);
    }

    public function document_acknowledges(): HasManyThrough
    {
        return $this->HasManyThrough(DocumentAcknowledge::class, Document::class);
    }
}
