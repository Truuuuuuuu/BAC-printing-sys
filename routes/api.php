<?php

use App\Models\Barangay;

Route::get('/barangays/{cityId}', function ($cityId) {
    return Barangay::where('city_id', $cityId)
        ->orderBy('name')
        ->get(['id', 'name']);
});