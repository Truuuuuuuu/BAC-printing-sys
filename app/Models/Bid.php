<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    protected $fillable = [
        'project_id',
        'company_name',
        'proprietor',
        'bid_amount',
        'address',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }


   public function scopeSearch($query, $search)
    {   
        if ($search) {
            $query->whereHas('project', function ($q) use ($search) {
                $q->where('project_title', 'like', '%' . $search . '%');
            })
            ->orWhere('company_name', 'like', '%' . $search . '%')
            ->orWhere('proprietor', 'like', '%' . $search . '%');
        }

        return $query;
    }

}
