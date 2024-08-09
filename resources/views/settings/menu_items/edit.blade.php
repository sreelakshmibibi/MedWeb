@extends('layouts.dashboard')

@section('title', 'Edit Menu Item')

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
                    <h3 class="page-title">Edit Menu Item</h3>
                    <a href="{{ url('menu_items') }}" class="btn btn-primary">Back</a>
                </div>
            </div>

            <section class="content">
                <div class="row ">
                    <div class="container mt-5">
                        <form action="{{ route('menu_items.update', $menuItem->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card">
                                        @if ($errors->any())
                                            <ul class="alert alert-warning">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <label for="name" class="col-md-2 col-form-label">Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ old('name', $menuItem->name) }}" required />
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="url" class="col-md-2 col-form-label">URL <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="url" class="form-control"
                                                        value="{{ old('url', $menuItem->url) }}" required />
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="route_name" class="col-md-2 col-form-label">Route Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="route_name" class="form-control"
                                                        value="{{ old('route_name', $menuItem->route_name) }}" required />
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="parent_id" class="col-md-2 col-form-label">Parent Menu
                                                    Item</label>
                                                <div class="col-md-8">
                                                    <select name="parent_id" class="form-control">
                                                        <option value="">None</option>
                                                        @foreach ($menuItems as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->id == old('parent_id', $menuItem->parent_id) ? 'selected' : '' }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="order_no" class="col-md-2 col-form-label">Order Number <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="number" name="order_no" class="form-control"
                                                        value="{{ old('order_no', $menuItem->order_no) }}" required />
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="icon" class="col-md-2 col-form-label">Icon<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="icon" class="form-control"
                                                        value="{{ old('icon', $menuItem->icon) }}" />
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="roles" class="col-md-2 col-form-label">Roles<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <select name="roles[]" class="form-control" multiple>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ in_array($role->id, $selectedRoles) ? 'selected' : '' }}>
                                                                {{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="status" class="col-md-2 col-form-label">Status <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <select name="status" class="form-control" required>
                                                        <option value="Y"
                                                            {{ old('status', $menuItem->status) == 'Y' ? 'selected' : '' }}>
                                                            Active
                                                        </option>
                                                        <option value="N"
                                                            {{ old('status', $menuItem->status) == 'N' ? 'selected' : '' }}>
                                                            Inactive
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <button type="submit" class="btn btn-primary">Update</button> --}}
                                            <div class="row mb-3">
                                                <div class="col-md-12 d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-primary">Update</button>
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
