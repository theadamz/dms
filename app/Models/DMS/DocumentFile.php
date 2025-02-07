<?php

namespace App\Models\DMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function document_approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class);
    }

    public function document_reviews(): HasMany
    {
        return $this->hasMany(DocumentReview::class);
    }

    public function document_acknowledges(): HasMany
    {
        return $this->hasMany(DocumentAcknowledge::class);
    }
}
