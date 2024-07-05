@extends('layouts.dashboard')
@section('title', 'Appointment')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">Appointment created successfully
                </div>
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fade fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Appointment List</h3>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    {{-- <div class="box-body p-0"> --}}
                    <div class="box-body">
                        <div id="paginator"></div>
                        <br />
                        <div class="table-responsive">
                            <!-- Main content -->
                            <table
                                class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center"
                                width="100%">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <!-- <th>No</th> -->
                                        <th>Token No</th>
                                        <th>Patient ID</th>
                                        <th>Patient Name</th>
                                        {{-- <th>Age</th> --}}
                                        <th>Consulting Doctor</th>
                                        {{-- <th>Department</th> --}}
                                        {{-- <th>Disease</th> --}}
                                        <th>Phone number</th>
                                        <th>Branch</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th width="150px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('appointment.booking')
    @include('appointment.reschedule')
    @include('appointment.cancel')

    {{-- </div> --}}

    <!-- ./wrapper -->
    <script type="text/javascript">
        var selectedDate;
        var table;

        $(document).ready(function() {

            $("#paginator").datepaginator({
                onSelectedDateChanged: function(a, t) {
                    selectedDate = moment(t).format("YYYY-MM-DD");
                    // Reload DataTable with new data based on selected date
                    // reloadTableData();
                    table.ajax.reload();
                },
            });

            var initialDate = $("#paginator").datepaginator("getDate");
            selectedDate = moment(initialDate).format("YYYY-MM-DD")

        });


        jQuery(function($) {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                // ajax: "",

                ajax: {
                    url: "{{ route('appointment') }}",
                    type: 'GET',
                    // data: {
                    //     selectedDate: selectedDate
                    // }
                    data: function(d) {
                        d.selectedDate = selectedDate; // Add selectedDate as a query parameter
                    }
                },
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function(data, type, row, meta) {
                    //         // Return the row index (starts from 0)
                    //         return meta.row + 1; // Adding 1 to start counting from 1
                    //     }
                    // },
                    {
                        data: 'token_no',
                        name: 'token_no'
                    },
                    {
                        data: 'patient_id',
                        name: 'patient_id'
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'doctor',
                        name: 'doctor'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'branch',
                        name: 'branch'
                    },
                    {
                        data: 'app_date',
                        name: 'app_date'
                    },

                    {
                        data: 'app_time',
                        name: 'app_time'
                    },
                    

                    // {
                    //     data: 'phone',
                    //     name: 'phone'
                    // },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]
            });


            $(document).on('click', '.btn-edit', function() {
                var appId = $(this).data('id');
                $('#reschedule_app_id').val(appId); // Set app ID in the hidden input
                $.ajax({
                    url: '{{ url("appointment", "") }}' + "/" + appId + "/edit",
                    method: 'GET',
                    success: function(response) {
                        $('#reschedule_app_id').val(response.id);
                        // $('#edit_staff').val(response.staff);

                       
                        $('#modal-reschedule').modal('show');
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            });

        });

        function reloadTableData() {
            table.ajax.reload();
        }
    </script>
@endsection
