@extends('layouts.dashboard')

@section('title', 'Add Menu Item')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">

            <div class="content-header">
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
                    <h3 class="page-title">Add Menu Item</h3>
                    <a href="{{ url('menu_items') }}" class="btn btn-primary">Back</a>
                </div>
            </div>

            <section class="content">
                <div class="row ">
                    <div class="container mt-5">
                        <form action="{{ route('menu_items.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    @if ($errors->any())
                                        <ul class="alert alert-warning">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <label for="name" class="col-md-2 col-form-label">Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="name" name="name" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="url" class="col-md-2 col-form-label">URL <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="url" name="url" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="route_name" class="col-md-2 col-form-label">Route Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="route_name" name="route_name"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="parent_id" class="col-md-2 col-form-label">Parent Menu
                                                    Item</label>
                                                <div class="col-md-8">
                                                    <select id="parent_id" name="parent_id" class="form-control">
                                                        <option value="">None</option>
                                                        @foreach ($menuItems as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="order_no" class="col-md-2 col-form-label">Order No <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="number" id="order_no" name="order_no"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="icon" class="col-md-2 col-form-label">Icon<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="icon" name="icon" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="roles" class="col-md-2 col-form-label">Roles<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <select id="roles" name="roles[]" class="form-control" multiple
                                                        required>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="status" class="col-md-2 col-form-label">Status <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <select id="status" name="status" class="form-control" required>
                                                        <option value="Y">Active</option>
                                                        <option value="N">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12 d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
