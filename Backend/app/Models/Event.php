<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Event extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'description',
        'price',
        'date',
        'time',
        'is_active'
    ];

    protected $cast = [
        'is_active' => 'boolean',
        'date'      => 'date:Y-m-d',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%'. $search. '%')
            ->orWhere('price', 'like', '%'. $search. '%')
            ->orWhere('description', 'like', '%'. $search. '%');
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}
