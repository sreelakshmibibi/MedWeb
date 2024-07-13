<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\TreatmentComboOffer;
use Illuminate\Http\Request;

class ComboOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comboOffers = TreatmentComboOffer::with('treatments')->get();

        return view('settings.combo_offer.index', compact('comboOffers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TreatmentComboOffer $treatmentComboOffer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TreatmentComboOffer $treatmentComboOffer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TreatmentComboOffer $treatmentComboOffer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreatmentComboOffer $treatmentComboOffer)
    {
        //
    }
}
