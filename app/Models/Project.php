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

    public function getContractAmountInWordsAttribute(): string
    {
        if (!$this->amount) return '';

        
        $amount = number_format($this->awardedBid->bid_amount, 2, '.', '');

        [$whole, $cents] = explode('.', $amount);

        $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);

        $words = collect(explode(' ', $formatter->format((int) $whole)))
            ->map(fn($w) => ucfirst($w))
            ->join(' ');

        $centsText = (int) $cents > 0 ? " and {$cents}/100" : '';


        return "{$words} Pesos{$centsText}";
    }
}
