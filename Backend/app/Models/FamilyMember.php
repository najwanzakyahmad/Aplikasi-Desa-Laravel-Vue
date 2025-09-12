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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}
