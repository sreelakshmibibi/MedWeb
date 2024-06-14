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
    <link rel="icon" href="images/favicon.ico">

    <title>MedWeb - Dashboard</title>

    {{-- <title>{{ config('app.name', 'MedWeb') }}</title> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

    <!-- Vendors Style-->
    <link rel="stylesheet" href="css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="css/horizontal-menu.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/skin_color.css">

</head>

<body class="layout-top-nav dark-skin theme-primary fixed">

    <div class="wrapper">
        <div id="loader"></div>

        @include('dashboard.header')

        @include('dashboard.menu')

        @yield('content')

    </div>

    <div class="container-fluid">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-1 border-top">
            <div class="col-md-4 d-flex align-items-center">
                <span class="mb-3 mb-md-0 text-muted">Copyright &copy; 2024 MedWeb</span>
            </div>

            <div class="col-md-4 d-flex justify-content-end">
                <span class="mb-3 mb-md-0 text-muted">Developed by Serieux</span>
            </div>
        </footer>
    </div>

    <!-- Vendor JS -->
    <script src="js/vendors.min.js"></script>
    <script src="js/pages/chat-popup.js"></script>
    <script src="assets/icons/feather-icons/feather.min.js"></script>

    <script src="assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
    <script src="assets/vendor_components/OwlCarousel2/dist/owl.carousel.js"></script>

    <!-- MedWeb App -->
    <script src="js/jquery.smartmenus.js"></script>
    <script src="js/menus.js"></script>
    <script src="js/template.js"></script>
    <script src="js/pages/dashboard3.js"></script>

</body>

</html>
