<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DevelopmentApplicant extends Model
{
    use SoftDeletes, UUID, HasFactory;

    protected $fillable = [
        'development_id',
        'user_id',
        'status'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('development_id', 'like', '%'. $search. '%')
            ->orWhere('user_id', 'like', '%'. $search. '%')
            ->orWhere('status', 'like', '%'. $search. '%');
    }

    public function development()
    {
        return $this->belongsTo(Development::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
