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

    {{-- <title>MedWeb - Dashboard</title> --}}
    <title>@yield('title', 'MedWeb')</title>
    {{-- <title>{{ config('app.name', 'MedWeb') }}</title> --}}

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('css/horizontal-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skin_color.css') }}">

    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script src="{{ asset('assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/apexcharts-bundle/dist/apexcharts.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/tooth_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="layout-top-nav dark-skin theme-primary fixed">

    <div class="wrapper">
        @yield('loader')

        @include('dashboard.header')
        <?php
        use App\Services\CommonService;
        $commonService = new CommonService();
        $menuItems = $commonService->getMenuItems();
        ?>
        @include('dashboard.menu', ['menuItems' => $menuItems])

        <!-- Content area -->
        @yield('content')

    </div>

    @include('dashboard.footer')

    <!-- Vendor JS -->
    <script src="{{ asset('js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_plugins/bootstrap-slider/bootstrap-slider.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/OwlCarousel2/dist/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/flexslider/jquery.flexslider.js') }}"></script>

    <script src="{{ asset('js/pages/statistic.js') }}"></script>

    <!-- MedWeb App -->
    <script src="{{ asset('js/jquery.smartmenus.js') }}"></script>
    <script src="{{ asset('js/menus.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
    <script src="{{ asset('js/pages/advanced-form-element.js') }}"></script>

    <script src="{{ asset('assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup-init.js') }}">
    </script>

    <script src="{{ asset('assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script src="{{ asset('assets/vendor_plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/moment/min/moment.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_components/jquery-steps-master/build/jquery.steps.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/jquery-validation-1.17.0/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/pages/steps.js') }}"></script>
    <script src="{{ asset('js/pages/steps_patient.js') }}"></script>
    <script src="{{ asset('js/pages/steps_treatment.js') }}"></script>

    <script src="{{ asset('assets/vendor_components/bootstrap-select/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js') }}"></script>

    <script src="{{ asset('assets/vendor_components/date-paginator/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/date-paginator/bootstrap-datepaginator.min.js') }}"></script>
    <script src="{{ asset('js/pages/date-paginator.js') }}"></script>

    <script src="{{ asset('js/pages/doctor-details.js') }}"></script>

    <script src="{{ asset('js/pages/dashboard2.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard3.js') }}"></script>
    <script src="{{ asset('js/pages/slider.js') }}"></script>

    <script src="{{ asset('assets/vendor_components/dropzone/dropzone.js') }}"></script>

    <!-- Include scripts section -->
    @yield('scripts')
</body>

</html>
