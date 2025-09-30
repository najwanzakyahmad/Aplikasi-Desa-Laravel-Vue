<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Development extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'description',
        'person_in_charge',
        'start_date',
        'end_date',
        'amount',
        'status',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%'. $search. '%')
            ->orWhere('person_in_charge', 'like', '%'. $search. '%')
            ->orWhere('description', 'like', '%'. $search. '%');
    }

    public function DevelopmentApplicants()
    {
        return $this->hasMany(DevelopmentApplicant::class);
    }
}
