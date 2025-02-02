<?php

namespace App\Models\DMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
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
}
