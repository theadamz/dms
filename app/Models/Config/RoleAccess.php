<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RoleAccess extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['created_by', 'updated_by'];

    protected $casts = [
        'is_allowed' => 'bool',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (RoleAccess $model) {
            $model->created_by = Auth::id();
        });

        static::updating(function (RoleAccess $model) {
            $model->updated_by = Auth::id();
        });
    }
}
