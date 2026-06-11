<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class CityMunicipality extends Model
{
    protected $table = 'municipalities_cities';
    protected $fillable = ['name'];

    public function barangays(): HasMany
    {
        return $this->hasMany(Barangay::class, 'city_id');
    }

    public function index()
    {
        return view('bidder.index', [
            'cities' => CityMunicipality::orderBy('name')->get(),
        ]);
    }
}
