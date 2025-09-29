<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class FamilyMember extends Model
{
    use SoftDeletes;
    use UUID;

    protected $fillable = [
        'head_of_family_id',
        'user_id',
        'profile_picture',
        'identity_number',
        'gender',
        'birth_date',
        'phone_number',
        'occupation',
        'marital_status',
        'relatio'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('head_of_family_id', 'like', '%'. $search. '%')
                ->orWhere('user_id', 'like', '%'. $search. '%')
                ->orWhere('identity_number', 'like', '%'. $search. '%')
                ->orWhere('phone_number', 'like', '%'. $search. '%')
                ->orWhere('occupation', 'like', '%'. $search. '%')
                // Search related user's name
                ->orWhereRelation('user', 'name', 'like', '%'. $search. '%')
                // Search related user's email
                ->orWhereRelation('user', 'email', 'like', '%'. $search. '%');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}
