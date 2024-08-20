@extends('layouts.dashboard')
@section('title', 'Roles & Permissions')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Roles and Permissions</h3>

                    {{-- <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ route('home') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a> --}}
                    <a type="button" title="Back" class="waves-effect waves-light btn btn-primary"
                        href="{{ route('home') }}">
                        <span class="hidden-sm-up">Back</span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-angles-left"></i> Back</span>
                    </a>

                    </button>
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

            <!-- Nav tabs -->
            <ul class="nav nav-tabs " role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#rolestabcontent" role="tab"
                        id="rolestabtitle">
                        <span class="hidden-sm-up"><i class="fa-solid fa-house-chimney-medical"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-user me-10"></i>Roles</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#permissionstabcontent" role="tab"
                        id="permissionstabtitle">
                        <span class="hidden-sm-up"><i class="fa-solid fa-hospital"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-file-signature me-10"></i>Permissions</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#userstabcontent" role="tab" id="userstabtitle">
                        <span class="hidden-sm-up"><i class="fa-solid fa-users"></i> </span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-users me-10"></i>Users</span>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="rolestabcontent" role="tabpanel">
                    <div class="py-15">
                        <!-- Main content -->
                        <section class="content ">
                            <div class="box">
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr class="bg-primary-light text-center">
                                                    <th width="5%">Id</th>
                                                    <th>Name</th>
                                                    <th width="30%">
                                                        @can('create role')
                                                            {{-- <a href="{{ url('roles/create') }}"
                                                                class="btn btn-sm btn-primary"><i class="fa fa-plus"> </i>
                                                                Add Role</a> --}}
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                data-bs-toggle="modal" data-bs-target="#modal-createrole"> <i
                                                                    class="fa fa-plus"> </i>
                                                                Add Role</button>
                                                        @else
                                                            Action
                                                        @endcan
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($roles as $role)
                                                    <tr>
                                                        <td class="text-center">{{ $role->id }}</td>
                                                        <td class="text-left">{{ $role->name }}</td>
                                                        <td class="text-center">
                                                            <a href="{{ url('roles/' . $role->id . '/give-permissions') }}"
                                                                class="btn btn-sm  btn-dark me-2">
                                                                Add / Edit Role Permission
                                                            </a>

                                                            @can('update role')
                                                                <a href="{{ url('roles/' . $role->id . '/edit') }}"
                                                                    class="btn btn-sm btn-success me-2">Edit
                                                                </a>
                                                            @endcan

                                                            @can('delete role')
                                                                <a href="{{ url('roles/' . $role->id . '/delete') }}"
                                                                    class="btn btn-sm btn-danger ">Delete
                                                                </a>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="tab-pane" id="permissionstabcontent" role="tabpanel">
                    <div class="py-15">
                        @include('settings.permission.index')
                    </div>
                </div>

                <div class="tab-pane" id="userstabcontent" role="tabpanel">
                    <div class="py-15">
                        @include('settings.user.index')
                    </div>
                </div>
            </div>

            @include('settings.role.create_modal')

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fadeOutAlert(alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease-out';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }

            document.querySelectorAll('.myadmin-alert').forEach(function(alert) {
                setTimeout(function() {
                    fadeOutAlert(alert);
                }, 3000);

                alert.querySelector('.closed').addEventListener('click', function(event) {
                    event.preventDefault();
                    fadeOutAlert(alert);
                });
            });
        });
    </script>
@endsection
