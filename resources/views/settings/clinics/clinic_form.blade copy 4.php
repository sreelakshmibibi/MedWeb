<!-- alert -->
<div id="successMessage" style="display:none;" class="alert alert-success">
</div>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="d-flex align-items-center justify-content-between">
        {{-- <h3 class="page-title">Clinic Branches</h3> --}}
        <h3></h3>
        <button type="button" class="waves-effect waves-light btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#modal-right"> <i class="fa fa-add"></i> Add New</button>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="row mx-0">
        <div class="box">
            <div class="box-body">
                <div class="table-responsive">
                    <!-- Main content -->
                    <table id="cbranch_table" class="table table-bordered table-hover table-striped mb-0 data-table"
                        width="100%">
                        <thead class="bg-primary-light text-center">
                            <tr>
                                <th width="10px">No</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Is Medicine Provided?</th>
                                <th width="20px">Status</th>
                                <th width="80px">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <!-- /.content -->
                </div>

            </div>
            <!-- /.box -->
        </div>
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->


<!-- modal -->
@include('settings.clinics.create')
@include('settings.clinics.edit')
@include('settings.clinics.delete')

<script type="text/javascript">
    var table;
    jQuery(function($) {
        table = $('#cbranch_table').DataTable({
            deferRender: true,
            columnDefs: [{
                    width: '10px',
                    targets: 0
                },
                {
                    width: "20px",
                    targets: 4
                },
                {
                    width: "80px",
                    targets: 5
                }
            ],
            fixedColumns: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('settings.clinic') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '10px',
                    className: 'text-center',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        // Return the row index (starts from 0)
                        return meta.row + 1; // Adding 1 to start counting from 1
                    }
                },
                {
                    data: 'clinic_phone',
                    name: 'clinic_phone',
                    className: 'text-center'
                },
                {
                    data: 'clinic_address',
                    name: 'clinic_address',
                    className: 'text-left'
                },
                {
                    data: 'is_medicine_provided',
                    name: 'is_medicine_provided',
                    className: 'text-center'
                },
                // {
                //     data: 'clinic_status',
                //     name: 'clinic_status',
                //     className: 'text-center'
                // },
                {
                    data: 'status',
                    name: 'status',
                    width: '20px',
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    width: '80px',
                    className: 'text-center',
                    orderable: false,
                    searchable: true
                },
            ],
            initComplete: function(settings, json) {
                // this.api().columns.adjust().draw();
                table.columns.adjust().draw();
            },
            drawCallback: function(settings) {
                table.columns.adjust().draw();
                $('#cbranch_table').css('visibility', 'visible');
            }
        });

        $(document).on('click', '.btn-edit', function() {
            var clinicId = $(this).data('id');
            $('#edit_clinic_id').val(clinicId); // Set department ID in the hidden input
            $.ajax({
                url: '{{ url('clinic') }}' + "/" + clinicId + "/edit",
                method: 'GET',
                success: function(response) {
                    $('#edit_clinic_id').val(response.id);
                    $('#edit_clinic_name').val(response.clinic_name);
                    $('#edit_clinic_email').val(response.clinic_email);
                    $('#edit_clinic_phone').val(response.clinic_phone);
                    $('#edit_clinic_website').val(response.clinic_website);
                    $('#edit_yes').prop('checked', response.is_main_branch === 'Y');
                    $('#edit_no').prop('checked', response.is_main_branch === 'N');
                    $('#edit_medicine_yes').prop('checked', response
                        .is_medicine_provided === 'Y');
                    $('#edit_medicine_no').prop('checked', response.is_medicine_provided ===
                        'N');
                    $('#edit_clinic_country').val(response.country_id);
                    let addressParts = response.clinic_address.split("<br>");
                    $('#edit_clinic_address1').val(addressParts[0]);
                    $('#edit_clinic_address2').val(addressParts[1]);
                    $('#edit_clinic_pincode').val(response.pincode);
                    // $('#edit_clinic_logo').val(response.clinic_logo);
                    if (response.clinic_logo) {
                        var logoUrl = '{{ asset('storage/') }}/' + response.clinic_logo;
                        console.log(logoUrl);
                        $('#currentClinicLogoImg').attr('src', logoUrl);
                        $('#currentClinicLogoImg').show(); // Show the image element
                    } else {
                        $('#currentClinicLogoImg').attr('src', ''); // Clear src if no logo
                        $('#currentClinicLogoImg').hide(); // Hide image element
                    }
                    // Load states based on selected country (assuming you have this function)
                    loadStates(response.country_id);
                    $('#edit_clinic_state').val(response.state_id);
                    loadCitiesEdit(response.state_id, response.city_id);
                    // Set selected state after a short delay to ensure options are loaded

                    $('#modal-edit-clinic').modal('show');
                },
                error: function(error) {

                    console.log(error);
                }
            });
        });

        // $(document).on('click', '.btn-danger', function() {
        $(document).on('click', '.btn-status', function() {
            var clinicId = $(this).data('id');
            var status = $(this).data('status');
            var statusChange = "ACTIVATE";
            var confirmText = "Are you sure you want to activate the clinic?";
            var buttonText = "Activate";
            if (status == 'Y') {
                statusChange = "DEACTIVATE";
                confirmText = "Are you sure you want to deactivate the clinic?";
                buttonText = "DeActivate";
            }

            $('#delete_clinic_id').val(clinicId);
            $('#delete_clinic_status').val(status);
            $('#statusChange').text(statusChange);
            $('#confirmText').text(confirmText);
            $('btn-confirm-delete').text(buttonText);
            $('#modal-delete-clinic').modal('show');

        });

        $('#btn-confirm-delete').click(function() {
            var clinicId = $('#delete_clinic_id').val();
            var status = $('#delete_clinic_status').val();
            var url = "{{ route('settings.clinic.destroy', [':clinic', ':status']) }}";
            url = url.replace(':clinic', clinicId);
            url = url.replace(':status', status);

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    var statusText = status == 'Y' ? 'Clinic deactivated successfully' :
                        'Clinic activated successfully';
                    $('#successMessage').text(statusText);
                    $('#successMessage').fadeIn().delay(3000)
                        .fadeOut(); // Show for 3 seconds
                    table.draw(); // Refresh DataTable
                },
                error: function(xhr) {
                    $('#modal-delete-clinic').modal('hide');
                    swal("Error!", xhr.responseJSON.message, "error");
                }
            });
        });
    });

    // Function to load states based on country ID
    function loadStates(countryId) {
        if (countryId) {
            $.ajax({
                url: '{{ route('get.states', '') }}' + '/' + countryId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#clinic_state').empty();
                    $('#clinic_state').append('<option value="">Select State</option>');
                    $.each(data, function(key, value) {
                        $('#clinic_state').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    var initialStateId = $('#clinic_state').val();

                }
            });
        } else {
            $('#clinic_state').empty();
        }
    }

    // Function to load cities based on state ID
    function loadCitiesEdit(stateId, cityId) {
        if (stateId) {
            $.ajax({
                url: '{{ route('get.cities', '') }}' + '/' + stateId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#edit_clinic_city').empty();
                    $.each(data, function(key, value) {
                        var selected = "";
                        if (key == cityId) {
                            selected = "selected";
                        }
                        $('#edit_clinic_city').append('<option value="' + key + '" ' + selected +
                            '>' + value + '</option>');
                    });
                }
            });
        } else {
            $('#clinic_city').empty();
            $('#edit_clinic_city').append('<option value="">Select City</option>');
        }
    }

    // Function to validate email format
    // function isValidEmail(email) {
    //     // You can implement your own email validation logic here
    //     var re = /\S+@\S+\.\S+/;
    //     return re.test(email);
    // }

    // Function to validate URL format
    function isValidUrl(url) {
        // You can implement your own URL validation logic here
        var re = /^(ftp|http|https):\/\/[^ "]+$/;
        return re.test(url);
    }

    // Function to validate pin code format
    function isValidPincode(pincode) {
        // You can implement your own pin code validation logic here
        var re = /^\d{6}$/;
        return re.test(pincode);
    }
</script>
