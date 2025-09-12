<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class HeadOfFamily extends Model
{
    use SoftDeletes;
    use UUID;

    protected $table = 'head_of_families';
    protected $fillable = [
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

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function socialAssistanceRecipients()
    {
        return $this->hasMany(SocialAssistanceRecipient::class);
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}
