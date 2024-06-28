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

    {{-- <title>MedWeb - Log in </title> --}}
    <title>@yield('title')</title>
    {{-- <title>{{ config('app.name', 'MedWeb') }}</title> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('css/horizontal-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skin_color.css') }}">
</head>

{{-- <body> --}}

<body class="hold-transition theme-primary bg-img"
    style="background-image: url({{ asset('images/front-end-img/banners/slider-2.jpg') }})">

    @yield('content')

    {{-- @yield('footer') --}}
    @include('layouts.footer')

    <!-- Vendor JS -->
    <script src="{{ asset('js/vendors.min.js') }}"></script>
    <script src="{{ asset('js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>

</body>

</html>
