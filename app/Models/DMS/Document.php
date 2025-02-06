<?php

namespace App\Models\DMS;

use App\Models\Basic\CategorySub;
use App\Models\Config\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['created_by', 'updated_by'];
    protected $casts = [
        'date' => 'date:Y-m-d',
        'due_date' => 'date:Y-m-d',
        'is_locked' => 'boolean',
        'req_review' => 'boolean',
        'is_reviewed' => 'boolean',
        'req_acknowledgement' => 'boolean',
        'is_acknowledged' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Document $model) {
            $model->created_by = Auth::id();
        });

        static::updating(function (Document $model) {
            $model->updated_by = Auth::id();
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function category_sub(): BelongsTo
    {
        return $this->belongsTo(CategorySub::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function doc_parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'ref_doc_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(DocumentFile::class);
    }

    public function approval_users(): HasMany
    {
        return $this->hasMany(DocumentApproval::class);
    }

    public function review_users(): HasManyThrough
    {
        return $this->hasManyThrough(DocumentReview::class, DocumentFile::class);
    }

    public function acknowledge_users(): HasManyThrough
    {
        return $this->hasManyThrough(DocumentReview::class, DocumentAcknowledge::class);
    }
}
