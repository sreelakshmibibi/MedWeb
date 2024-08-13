@extends('layouts.dashboard')

@section('title', 'Menu Items')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Menu Items</h3>

                    <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ route('home') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a>
                </div>
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

            <section class="content ">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr class="bg-primary-light text-center">
                                        <th>Name</th>
                                        <th>URL</th>
                                        <th>Icon</th>
                                        <th>Order No</th>
                                        <th>Route Name</th>
                                        <th>Status</th>
                                        <th>
                                            @can('create menu item')
                                                <a href="{{ route('menu_items.create') }}" class="btn btn-sm btn-primary">Add
                                                    Menu
                                                    Item</a>
                                            @else
                                                Action
                                            @endcan
                                        </th>
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
            </section>
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
