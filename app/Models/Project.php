<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Project extends Model
{
    protected $fillable = [
        'project_title',
        'amount',
        'bidding_date',
        'status',
    ];

    protected $casts = [
        'bidding_date' => 'date',
    ];

    public function isAwarded()
    {
        return $this->status === 'awarded';
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('project_title', 'like', '%' . $search . '%');
        }

        return $query;
    }

     public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

}
