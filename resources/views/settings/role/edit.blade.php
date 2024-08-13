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
                            <h4 class="mb-0">Edit Role</h4>
                            {{-- @can('create role')
                                <a href="{{ url('roles') }}" class="btn btn-primary">Back</a>
                            @endcan --}}
                        </div>
                    </div>

                    <form action="{{ url('roles/' . $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="box-body">

                            <div class="form-group">
                                <label class="form-label" for="name">Role Name <span class="text-danger">
                                        *</span></label>
                                <input class="form-control" type="text" id="name" name="name" placeholder="Name"
                                    value="{{ $role->name }}">
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
