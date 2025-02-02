<?php

namespace App\Models\Basic;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class ApprovalSet extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['created_by', 'updated_by'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ApprovalSet $model) {
            $model->created_by = Auth::id();
        });

        static::updating(function (ApprovalSet $model) {
            $model->updated_by = Auth::id();
        });
    }

    public function approval_set_users(): HasMany
    {
        return $this->hasMany(ApprovalSetUser::class);
    }

    public function created_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
