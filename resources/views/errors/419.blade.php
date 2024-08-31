<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <title>Page Expired</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('css/horizontal-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skin_color.css') }}">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="layout-top-nav dark-skin theme-primary fixed">

    <div class="wrapper">

        @php
            $logoPath = session('logoPath');
            $clinicName = session('clinicName');

        @endphp
        <header class="main-header">
            <div class="inside-header">
                <div class="d-flex align-items-center logo-box justify-content-start">
                    <!-- Logo -->
                    <a href="#" class="logo">
                        <div class="logo d-inline-flex align-items-center">
                            @if ($logoPath)
                                <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo"
                                    style="width: 50px; margin-right:10px;">
                            @else
                                <img src="{{ asset('images/logo-lt.png') }}" alt="Logo">
                            @endif
                            @if ($clinicName)
                                <h4>{{ $clinicName }}</h4>
                            @else
                                <h4 class="align-self-center medweb_text"
                                    style="margin-left:-5px; margin-bottom: 0; font-size: 1.75rem;">
                                    MedWeb</h4>
                            @endif
                        </div>
                    </a>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            <div class="container-full">
                <section class="error-page h-p100">
                    <div class="container h-p100">
                        <div class="row h-p100 align-items-center justify-content-center text-center">
                            <div class="col-lg-7 col-md-10 col-12">
                                <div class="rounded10 p-50">
                                    <img src="{{ asset('images/auth-bg/419.png') }}" class="max-w-200"
                                        alt="" />
                                    <h1>419 - Page Expired!</h1>
                                    <h3>Sorry, the page you are looking for is expired.</h3>
                                    <div class="my-30">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="btn btn-danger" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                            this.closest('form').submit();">Back
                                                to
                                                Login</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @include('dashboard.footer')

    <!-- Vendor JS -->
    <script src="{{ asset('js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>

    <!-- MedWeb App -->
    <script src="{{ asset('js/jquery.smartmenus.js') }}"></script>
    <script src="{{ asset('js/menus.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>

</body>

</html>
