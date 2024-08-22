@php
    use Illuminate\Support\Facades\Session;
    $role = session('role');
@endphp
@extends('layouts.dashboard')
@section('title', 'Patient')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <input type="hidden" id="isAdmin" name="isAdmin" value="{{ $role == 'Admin' ? true : false }}">
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Treatment:<span class="fs-20 text-info">
                            <?= Session::get('patientId') ?>- <?= Session::get('patientName') ?>
                        </span>
                    </h3>
                    {{-- <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ route('appointment') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a> --}}
                    <a type="button" class="waves-effect waves-light btn btn-primary" title="Back"
                        href="{{ route('appointment') }}">
                        <span class="hidden-sm-up"><i class="fa-solid fa-angles-left"></i></span>
                        <span class="hidden-xs-down"><i class="fa-solid fa-angles-left"></i> Back</span>
                    </a>

                </div>
                <div id="error-message-container">
                    <p id="error-message"
                        class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: none;"></p>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body wizard-content px-2 pb-0">
                        <form method="post" class="validation-wizard wizard-circle" id="treatmentform"
                            action="{{ route('treatment.details.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Step 1 -->
                            <h6 class="tabHeading">Personal Info</h6>
                            <section class="tabSection">
                                @include('appointment.personal_info')
                            </section>

                            <h6 class="tabHeading">Dental Chart</h6>
                            <section class="tabSection">
                                @include('appointment.dchart_images')
                            </section>

                            <h6 class="tabHeading">Dental Table</h6>
                            <section class="tabSection">
                                @include('appointment.dtable')
                            </section>

                            {{-- <div id="updateRoute" data-url="{{ route('patient.patient_list.update') }}"
                                data-patientlist-route="{{ route('patient.patient_list') }}"></div> --}}
                            <div id="storeRoute" data-url="{{ route('treatment.details.store') }}"
                                data-treatment-details-route="{{ route('appointment') }}"></div>
                            <input type="hidden" name="edit_app_id" id="edit_app_id" value="{{ $appointment->id }}">
                            <input type="hidden" name="edit_patient_id" id="edit_patient_id"
                                value="{{ $patientProfile->id }}">
                        </form>
                    </div>
                    <div class="apphistorydiv" style="display: none;">
                        @include('appointment.history')
                    </div>

                    <div class="prescdiv" style="display: none;">
                        @include('appointment.prescription')
                    </div>
                    <div class="chargediv" style="display:none;">
                        @include('appointment.charge')
                    </div>
                    <!-- /.box-body -->
                </div>
            </section>
        </div>
    </div>

    <script>
        var treatmentShowRoute = "{{ route('treatment.show', ['appointment' => ':appId']) }}";
        var treatmentShowChargeRoute = "{{ route('treatment.showCharge', ['appointment' => ':appId']) }}";
        var pdfTeethRoute = "{{ route('fetch.teeth.details', ['patientId' => ':patientId', 'appId' => ':appId']) }}";
        var appAction = "{{ $appAction }}";
        var currency = "{{ session::get('currency') }}";
        var row1 = "{{ App\Models\TeethRow::Row_1_Desc }}";
        var row2 = "{{ App\Models\TeethRow::Row_2_Desc }}";
        var row3 = "{{ App\Models\TeethRow::Row_3_Desc }}";
        var row4 = "{{ App\Models\TeethRow::Row_4_Desc }}";
        var teethId;
        let isAdmin = $("#isAdmin").val();

        var now = new Date().toISOString().slice(0, 16);
        document.getElementById('appdate').setAttribute('min', now);

        $(document).ready(function() {

            $("#treatmentform .actions ul li:last-child a").addClass("bg-success btn btn-success");

            if (appAction === 'Show') {
                $("#treatmentform .actions ul li:last-child").addClass("disabled").attr("aria-hidden", "true").attr(
                    "aria-disabled", "true").hide();
                $("#treatmentform .actions ul li:last-child a").attr("href", "#").hide();
            }

            // Handle change event for dparts
            $('.dparts').click(function() {
                var partName = this.id;
                var partId = '#' + partName;
                var title = $(this).attr('title');
                var divId = '#' + title;
                var selectId = '#' + title.toLowerCase() + '_condn';

                if ($(partId).hasClass('red')) {
                    $(partId).css({
                        'background-color': 'white',
                    });
                    $(partId).removeClass('red');
                    $(selectId).val('');
                    $(divId).hide();
                } else {
                    $(partId).css({
                        'background-color': 'red',
                    });
                    $(partId).addClass('red');
                    $(divId).show();
                }
                $(partId).toggleClass('selected');
            });

            // $('#tooth_selected').change(function() {
            //     var selectedValue = $(this).val();

            //     // Hide all tooth divs
            //     $('.exam_toothdiv').hide();
            //     $('#incisors_canines').hide();
            //     $('#premolars_molars').hide();

            //     if (selectedValue === 'tooth_in') {
            //         $('.exam_toothdiv').show();
            //         $('#incisors_canines').show();

            //     } else if (selectedValue === 'tooth_mol') {
            //         $('.exam_toothdiv').show();
            //         $('#premolars_molars').show();

            //     }
            // });

            $('#table_info_btn').click(function() {
                if ($('#table_infodiv').css('display') == 'block') {
                    $('#table_infodiv').hide();
                } else {
                    $('#table_infodiv').show();
                }
            });

            // $('#newToothTreatmentBtn').click(function() {
            //     var teethName = $('#tooth_no').val();
            //     var divId = '#div' + teethName;
            //     $(divId).css({
            //         'border': 'none',
            //         'border-radius': '5px',
            //         // 'background-color': 'rgba(0, 0, 255, 0.1)',
            //     });
            //     // $(divId).addClass('overlay');
            // });

            $("#follow_checkbox").change(function() {
                if ($(this).is(':checked')) {
                    // $('#followupdiv').hide();
                    $('#followupdiv').show();
                } else {
                    // $('#followupdiv').show();
                    $('#followupdiv').hide();
                }
            });

            $("#presc_checkbox").on("change", function() {
                var isChecked = $(this).is(":checked");
                handlePrescription(isChecked);
            });


            let count = 1;
            let rowIndex = {{ count($patientPrescriptions) + 1 }};
            $(document).on('click', '#medicineAddRow',
                function() {

                    const tbody = document.getElementById('presctablebody');

                    if (!tbody) {
                        console.error('No tbody element found.');
                        return;
                    }

                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${rowIndex}</td>
                    <td>
                        <select class="form-control" id="medicine_id${rowIndex}" name="prescriptions[${rowIndex}][medicine_id]" style="width: 100%;" required>
                            <option value=""> Select a Medicine </option>
                            @foreach ($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->med_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dose${rowIndex}" name="prescriptions[${rowIndex}][dose]" placeholder="Dose" aria-describedby="dose_unit${rowIndex}" required>
                            <select class="form-control input-group-text" id="dose_unit${rowIndex}" name="prescriptions[${rowIndex}][dose_unit]" required >
                                <option value="" disabled selected>Unit</option>
                                <option value="ml">ml</option>
                                <option value="drops">drops</option>
                                <option value="tab">tab</option>
                                <option value="g">g</option>
                                <option value="mg">mg</option>
                                <option value="cc">cc</option>
                                <option value="pills">pills</option>
                                <option value="units">units</option>
                                <option value="teaspoon">teaspoon</option>
                                <option value="tablespoon">tablespoon</option>
                                <option value="cup">cup</option>
                                <option value="patch">patch</option>
                                <option value="inhaler">inhaler</option>
                                <option value="spray">spray</option>
                                <option value="dropper">dropper</option>
                                <option value="vial">vial</option>
                                <option value="ampule">ampule</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <select class="form-control" id="dosage${rowIndex}" name="prescriptions[${rowIndex}][dosage_id]" required style="width: 100%;">
                            <option value=""> Select a Dosage </option>
                            @foreach ($dosages as $dosage)
                                <option value="{{ $dosage->id }}">{{ $dosage->dos_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" min="1" max="365" class="form-control" id="duration${rowIndex}" name="prescriptions[${rowIndex}][duration]" aria-describedby="basic-addon2" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">days</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <select class="form-control" id="advice${rowIndex}" name="prescriptions[${rowIndex}][advice]" required style="width: 100%;">
                            <option value="After food">After food</option>
                            <option value="Before food">Before food</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" id="route${rowIndex}" name="prescriptions[${rowIndex}][route_id]"  style="width: 100%;">
                            @foreach ($medicineRoutes as $route)
                                <option value="{{ $route->id }}">{{ $route->route_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control text-center" id="remarks${rowIndex}" name="prescriptions[${rowIndex}][remark]" placeholder="remarks">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                `;

                    tbody.appendChild(row);

                    $(`#medicine_id${rowIndex}`).select2({
                        width: "100%",
                        placeholder: "Select a Medicine",
                        tags: true, // Allow user to add new tags (medicines)
                        tokenSeparators: [",", " "], // Define how tags are separated
                        createTag: function(params) {
                            var term = $.trim(params.term);

                            if (term === "") {
                                return null;
                            }

                            // Check if the term already exists as an option
                            var found = false;
                            $(this)
                                .find("option")
                                .each(function() {
                                    if ($.trim($(this).text()) === term) {
                                        found = true;
                                        return false; // Exit the loop early
                                    }
                                });

                            if (!found) {
                                // Return object for new tag
                                return {
                                    id: term,
                                    text: term,
                                    newTag: true, // Add a custom property to indicate it's a new tag
                                };
                            }

                            return null; // If term already exists, return null
                        },
                    });
                    $(`#dosage${rowIndex}`).select2({
                        width: "100%",
                        placeholder: "Select a Dosage",
                    });
                    $(`#advice${rowIndex}`).select2({
                        width: "100%",
                    });
                    $(`#route${rowIndex}`).select2({
                        width: "100%",
                        placeholder: "Select a Route",
                    });
                    rowIndex++;
                });

            window.removeRow = function(button) {
                button.closest('tr').remove();
            };


            let chargecount = 1;
            // Event listener for Add Row button click
            $(document).on('click', '#chargeAddRow', function() {
                chargecount++;
                let newRow = `<tr>
                    <td>${chargecount}</td>
                    <td>
                        <select class="select2" id="treatment_id${chargecount}" name="treatment_id${chargecount}"
                            data-placeholder="Select a Treatment" style="width: 100%;">
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control" id="quantity${chargecount}" name="quantity${chargecount}"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Tooth</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        <button type="button" id="btnchargeDelete" title="delete row"
                            class="waves-effect waves-light btn btn-danger btn-sm btnchargeDelete"> <i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;

                $('#chargetablebody').append(newRow);
                // Reinitialize Select2 on the newly added select element
                $(`#treatment_id${chargecount}`).select2({
                    width: '100%',
                    placeholder: 'Select a Treatment'
                });

                updateRowchargeCount();
            });

            // Event listener for Delete button click
            $(document).on('click', '.btnchargeDelete', function() {
                $(this).closest('tr').remove();
                updateRowchargeCount();
            });

            // Function to update row chargecount input field value
            function updateRowchargeCount() {
                $('#row_chargecount').val(chargecount);
            }



            // Event listener for dropdown item click
            $(".dropdown-menu .dropdown-item").click(function() {
                // Get the selected salutation text
                let salutation = $(this).text().trim();

                // Update the button text with the selected salutation
                $(".input-group .dropdown-toggle").text(salutation);
            });

        });

        $(document).on('click', '.btn-treat-delete', function() {
            var tootExamId = $(this).data('id');
            $('#delete_tooth_exam_id').val(tootExamId); // Set patient ID in the hidden input
            $('#modal-delete').modal('show');
        });

        $(document).on('click', '.btn-treatment-pdf-generate', function() {
            var appId = $(this).data('app-id');
            var parentId = $(this).data('parent-id');
            var patientId = $(this).data('patient-id');

            $('#pdf_appointment_id').val(appId);
            $('#pdf_patient_id').val(patientId);
            $('#pdf_app_parent_id').val(parentId);
            $('#pdfType').val('appointment'); // Default to 'appointment'
            $('#toothSelection').addClass('d-none'); // Hide tooth selection by default
            $('#modal-download').modal('show'); // Show the modal
        });

        $(document).on('click', '.btn-prescription-pdf-generate', function() {
            var appId = $(this).data('app-id');
            var patientId = $(this).data('patient-id');

            const url = '{{ route('download.prescription') }}';

            // Make the AJAX request
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    app_id: appId,
                    patient_id: patientId,
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                xhrFields: {
                    responseType: 'blob' // Important for handling binary data like PDFs
                },
                success: function(response) {
                    var blob = new Blob([response], {
                        type: 'application/pdf'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'prescription.pdf';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    // For printing, open the PDF in a new window or iframe and call print
                    var printWindow = window.open(link.href, '_blank');
                    printWindow.onload = function() {
                        printWindow.print();
                    };
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        // $(document).on('click', '.btn-prescription-print', function() {
        //     var appId = $(this).data('app-id');
        //     var patientId = $(this).data('patient-id');

        //     // Define the URL for the print view
        //     const url = `{{ route('print.prescription') }}?app_id=${appId}&patient_id=${patientId}`;

        //     // Open the print view in a new window
        //     window.open(url, '_blank');
        // });
    </script>

    @include('appointment.teeth')
    @include('appointment.documents')
    @include('appointment.teeth_delete')
    @include('appointment.pdf_option')
@endsection
