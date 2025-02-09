<?php

namespace App\Models\DMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class DocumentInfomedUser extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['created_by'];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (DocumentInfomedUser $model) {
            $model->created_by = Auth::id();
            $model->created_at = now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
