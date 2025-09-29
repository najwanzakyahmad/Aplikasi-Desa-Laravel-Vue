<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class SocialAssistance extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'category',
        'amount',
        'provider',
        'description',
        'is_available',
    ];

    protected $cast = [
        'is_available' => 'boolean'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%'. $search. '%')
            ->orWhere('category', 'like', '%'. $search. '%')
            ->orWhere('amount', 'like', '%'. $search. '%')
            ->orWhere('provider', 'like', '%'. $search. '%')
            ->orWhere('description', 'like', '%'. $search. '%');
    }

    public function socialAssistanceRecipients()
    {
        return $this->hasMany(SocialAssistanceRecipient::class);
    }
}
