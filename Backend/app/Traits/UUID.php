<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    /**
     * Boot function from Laravel.
     */
    protected static function bootUUID()
    {
        parent::boot();

        static::creating(function ($model) {
            if($model->getKey() === null) {
                $model->setAttribute($model->getKeyName(), Str::uuid()->toString());
            }
        });
    }

    /**
     * Disable auto-incrementing as we are using UUIDs.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set the key type to string for UUIDs.
     *
     * @var string
     */
    protected $keyType = 'string';
}