<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Expense\ExpenseRequest;
use App\Models\Expense;
use App\Models\LabBill;
use App\Models\ExpenseCategory;
use App\Models\ClinicBranch;
use App\Models\ClinicBasicDetail;
use Yajra\DataTables\DataTables as DataTables;
use Illuminate\Support\Facades\Crypt;

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
        $clinicBranches = ClinicBranch::with(['country', 'state', 'city'])
            ->where('clinic_status', 'Y')
            ->get();

        if ($request->ajax()) {

            $expenses = Expense::select('expenses.*', 'expense_categories.category as category_name', 'clinic_branches.clinic_address as branch') // Select fields from both tables
                ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id') // Join the expense_categories table
                ->join('clinic_branches', 'expenses.branch_id', '=', 'clinic_branches.id') // Join the clinic_branches table
                ->where('expense_categories.status', 'Y') // Filter by status
                ->orderBy('billdate', 'asc')
                ->get();
            $expenses->transform(function ($expense) {
                $expense->branch = str_replace('<br>', ' ', $expense->branch);
                return $expense;
            });

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
        return view('expense.expense.index', compact('categories', 'clinicBranches'));
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
            $expense->category_id = $data['category_id'];
            $expense->branch_id = $data['branch_id'];
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


    public function getExpensesByDate(Request $request)
    {
        $date = $request->input('date');
        $branchId = $request->input('branch_id');
        $clinicBasicDetails = ClinicBasicDetail::first();

        // Decrypt branch ID if present
        if ($branchId) {
            $branchIdDecryptd = base64_decode(Crypt::decrypt($branchId));
        }

        // Query clinic expenses
        $clinicExpenseQuery = Expense::whereDate('created_at', $date)
            ->where('status', 'Y');

        if ($branchId) {
            $clinicExpenseQuery->where('branch_id', $branchIdDecryptd);
        }

        $clinicExpenses = $clinicExpenseQuery->get();

        // Query lab expenses
        $labExpenseQuery = LabBill::whereDate('created_at', $date)
            ->where('lab_bill_status', 2)
            ->with('technician');

        if ($branchId) {
            $labExpenseQuery->where('branch_id', $branchIdDecryptd);
        }

        $labExpenses = $labExpenseQuery->get();

        // Merge clinic and lab expenses
        $mergedExpenses = $clinicExpenses->merge($labExpenses);

        // Sort by created_at date
        $sortedExpenses = $mergedExpenses->sortBy('created_at');
        if ($request->ajax()) {
            // Return DataTable response
            return DataTables::of($sortedExpenses)
                ->addIndexColumn()
                ->addColumn('expenseName', function ($row) {
                    if (isset($row->name)) {
                        return $row->name;
                    } elseif (isset($row->technician)) {
                        return 'Lab Bill paid for ' . $row->technician->name . ', Lab: ' . $row->technician->lab_name . ', Address: ' . $row->technician->lab_address;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount ?? $row->amount_paid ?? 'N/A';
                })
                ->addColumn('billDate', function ($row) {
                    return isset($row->created_at) ? $row->created_at->format('d-m-Y') : 'N/A';
                })
                ->make(true);
        }
        return view('report.expense', compact('sortedExpenses', 'clinicBasicDetails'));
    }


}
