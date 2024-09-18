<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Http\Requests\Expense\ExpenseCategoryRequest;
use Yajra\DataTables\DataTables as DataTables;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:expense category', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $medicines = ExpenseCategory::orderBy('category', 'asc')->get();

            return DataTables::of($medicines)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category;
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
                        data-bs-target="#modal-edit" ><i class="fa fa-pencil"></i></button>
                        <button type="button" class="waves-effect waves-light btn btn-circle btn-danger btn-del btn-xs" data-bs-toggle="modal" data-bs-target="#modal-delete" data-id="' . $row->id . '" title="delete">
                        <i class="fa fa-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('expense.category.index');
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
    public function store(ExpenseCategoryRequest $request)
    {
        try {
            
            $category = new ExpenseCategory();
            $category->category = ucwords(strtolower($request->input('category')));
            $category->status = $request->input('status');
            $category->created_by = auth()->user()->id;

            // Save the department
            $i = $category->save();
            if ($i) {
                if ($request->ajax()) {
                    return response()->json(['success' => 'Category created successfully.']);
                }
                return redirect()->back()->with('success', 'Category created successfully');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Category not created.'. $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage());
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
        $category = ExpenseCategory::find($id);
        if (!$category) {
            abort(404);
        }

        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseCategoryRequest $request)
    {
        try {
            $category = ExpenseCategory::findOrFail($request->edit_category_id);
            $category->category = ucwords(strtolower($request->category));;
            $category->status = $request->status;
            $category->updated_by = auth()->user()->id;
            $category->save();

            if ($request->ajax()) {
                return response()->json(['success' => 'Category updated successfully.']);
            }
            return redirect()->back()->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update category. Please try again.'.$e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed to update category. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $category->delete();

        return response()->json(['success', 'Category deleted successfully.'], 201);
    }
}
