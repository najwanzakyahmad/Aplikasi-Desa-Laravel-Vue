<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventParticipant extends Model
{
    use SoftDeletes, UUID, HasFactory;

    protected $fillable = [
        'event_id',
        'head_of_family_id',
        'quantity',
        'total_price',
        'payment_status'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('event_id', 'like', '%'. $search. '%')
            ->orWhere('head_of_family', 'like', '%'. $search. '%')
            ->orWhere('quantity', 'like', '%'. $search. '%')

            ->orWhereRelation('event', 'name', 'like', '%' . $search . '%')
            ->orWhereRelation('headOfFamily', 'name', 'like', '%' . $search . '%');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}
