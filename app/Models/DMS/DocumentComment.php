<?php

namespace App\Models\DMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentComment extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['created_by', 'updated_by'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (DocumentComment $model) {
            $model->created_by = Auth::id();
        });

        static::updating(function (DocumentComment $model) {
            $model->updated_by = Auth::id();
        });
    }
}
