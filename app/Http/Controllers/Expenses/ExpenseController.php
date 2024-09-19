<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Expense\ExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Yajra\DataTables\DataTables as DataTables;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:expense', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = ExpenseCategory::where('status', 'Y')
            ->orderBy('category', 'asc')
            ->get();

        if ($request->ajax()) {

            $expenses = Expense::select('expenses.*', 'expense_categories.category as category_name') // Select fields from both tables
                ->join('expense_categories', 'expenses.category', '=', 'expense_categories.id') // Join the expense_categories table
                ->where('expense_categories.status', 'Y') // Filter by status
                ->orderBy('billdate', 'asc')
                ->get();

            return DataTables::of($expenses)
                ->addIndexColumn()
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
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs me-2" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    if (!empty($row->billfile)) {
                        $btn .= '<button type="button" data-id="' . $row->id . '" title="download bills"
                                class="waves-effect waves-light btn btn-circle btn-secondary btn-xs downloadBills"
                                ><i class="fa-solid fa-download"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // Return the view with menu items
        return view('expense.expense.index', compact('categories'));
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
    public function store(ExpenseRequest $request)
    {
        try {
            $data = $request->validated();

            // Add the created_by field
            $data['created_by'] = auth()->id(); // Get the ID of the authenticated user

            // Handle multiple file uploads if they exist
            if ($request->hasFile('billfile')) {
                $filePaths = [];
                foreach ($request->file('billfile') as $file) {
                    $filePaths[] = $file->store('expenses', 'public'); // Store in the 'expenses' directory
                }
                $data['billfile'] = json_encode($filePaths); // Save paths as JSON or handle accordingly
            }

            // Create the expense record
            $expense = Expense::create($data);
            return response()->json(['success' => 'Expense added successfully!', 'expense' => $expense], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to store expense.'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            abort(404);
        }

        return $expense;
    }

    public function downloadBills($id)
    {
        $expense = Expense::findOrFail($id);
        $expensename = $expense->name;
        $bills = json_decode($expense->billfile); // Assuming billfile stores JSON encoded paths

        if (empty($bills)) {
            return redirect()->back()->with('error', 'No bills to download.');
        }

        // Create a new zip file
        $zip = new \ZipArchive();
        $zipFileName = 'bills_expense_' . $expensename . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            return redirect()->back()->with('error', 'Could not create ZIP file.');
        }

        foreach ($bills as $filePath) {
            $fullPath = storage_path('app/public/' . $filePath);
            if (file_exists($fullPath)) {
                $zip->addFile($fullPath, basename($filePath));
            }
        }

        $zip->close();

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request)
    {
        $id = $request->input('edit_expense_id');
        try {
            $expense = Expense::findOrFail($id);
            $data = $request->validated();

            // Update the fields
            $expense->name = $data['name'];
            $expense->category = $data['category'];
            $expense->amount = $data['amount'];
            $expense->billdate = $data['billdate'];
            $expense->status = $data['status'];

            // Handle file upload if it exists
            if ($request->hasFile('billfile')) {
                // Handle multiple file uploads
                $filePaths = [];
                foreach ($request->file('billfile') as $file) {
                    $filePaths[] = $file->store('expenses', 'public'); // Store in the 'expenses' directory
                }
                // Update with new file paths
                $expense->billfile = json_encode($filePaths);
            }

            // Save the updated expense
            $expense->save();

            return response()->json(['success' => 'Expense updated successfully!', 'expense' => $expense], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating expense: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update expense.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json(['success', 'Expense deleted successfully.'], 201);
    }
}
