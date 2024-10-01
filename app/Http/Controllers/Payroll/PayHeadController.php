<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayHeadRequest;
use App\Models\PayHead;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PayHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        if ($request->ajax()) {

            $payHeads = PayHead::orderBy('head_type', 'asc')->get();

            return DataTables::of($payHeads)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    switch ($row->type) {
                        case 'E':
                            return PayHead::E_WORDS;
                        case 'SD':
                            return PayHead::SD_WORDS;
                        default:
                            return PayHead::SA_WORDS; // Default case if none match
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'Y') {
                        $btn1 = '<span class="text-success" title="active"><i class="fa-solid fa-circle-check"></i></span>';
                    } else {
                        $btn1 = '<span class="text-danger" title="inactive"><i class="fa-solid fa-circle-xmark"></i></span>';
                    }
                    return $btn1;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1" title="edit" data-bs-toggle="modal" data-id="' . $row->id . '"
                        data-bs-target="#modal-payhead-edit" ><i class="fa fa-pencil"></i></button>';
                        // $btn .= '<button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        // <i class="fa fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // Return the view with menu items
        return view('payroll.payHead.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PayHeadRequest $request)
    {
        try {
            // Create a new department instance
            $payHead = new PayHead();
            // $department->department = $request->input('department');
            // Capitalize each word in the department name before assigning it
            $payHead->head_type = ucwords(strtolower($request->input('head_type')));
            $payHead->type = $request->input('type');
            $payHead->status = $request->input('status');
            
            // Save the department
            $i = $payHead->save();
            if ($i) {
                if ($request->ajax()) {
                    return response()->json(['success' => 'PayHead created successfully.']);
                }
                return redirect()->back()->with('success', 'PayHead created successfully');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'PayHead not created.'. $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Failed to create Pay head: ' . $e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payHead = PayHead::find($id);
        if (!$payHead) {
            abort(404);
        }

        return $payHead;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $payHead = PayHead::findOrFail($request->edit_payhead_id);

            // Update department fields based on form data
            $payHead->head_type = $request->edit_head_type;
            $payHead->type = $request->edit_type;
            $payHead->status = $request->edit_status;

            // Save the updated department
            $payHead->save();

            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => 'Pay head updated successfully.']);
            }

            // Redirect back with success message for non-AJAX request
            return redirect()->back()->with('success', 'Pay head updated successfully.');
        } catch (\Exception $e) {
            // Handle any unexpected errors
            // Return JSON response for AJAX request
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update Pay head. Please try again.'.$e->getMessage()], 500);
            }

            // Redirect back with error message for non-AJAX request
            return redirect()->back()->with('error', 'Failed to update Pay Head. Please try again.');
        }
    }


}
