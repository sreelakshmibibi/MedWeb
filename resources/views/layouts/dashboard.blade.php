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

    <script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>
    <link href="{{ asset('css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

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
    <script src="{{ asset('js/pages/steps_viewpatient.js') }}"></script>

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
   @include('layouts.startWorkModal')
   @include('layouts.stopWorkModal')
</body>
<script>
        $(document).ready(function() {
            @if(session('showModal'))
                $('#modal-start-work').modal('show');
                {{ session()->forget('showModal') }} // Clear the session variable
            @endif

            // Confirm start work
            $('#btn-confirm-start').click(function() {
                $.ajax({
                    url: '/start-work', // Your route for handling the start work
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: '{{ auth()->user()->id }}', // Get the logged-in user's ID
                        attendance_status: 'Present' // Set the attendance status
                    },
                    success: function(response) {
                        $('#modal-start-work').modal('hide'); // Hide the modal
                    },
                    error: function(xhr) {
                        // Handle error
                        alert('Failed to start work: ' + xhr.responseText);
                    }
                });
            });

            $('#logout-button').click(function(event) {
                event.preventDefault(); // Prevent default link behavior
                $('#modal-stop-work').modal('show'); // Show the logout modal
            });

            $('#btn-confirm-logout').click(function() {
                // Perform logout without recording the logout timing
                $.ajax({
                    url: '/logout-cancel', // Create this route in your application
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        window.location.href = '/login'; // Redirect after logout
                    }
                });
            });
        });

        $('#btn-confirm-logout-record').click(function() {
            const now = new Date();
            const logoutDate = now.toISOString().split('T')[0]; // Format: YYYY-MM-DD
            const logoutTime = now.toTimeString().split(' ')[0]; // Format: HH:mm:ss
            $.ajax({
                url: '/finish-work', // Your route for handling finish work
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: '{{ auth()->user()->id }}', // Get the logged-in user's ID
                    logout_date: logoutDate,
                    logout_time: logoutTime, // Send the formatted time
                },
                success: function(response) {
                    $('#modal-stop-work').modal('hide'); // Hide the modal
                    // Optionally, redirect or update the UI
                    $.ajax({
                    url: '/logout-cancel', // Create this route in your application
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        window.location.href = '/login'; // Redirect after logout
                    }
                });
                    
                },
                error: function(xhr) {
                    alert('Failed to finish work: ' + xhr.responseText);
                }
            });
        });
    </script>
</html>
