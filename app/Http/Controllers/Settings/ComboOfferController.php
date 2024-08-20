<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ComboOfferRequest;
use App\Models\TreatmentComboOffer;
use App\Models\TreatmentType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class ComboOfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings combo_offers', ['only' => ['index', 'store', 'update', 'edit', 'destroy']]);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $comboOffers = TreatmentComboOffer::with('treatments')->get();

            return DataTables::of($comboOffers)
                ->addIndexColumn()
                ->addColumn('treatments', function ($offer) {
                    return $offer->treatments->pluck('treat_name')->implode(', ');
                })
                ->addColumn('total_treatment_amount', function ($offer) {
                    return $offer->treatments->sum('treat_cost');
                })
                ->addColumn('offer_amount', function ($offer) {
                    return number_format((float) $offer->offer_amount, 2, '.', ',');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'Y') {
                        $btn1 = '<span class="text-success" title="active"><i class="fa-solid fa-circle-check"></i></span>';
                    } else {
                        $btn1 = '<span class="text-danger" title="inactive"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }

                    return $btn1;
                })
                ->addColumn('action', function ($offer) {
                    $editBtn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="'.$offer->id.'"
                    data-bs-target="#modal-edit"><i class="fa fa-pencil"></i></button>';
                    $deleteBtn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="'.$offer->id.'" title="delete">
                    <i class="fa fa-trash"></i></button>';

                    return $editBtn.$deleteBtn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        $treatments = TreatmentType::where('status', 'Y')
            ->orderBy('treat_name', 'asc')
            ->get();

        return view('settings.combo_offer.index', compact('treatments'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ComboOfferRequest $request)
    {
        try {
            // Create a new combo offer instance
            $comboOffer = new TreatmentComboOffer();
            $comboOffer->offer_amount = $request->input('offer_amount');
            $comboOffer->offer_from = $request->input('offer_from');
            $comboOffer->offer_to = $request->input('offer_to');
            $comboOffer->status = $request->input('status');
            $comboOffer->created_by = auth()->user()->id;
            $comboOffer->updated_by = auth()->user()->id;

            // Save the combo offer
            $comboOffer->save();

            // Attach selected treatments to the combo offer
            $comboOffer->treatments()->attach($request->input('treatments'));

            return redirect()->back()->with('success', 'Combo offer created successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create combo offer: '.$e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Find the combo offer by ID
        $comboOffer = TreatmentComboOffer::with('treatments')->findOrFail($id);
        $treatments = TreatmentType::where('status', 'Y')
            ->orderBy('treat_name', 'asc')
            ->get();

        // Prepare data to return
        $response = [
            'id' => $comboOffer->id,
            'offer_amount' => $comboOffer->offer_amount,
            'offer_from' => $comboOffer->offer_from,
            'offer_to' => $comboOffer->offer_to,
            'status' => $comboOffer->status,
            'comboOffer_treatments' => $comboOffer->treatments->pluck('id')->toArray(),
            'treatments' => $treatments,
        ];

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComboOfferRequest $request, $id)
    {
        try {
            $comboOffer = TreatmentComboOffer::findOrFail($id);

            // Update combo offer fields based on form data
            $comboOffer->offer_amount = $request->offer_amount;
            $comboOffer->offer_from = $request->offer_from;
            $comboOffer->offer_to = $request->offer_to;
            $comboOffer->status = $request->status;
            $comboOffer->updated_by = auth()->user()->id;
            $comboOffer->save();

            // Sync the treatments with the combo offer
            $comboOffer->treatments()->sync($request->treatments);

            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => 'Combo offer updated successfully.']);
            }

            // Redirect back with success message for non-AJAX request
            return redirect()->back()->with('success', 'Combo offer updated successfully.');

        } catch (\Exception $e) {
            // Handle any unexpected errors
            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to create combo offer: '.$e->getMessage()], 422);
            }

            // Redirect back with error message for non-AJAX request
            return redirect()->back()->with('error', 'Failed to update combo offer. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the combo offer by ID
            $comboOffer = TreatmentComboOffer::findOrFail($id);

            // Soft delete the combo offer
            $comboOffer->delete();

            // Return a success response
            return response()->json(['message' => 'Combo offer deleted successfully.'], 200);
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json(['message' => 'Failed to delete combo offer: '.$e->getMessage()], 500);
        }
    }
}
