<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">Patients List</h4>
        <p class="mb-0 pull-right">Today</p>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center">

                <thead>
                    <tr class="bg-primary-light">
                        <th>No</th>
                        {{-- <th>Date</th> --}}
                        <th>ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>City</th>
                        <th>Gender</th>
                        <th>Doctor</th>
                        {{-- <th>Settings</th> --}}
                        <th width="100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- <tr>
                        <td>01</td>
                        {{-- <td>01/08/2021</td> --}}
                    {{-- }}       <td>DO-124585</td>
                        <td><strong>Shawn Hampton</strong></td>
                        <td>27</td>
                        <td>Miami</td>
                        <td>Male</td>
                        <td>Dr.Samuel</td>
                        <td>
                            <div class="d-flex">
                                <a href="#"
                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                        class="fa fa-pencil"></i></a>
                                <a href="#" class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                        class="fa fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>02</td>
                        {{-- <td>01/08/2021</td> --}}
                    {{-- }}    <td>DO-412577</td>
                        <td><strong>Polly Paul</strong></td>
                        <td>31</td>
                        <td>Naples</td>
                        <td>Female</td>
                        <td>Dr.Geeta</td>
                        <td>
                            <div class="d-flex">
                                <a href="#"
                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                        class="fa fa-pencil"></i></a>
                                <a href="#" class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                        class="fa fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>03</td>
                        {{-- <td>01/08/2021</td> --}}
                    {{-- }}    <td>DO-412151</td>
                        <td><strong>Harmani Doe</strong></td>
                        <td>21</td>
                        <td>Destin</td>
                        <td>Female</td>
                        <td>Dr.Gaya</td>
                        <td>
                            <div class="d-flex">
                                <a href="#"
                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                        class="fa fa-pencil"></i></a>
                                <a href="#" class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                        class="fa fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>04</td>
                        {{-- <td>01/08/2021</td> --}}
                    {{-- }}   <td>DO-123654</td>
                        <td><strong>Mark Wood</strong></td>
                        <td>30</td>
                        <td>Orlando</td>
                        <td>Male</td>
                        <td>Dr.Geeta</td>
                        <td>
                            <div class="d-flex">
                                <a href="#"
                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                        class="fa fa-pencil"></i></a>
                                <a href="#" class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                        class="fa fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>05</td>
                        {{-- <td>01/08/2021</td> --}}
                    {{-- }}     <td>DO-159874</td>
                        <td><strong>Johen Doe</strong></td>
                        <td>58</td>
                        <td>Tampa</td>
                        <td>Male</td>
                        <td>Dr.Raghu</td>
                        <td>
                            <div class="d-flex">
                                <a href="#"
                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                        class="fa fa-pencil"></i></a>
                                <a href="#" class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                        class="fa fa-trash"></i></a>
                            </div>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer bg-light py-10 with-border">
        <div class="d-flex align-items-center justify-content-between">
            <p class="mb-0">Total <span id="total-value"> </span> Patients</p>
            <a type="button" href="{{ route('patient.patient_list') }}"
                class="waves-effect waves-light btn btn-primary">View
                All</a>
        </div>
    </div>
</div>

{{-- @include('patient.today.edit')
@include('patient.today.delete') --}}

@include('patient.patient_list.edit')
@include('patient.patient_list.delete')


<script type="text/javascript">
    jQuery(function($) {

        function fetchTotal() {
            $.ajax({
                url: '{{ route('totalpatients') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#total-patient').text(response.total);
                    $('#total-value').text(response.totalToday);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching total:', error);
                }
            });
        }
        fetchTotal();

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [5, 10, 25, 50],
            ajax: "",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'first_name',
                    name: 'first_name'
                },
                // {
                //     data: 'last_name',
                //     name: 'last_name'
                // },
                {
                    data: 'age',
                    name: 'age'
                },
                {
                    data: 'area',
                    name: 'area'
                },
                {
                    data: 'gender',
                    name: 'gender'
                },

                {
                    data: 'doctor',
                    name: 'doctor'
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
