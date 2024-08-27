@extends('layouts.dashboard')
@section('title', 'Roles & Permissions')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">

            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Roles and Permissions</h3>

                    <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ url('roles') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a>
                </div>
            </div>

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

            @if ($errors->any())
                <ul class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <section class="content ">
                <div class="box">
                    <div class="box-header py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Edit User</h4>
                            {{-- @can('create role')
                                <a href="{{ url('roles') }}" class="btn btn-primary">Back</a>
                            @endcan --}}
                        </div>
                    </div>

                    <form action="{{ url('users/' . $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="box-body">

                            <div class="form-group">
                                <label class="form-label" for="name">Name <span class="text-danger">
                                        *</span></label>
                                <input class="form-control" type="text" id="name" name="name" placeholder="Name"
                                    value="{{ str_replace('<br>', ' ', $user->name) }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="email">Email ID<span class="text-danger">
                                        *</span></label>
                                <input class="form-control" type="email" id="email" name="email"
                                    placeholder="Email ID" readonly value="{{ $user->email }}">
                                <div id="emailError" class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="password">Password<span class="text-danger">
                                        *</span></label>
                                <input class="form-control" type="password" id="password" name="password"
                                    placeholder="password">

                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="roles">Roles<span class="text-danger">
                                        *</span></label>
                                <select name="roles[]" id="roles" class="form-control" multiple>
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

                        </div>
                        <div class="box-footer p-3 text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update
                            </button>
                        </div>
                    </form>

                </div>
            </section>
        </div>
    </div>
@endsection
