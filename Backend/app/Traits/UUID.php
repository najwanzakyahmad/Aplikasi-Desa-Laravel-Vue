<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    /**
     * Dipanggil Eloquent saat model yang menggunakan trait di-instantiate.
     * Set default supaya PK bukan auto-increment dan tipe key string (UUID).
     */
    protected function initializeUUID(): void
    {
        // Cara 1: set properti
        $this->incrementing = false;
        $this->keyType = 'string';

        // Cara 2 (opsional): gunakan setter
        // $this->setIncrementing(false);
        // $this->setKeyType('string');
    }

    /**
     * Dipanggil otomatis oleh Eloquent untuk trait.
     * Generate UUID saat creating jika belum ada key.
     */
    protected static function bootUUID(): void
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->setAttribute($model->getKeyName(), (string) Str::uuid());
            }
        });
    }
}
