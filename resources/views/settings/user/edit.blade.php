@extends('layouts.dashboard')
@section('title', 'Patients')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">
                </div>
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
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

                <div class="row ">
                    <div class="container mt-5">
                        <a href="{{ url('roles') }}" class="btn btn-primary mx-1">Roles</a>
                        <a href="{{ url('permissions') }}" class="btn btn-info mx-1">Permissions</a>
                        <a href="{{ url('users') }}" class="btn btn-warning mx-1">Users</a>
                    </div>

                    <div class="container mt-2">
                        <div class="row">
                            <div class="container mt-5">
                                <div class="row">
                                    <div class="col-md-12">

                                        @if ($errors->any())
                                            <ul class="alert alert-warning">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        <div class="card">

                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h4 class="mb-0">Edit User</h4>
                                                <a href="{{ url('users') }}" class="btn btn-primary">Back</a>
                                            </div>

                                            <div class="card-body">
                                                <form action="{{ url('users/' . $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="mb-3">
                                                        <label for="">Name</label>
                                                        <input type="text" name="name" value="{{ $user->name }}"
                                                            class="form-control" />
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="">Email</label>
                                                        <input type="text" name="email" readonly
                                                            value="{{ $user->email }}" class="form-control" />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="">Password</label>
                                                        <input type="text" name="password" class="form-control" />
                                                        @error('password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="">Roles</label>
                                                        <select name="roles[]" class="form-control" multiple>
                                                            <option value="">Select Role</option>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role }}"
                                                                    {{ in_array($role, $userRoles) ? 'selected' : '' }}>
                                                                    {{ $role }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('roles')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
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

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
