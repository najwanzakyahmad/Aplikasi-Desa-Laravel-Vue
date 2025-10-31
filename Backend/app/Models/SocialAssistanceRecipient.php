<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialAssistanceRecipient extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $fillable = [
        'social_assistance_id',
        'head_of_family_id',
        'bank',
        'amount',
        'reason',
        'account_number',
        'proof',
        'status'
    ];

    // public function scopeSearch($query, $search)
    // {
    //     return $query->where('bank', 'like', '%'. $search. '%')
    //         ->orWhere('amount', 'like', '%'. $search. '%')
    //         ->orWhere('reason', 'like', '%'. $search. '%')
    //         ->orWhere('account_number', 'like', '%'. $search. '%')
    //         ->orWhere('status', 'like', '%'. $search. '%')
    //         ->orWhere('head_of_family_id', 'like', '%'. $search. '%')
    //         ->orWhere('social_assistance', 'like', '%'. $search. '%');

            // ->orWhereRelation('socialAssistance', 'name', 'like', '%'. $search. '%')
            // ->orWhereRelation('headOfFamily', 'name', 'like', '%'. $search. '%');

    // }

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('socialAssistance', function($query) use ($search){
            $query->where('name', 'like', '%'. $search. '%')
                ->orWhere('provider', 'like', '%'. $search. '%');
        })->orWhere('account_number', 'like', '%'. $search. '%')
            ->orWhere('bank', 'like', '%'. $search. '%')
            ->orWhere('amount', 'like', '%'. $search. '%');
    }

    public function socialAssistance()
    {
        return $this->belongsTo(SocialAssistance::class)->withTrashed();
    }

    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class)->withTrashed();
    }
}
