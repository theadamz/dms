<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignInHistory extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ip',
        'os',
        'platform',
        'browser',
        'country',
        'city',
        'user_id',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (SignInHistory $model) {
            $model->created_at = now();
        });
    }
}
