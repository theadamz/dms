<?php

namespace App\Models\Basic;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ApprovalSetUser extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['created_by', 'updated_by'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ApprovalSetUser $model) {
            $model->created_by = Auth::id();
        });

        static::updating(function (ApprovalSetUser $model) {
            $model->updated_by = Auth::id();
        });
    }

    public function approval_set(): BelongsTo
    {
        return $this->belongsTo(ApprovalSet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
