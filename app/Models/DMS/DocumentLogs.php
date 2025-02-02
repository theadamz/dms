<?php

namespace App\Models\DMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentLogs extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['document_id', 'user_id', 'action', 'created_at'];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (DocumentLogs $model) {
            $model->user_id = Auth::id();
            $model->created_at = now();
        });
    }
}
