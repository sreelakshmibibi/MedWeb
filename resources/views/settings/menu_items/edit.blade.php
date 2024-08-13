@extends('layouts.dashboard')

@section('title', 'Menu Items')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Edit Menu Item</h3>
                    <a href="{{ url('menu_items') }}" class="btn btn-primary"><i class="fa-solid fa-angles-left"></i>
                        Back</a>
                </div>
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

            @if ($errors->any())
                <ul class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <section class="content ">
                <div class="box">

                    <form action="{{ route('menu_items.update', $menuItem->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Name <span class="text-danger">
                                                *</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $menuItem->name) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="url">URL <span class="text-danger">
                                                *</span></label>
                                        <input type="text" class="form-control" id="url" name="url"
                                            value="{{ old('url', $menuItem->url) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="route_name">Route Name <span class="text-danger">
                                                *</span></label>
                                        <input type="text" class="form-control" id="route_name" name="route_name"
                                            value="{{ old('route_name', $menuItem->route_name) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="parent_id">Parent Menu
                                            Item</label>
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
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="order_no">Order No <span class="text-danger">
                                                *</span></label>
                                        <input class="form-control" type="number" id="order_no" name="order_no"
                                            value="{{ old('order_no', $menuItem->order_no) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="icon">Icon <span class="text-danger">
                                                *</span></label>
                                        <input class="form-control" type="text" id="icon" name="icon"
                                            value="{{ old('icon', $menuItem->icon) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="roles">Roles <span class="text-danger">
                                                *</span></label>
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="status">Status <span class="text-danger">
                                                *</span></label>
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
