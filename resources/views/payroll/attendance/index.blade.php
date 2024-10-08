@extends('layouts.dashboard')
@section('title', 'Attendance')
@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="page-title">Attendance</h3>
                <a type="button" class="waves-effect waves-light btn btn-primary" title="Back"
                    href="{{ route('report.attendance.month') }}">
                    <span class="hidden-sm-up"><i class="fa fa-download">‌ </i></span>
                    <span class="hidden-xs-down"><i class="fa fa-download">‌ </i>  Attendance Report</span>
                </a>
            </div>
        </div>

        <section class="content">
            <form method="post" action="{{ route('attendance.store') }}" id="attendanceForm">
                @csrf
                <input type="hidden" name="selected_date" id="selected_date" value="{{ now()->format('Y-m-d') }}"> <!-- Hidden field for selected date -->
                <div id="formError" class="text-danger" style="display: none; margin-bottom: 1rem;"></div> <!-- Error message container -->
                <div class="box">
                    <div class="box-body">
                        <div id="attendance_paginator"></div>
                        <br />
                        <div class="box no-border mb-0" id="orderResults">
                            <div class="box-header py-2">
                                <h4 class="box-title">Staff Attendance</h4>
                            </div>
                            <div class="box-body">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Attendance Status</th>
                                            <th class="text-center">Login Time</th>
                                            <th class="text-center">Logout Time</th>
                                            <th class="text-center">Worked Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be populated by AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-end p-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Save Attendance
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
<script>
    window.csrfToken = '{{ csrf_token() }}';
    var attendanceCreateRoute = "{{ route('attendance.create') }}";
    var present = '{{App\Models\EmployeeAttendance::PRESENT}}';
    var on_leave = '{{App\Models\EmployeeAttendance::ON_LEAVE}}';

</script>
  <script src="{{ asset('js/attendance.js') }}"></script>

@endsection
