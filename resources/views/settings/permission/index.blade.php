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

                    <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ route('home') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a>
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
                            <div class="col-md-12">

                                <div class="card mt-3">

                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Permissions</h4>
                                        @can('create permission')
                                            <a href="{{ url('permissions/create') }}" class="btn btn-primary"> Add
                                                Permission</a>
                                        @endcan
                                    </div>
                                    <div class="card-body">

                                        {{-- <table class="table table-bordered table-striped"> --}}
                                        <table
                                            class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th width="40%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($permissions as $permission)
                                                    <tr>
                                                        <td>{{ $permission->id }}</td>
                                                        <td>{{ $permission->name }}</td>
                                                        <td>
                                                            @can('update permission')
                                                                <a href="{{ url('permissions/' . $permission->id . '/edit') }}"
                                                                    class="btn btn-success">Edit</a>
                                                            @endcan

                                                            @can('delete permission')
                                                                <a href="{{ url('permissions/' . $permission->id . '/delete') }}"
                                                                    class="btn btn-danger mx-2">Delete</a>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach --}}
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        jQuery(function($) {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('permissions.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
