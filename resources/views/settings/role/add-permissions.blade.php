@extends('layouts.dashboard')
@section('title', 'Patients')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">
                </div>
                @if (session('status'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('status') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Roles and Permissions</h3>


                </div>
            </div>

            <section class="content">

                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-12">


                            <div class="card">

                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Role : {{ $role->name }}</h4>
                                    @can('create role')
                                        <a href="{{ url('roles') }}" class="btn btn-primary">Back</a>
                                    @endcan
                                </div>
                                <div class="card-body">

                                    <form action="{{ url('roles/' . $role->id . '/give-permissions') }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3">
                                            @error('permission')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                            <label for="">Permissions</label>
                                            <div class="row">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-md-2">
                                                        <label>
                                                            <input id="permission_{{ $permission->id }}" type="checkbox"
                                                                class="filled-in chk-col-success" name="permission[]"
                                                                value="{{ $permission->name }}"
                                                                {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                                            <label for="permission_{{ $permission->id }}"></label>
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            {{-- <div class="row">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-md-2">
                                                        <label>
                                                            
                                                        </label>
                                                        <input id="permission_{{ $permission->id }}" type="checkbox"
                                                                class="filled-in chk-col-success" name="permission[]"
                                                                value="{{ $permission->name }}"
                                                                {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                                            <label for="permission_{{ $permission->id }}"></label>
                                                    </div>
                                                @endforeach
                                            </div> --}}
                                            {{-- <div class="container mt-5">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4>Permissions</h4>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Permission</th>
                                                                    <th>Select</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($permissions as $permission)
                                                                    <tr>
                                                                        <td>{{ $permission->name }}</td>
                                                                        <td>
                                                                            <input id="permission_{{ $permission->id }}"
                                                                                type="checkbox"
                                                                                class="filled-in chk-col-success"
                                                                                name="permission[]"
                                                                                value="{{ $permission->name }}"
                                                                                {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                                                            <label
                                                                                for="permission_{{ $permission->id }}"></label>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> --}}


                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>


@endsection
{{-- <div class="col-md-12">

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Role : {{ $role->name }}</h4>
            @can('create role')
                <a href="{{ url('roles') }}" class="btn btn-primary">Back</a>
            @endcan
        </div>
        <div class="card-body">

            <form action="{{ url('roles/' . $role->id . '/give-permissions') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    @error('permission')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <label for="">Permissions</label>

                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-2">
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permission->name }}"
                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
