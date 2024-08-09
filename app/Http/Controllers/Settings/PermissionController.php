<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view permission', ['only' => ['index']]);
        $this->middleware('permission:create permission', ['only' => ['create', 'store']]);
        $this->middleware('permission:update permission', ['only' => ['update', 'edit']]);
        $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $permissions = Permission::orderBy('name')->get();
        if ($request->ajax()) {

            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {

                    return $row->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';

                    // Check if the user has 'update permission' ability
                    if (auth()->user()->can('update permission')) {
                        $btn .= '<a href="'.url('permissions/'.$row->id.'/edit').'" class="btn btn-success">Edit</a> ';
                    }

                    // Check if the user has 'delete permission' ability
                    if (auth()->user()->can('delete permission')) {
                        $btn .= '<a href="'.url('permissions/'.$row->id.'/delete').'" class="btn btn-danger mx-2">Delete</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.permission.index', ['permissions' => $permissions]);
    }

    public function create()
    {
        return view('settings.permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name',
            ],
        ]);

        Permission::create([
            'name' => $request->name,
        ]);

        return redirect('permissions')->with('status', 'Permission Created Successfully');
    }

    public function edit(Permission $permission)
    {
        return view('settings.permission.edit', ['permission' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name,'.$permission->id,
            ],
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect('permissions')->with('status', 'Permission Updated Successfully');
    }

    public function destroy($permissionId)
    {
        $permission = Permission::find($permissionId);
        $permission->delete();

        return redirect('permissions')->with('status', 'Permission Deleted Successfully');
    }
}
