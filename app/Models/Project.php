<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Project extends Model
{
    protected $fillable = [
        'bid_id',
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

    public function winningBid(): ?Bid
    {
        return $this->bids()
            ->orderBy('bid_amount')
            ->first();
    }

    public function awardedBid()
    {
        return $this->belongsTo(Bid::class, 'bid_id');
    }
    public function getVariancePercentageAttribute(): ?float
    {
        if (!$this->awardedBid || !$this->amount) return null;

        return (($this->amount - $this->awardedBid->bid_amount) 
            / $this->amount) * 100;
    }

    public function getTotalResponsiveBiddersAttribute(): ?int
    {
        return $this->bids()->count();
    }
}
