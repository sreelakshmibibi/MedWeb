<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:staff view', ['only' => ['index']]);
        $this->middleware('permission:staff create', ['only' => ['create', 'store']]);
        $this->middleware('permission:staff update', ['only' => ['update', 'edit']]);
        $this->middleware('permission:staff delete', ['only' => ['destroy']]);
    }

    // public function index()
    // {
    //     $users = User::get();

    //     return view('settings.user.index', ['users' => $users]);
    // }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::get();

            return DataTables::of($users)
                ->addIndexColumn()
                // ->addColumn('name', function ($row) {
                //     $name = str_replace('<br>', ' ', $row->name);

                //     return $name;
                // })
                ->addColumn('roles', function ($user) {
                    $roles = $user->getRoleNames(); // Get role names
    
                    return $roles->map(function ($role) {
                        return '<label class="badge bg-primary mx-1">' . $role . '</label>';
                    })->implode(''); // Concatenate badges
                })
                ->addColumn('action', function ($user) {
                    $btn = '';
                    if (auth()->user()->can('staff update')) {
                        $btn .= '<a href="' . url('users/' . $user->id . '/edit') . '" class="btn btn-sm btn-success btn-edit" data-id="' . $user->id . '">Edit</a> ';
                    }
                    if (auth()->user()->can('staff delete')) {
                        $btn .= '<a href="' . url('users/' . $user->id . '/delete') . '" class="btn btn-sm btn-danger btn-delete" data-id="' . $user->id . '">Delete</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }

        return view('settings.user.index');
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('settings.user.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'roles' => 'required|array',
        ]);

        $isAdmin = in_array('Admin', $request->roles) ? 1 : 0;
        $isDoctor = in_array('Doctor', $request->roles) ? 1 : 0;
        $isNurse = in_array('Nurse', $request->roles) ? 1 : 0;
        $isReception = in_array('Reception', $request->roles) ? 1 : 0;

        $user = User::create([
            'name' => ucwords(strtolower($request->name)). "<br>",
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $isAdmin,
            'is_doctor' => $isDoctor,
            'is_nurse' => $isNurse,
            'is_reception' => $isReception,
        ]);

        $user->syncRoles($request->roles);

        // return redirect('/users')->with('status', 'User created successfully with roles');
        return redirect('roles')->with('status', 'User created successfully with roles');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();

        return view('settings.user.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|max:20',
            'roles' => 'required',
        ]);
        $isAdmin = in_array('Admin', $request->roles) ? 1 : 0;
        $isDoctor = in_array('Doctor', $request->roles) ? 1 : 0;
        $isNurse = in_array('Nurse', $request->roles) ? 1 : 0;
        $isReception = in_array('Reception', $request->roles) ? 1 : 0;
        $data = [
            'name' => ucwords(strtolower($request->name)). "<br>",
            'email' => $request->email,
            'is_admin' => $isAdmin,
            'is_doctor' => $isDoctor,
            'is_nurse' => $isNurse,
            'is_reception' => $isReception,
        ];

        if (!empty($request->password)) {
            $data += [
                'password' => Hash::make($request->password),
            ];
        }

        $user->update($data);
        $user->syncRoles($request->roles);

        // return redirect('/users')->with('status', 'User Updated Successfully with roles');
        return redirect('roles')->with('status', 'User Updated Successfully with roles');
    }

    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        // return redirect('/users')->with('status', 'User Delete Successfully');
        return redirect('roles')->with('status', 'User deleted successfully');
    }
}
