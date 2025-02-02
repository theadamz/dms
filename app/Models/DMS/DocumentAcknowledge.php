<?php

namespace App\Models\DMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentAcknowledge extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['created_by', 'updated_by'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (DocumentAcknowledge $model) {
            $model->created_by = Auth::id();
        });

        static::updating(function (DocumentAcknowledge $model) {
            $model->updated_by = Auth::id();
        });
    }
}
