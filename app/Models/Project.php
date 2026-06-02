<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
