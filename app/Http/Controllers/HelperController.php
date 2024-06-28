<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)
        ->orderBy('state', 'ASC')
        ->pluck('state', 'id')
        ->toArray();
        return $states;
        
    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)
        ->orderBy('city', 'ASC')
        ->pluck('city', 'id')
        ->toArray();
        return $cities;
    }
}
