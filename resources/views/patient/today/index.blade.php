<div class="box">
    <div class="box-header with-border pb-2 ">
        <h4 class="box-title">Patients List</h4>
        <p class="mb-0 pull-right">Today</p>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center" width="100%">

                <thead>
                    <tr class="bg-primary-light">
                        <th>No</th>
                        {{-- <th>Date</th> --}}
                        <th>PID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Doctor</th>
                        <th>Phone</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                        {{-- <th>Settings</th> --}}
                        <th width="20px">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer bg-light py-10 with-border">
        <div class="d-flex align-items-center justify-content-between">
            <p class="mb-0">Total <span id="total-value"></span> Patients</p>
            <a type="button" href="{{ route('patient.patient_list') }}"
                class="waves-effect waves-light btn btn-primary">View
                All</a>
        </div>
    </div>
</div>

{{-- @include('patient.today.edit')
@include('patient.today.delete') --}}

{{-- @include('patient.patient_list.edit')
@include('patient.patient_list.delete') --}}


<script type="text/javascript">
    jQuery(function($) {

        // function fetchTotal() {
        //     $.ajax({
        //         url: '{{ route('totalpatients') }}',
        //         type: 'GET',
        //         dataType: 'json',
        //         success: function(response) {
        //             $('#total-value').text(response.total);
        //             $('#total-patient').text(response.total);
        //             $('#total-staff').text(response.totalStaff);
        //             $('#total-doctor').text(response.totalDoctor);
        //             $('#total-other').text(response.totalOthers);

        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error fetching total:', error);
        //         }
        //     });
        // }
        // fetchTotal();

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthMenu: [5, 10, 25, 50],
            ajax: {
                url: '{{ route('patient.today') }}',
                type: "GET",
                dataSrc: function(json) {
                    $('#total-value').text(json
                        .recordsTotal);
                    return json.data;
                }
            },
            columns: [{
                    data: 'token_no',
                    name: 'token_no',
                    className: 'max-w-10'
                },
                {
                    data: 'patient_id',
                    name: 'patient_id'
                },

                {
                    data: 'name',
                    name: 'name',
                    className: 'w-120'
                },
                {
                    data: 'age',
                    name: 'age',
                    className: 'max-w-10'
                },
                {
                    data: 'doctor',
                    name: 'doctor',
                    className: 'w-120'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },

                {
                    data: 'app_time',
                    name: 'app_time',
                    className: 'w-10',
                    render: function(data, type, row) {
                        var timeParts = data.split(':');
                        var hours = parseInt(timeParts[0], 10);
                        var minutes = parseInt(timeParts[1], 10);
                        var ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12; // Handle midnight (00:xx) as 12

                        var formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

                        var formattedTime = hours + ':' + formattedMinutes + ' ' + ampm;

                        return formattedTime;
                    }
                },
                {
                    data: 'app_type',
                    name: 'app_type',
                    className: 'max-w-10',
                    render: function(data, type, row) {
                        if (data === 'NEW') {
                            return '<span class="fa-stack fa-1x text-white fs-10" title="' +
                                data +
                                '"><i class="fas fa-square fa-stack-2x"></i> <i class="fas fa-n fa-stack-1x fa-inverse text-primary"></i></span>';

                        } else {
                            return '<span class="fa-stack fa-1x text-primary fs-10" title="' +
                                data +
                                '"><i class="fas fa-square fa-stack-2x"></i> <i class="fas fa-f fa-stack-1x fa-inverse text-white"></i></span>';
                        }
                    }
                },

                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'w-10',
                    orderable: false,
                    searchable: true
                },
            ]
        });
        $(document).on('click', '.btn-edit', function() {
            var patientId = $(this).data('patient_id');
            $('#edit_patient_id').val(patientId); // Set patient ID in the hidden input
            $.ajax({
                url: '{{ url('patient', '') }}' + "/" + patientId + "/edit",
                method: 'GET',
                success: function(response) {
                    $('#edit_patient_id').val(response.id);
                    $('#edit_patient').val(response.patient);

                    if (response.status === 'Y') {
                        $('#edit_yes').prop('checked', true);
                    } else {
                        $('#edit_no').prop('checked', true);
                    }

                    $('#modal-edit').modal('show');
                },
                error: function(error) {
                    console.log(error)
                }
            });

        });
        $(document).on('click', '.btn-danger', function() {
            var patientId = $(this).data('patient_id');
            $('#delete_patient_id').val(patientId); // Set patient ID in the hidden input
            $('#modal-delete').modal('show');
        });

        $('#btn-confirm-delete').click(function() {
            var departmentId = $('#delete_patient_id').val();
            var url = "";
            url = url.replace(':patient', patientId);

            $.ajax({
                type: 'DELETE',
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    table.draw();

                },
                error: function(xhr) {
                    $('#modal-delete').modal('hide');
                    swal("Error!", xhr.responseJSON.message, "error");
                }
            });
        });

    });
</script>
