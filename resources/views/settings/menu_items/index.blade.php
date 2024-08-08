@extends('layouts.dashboard')

@section('title', 'Menu Items')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">


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
                    <h3 class="page-title">Menu Items</h3>

                    <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ route('home') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a>
                </div>
            </div>

            <section class="content">
                <div class="row ">
                    <div class="container mt-2">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="card mt-3">

                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0"></h4>

                                        @can('create menu item')
                                            <a href="{{ route('menu_items.create') }}" class="btn btn-primary">Add Menu Item</a>
                                        @endcan
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>URL</th>
                                                    <th>Icon</th>
                                                    <th>Order No</th>
                                                    <th>Route Name</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($menuItemsTree as $item)
                                                    @include('settings.menu_items.menu_item_row', [
                                                        'item' => $item,
                                                        'level' => 0,
                                                    ])
                                                @endforeach
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
@endsection
