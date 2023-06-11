<?php

namespace Hutsoliak\HttpLogger\Models;

use Illuminate\Database\Eloquent\Model;

class LogsHttp extends Model
{
    protected string $table = 'logs_http';

    protected array $fillable = [
        'status',
        'user_id',
        'method',
        'url',
        'parent_id',
        'request',
        'response',
        'headers',
        'cookies',
        'time',
        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->user()->id ?? null;
        });
    }

    protected array $casts = [
        'request' => 'json',
        'response' => 'json',
        'headers' => 'json',
        'cookies' => 'json',
    ];
}