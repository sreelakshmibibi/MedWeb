<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

    public function getSessionData()
    {
        // Retrieve session data
        $response = [
            'appId' => Session::get('appId'),
            'patientId' => Session::get('patientId'),
            'patientName' => Session::get('patientName'),
            'loginedUserAdmin' => Auth::user()->is_admin,
        ];

        return response()->json($response);
    }
}
