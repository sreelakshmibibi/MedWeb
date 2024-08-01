<?php

use Illuminate\Support\Facades\Session;
?>
@extends('layouts.dashboard')
@section('title', 'Patient')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Treatment : <?= Session::get('patientName') ?> ( <?= Session::get('patientId') ?>
                        )</h3>
                    <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ route('appointment') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a>
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
                            <?php if ($latestAppointment != 0) { ?>
                            {{-- <h6 class="tabHeading">Appointment History</h6>
                            <section class="tabSection">
                                @include('appointment.history')
                            </section> --}}
                            <?php } ?>


                            <h6 class="tabHeading">Dental Chart</h6>
                            <section class="tabSection">
                                @include('appointment.dchart_images')
                            </section>

                            {{-- <h6 class="tabHeading">Examination</h6>
                            <section class="tabSection">
                                @include('appointment.examination')
                            </section> --}}

                            <h6 class="tabHeading">Dental Table</h6>
                            <section class="tabSection">
                                @include('appointment.dtable')
                                {{-- @include('appointment.teeth_delete') --}}
                            </section>

                            {{-- <h6 class="tabHeading">Prescription</h6>
                            <section class="tabSection">
                                @include('appointment.prescription')
                            </section> --}}

                            {{-- <h6 class="tabHeading">Charge</h6>
                            <section class="tabSection">
                                @include('appointment.charge')
                            </section> --}}

                            {{-- <h6 class="tabHeading">Chart</h6>
                            <section class="tabSection">
                                @include('appointment.dchart')
                            </section> --}}


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
        var teethId;
        let isAdmin = $("#isAdmin").val();
        $(document).ready(function() {

            $("#treatmentform .actions ul li:last-child a").addClass("bg-success btn btn-success");

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

            // Event listener for Add Row button click
            // $(document).on('click', '#medicineAddRow', function() {
            //     count++;
            //     let newRow = `<tr>
        //         <td>${count}</td>
        //         <td>
        //             <select class="select2" id="medicine_id${count}" name="medicine_id${count}" required
        //                 data-placeholder="Select a Medicine" style="width: 100%;">
        //                     <option value=""> Select a Medicine </option>
        //                     <?php foreach ( $medicines as $medicine ) { ?>
        //                     <option value="{{ $medicine->id }}"> {{ $medicine->med_name }}</option>
        //                     <?php } ?>
        //             </select>
        //         </td>
        //         <td>
        //             <select class="select2" id="dosage${count}" name="dosage${count}" required
        //                 data-placeholder="Select a Dosage" style="width: 100%;">
        //                 <option value=""> Select a Dosage </option>
        //                 <?php foreach ( $dosages as $dosage ) { ?>
        //                     <option value="{{ $dosage->id }}"> {{ $dosage->dos_name }}</option>
        //                 <?php } ?>
        //             </select>
        //         </td>
        //         <td>
        //             <div class="input-group">
        //                 <input type="number" class="form-control" id="duration${count}" name="duration${count}" aria-describedby="basic-addon2">
        //                 <div class="input-group-append">
        //                     <span class="input-group-text" id="basic-addon2">days</span>
        //                 </div>
        //             </div>
        //         </td>
        //         <td>
        //             <select class="select2" id="advice${count}" name="advice${count}" required class="form-control"
        //                  style="width: 100%;">
        //                     <option value="After food">After food</option>
        //                    <option value="Before food">Before food</option>
        //             </select>
        //         </td>
        //         <td>
        //             <input type="text" class="form-control" id="remarks${count}" name="remarks${count}" placeholder="remarks">
        //         </td>
        //         <td>
        //             <button type="button" class="btnDelete waves-effect waves-light btn btn-danger btn-sm"
        //                     title="delete row"> <i class="fa fa-trash"></i></button>
        //         </td>
        //     </tr>`;

            //     $('#presctablebody').append(newRow);
            //     // Reinitialize Select2 on the newly added select element
            //     $(`#medicine_id${count}`).select2({
            //         width: '100%',
            //         placeholder: 'Select a Medicine',
            //         tags: true, // Allow user to add new tags (medicines)
            //         tokenSeparators: [',', ' '], // Define how tags are separated
            //         createTag: function(params) {
            //             var term = $.trim(params.term);

            //             if (term === '') {
            //                 return null;
            //             }

            //             // Check if the term already exists as an option
            //             var found = false;
            //             $(this).find('option').each(function() {
            //                 if ($.trim($(this).text()) === term) {
            //                     found = true;
            //                     return false; // Exit the loop early
            //                 }
            //             });

            //             if (!found) {
            //                 // Return object for new tag
            //                 return {
            //                     id: term,
            //                     text: term,
            //                     newTag: true // Add a custom property to indicate it's a new tag
            //                 };
            //             }

            //             return null; // If term already exists, return null
            //         }
            //     });
            //     $(`#dosage${count}`).select2({
            //         width: '100%',
            //         placeholder: 'Select a Dosage'
            //     });
            //     $(`#advice${count}`).select2({
            //         width: '100%',
            //     });
            //     updateRowCount();
            // });

            // Event listener for Delete button click
            // $(document).on('click', '.btnDelete', function() {
            //     $(this).closest('tr').remove();
            //     updateRowCount();
            // });

            // Function to update row count input field value
            // function updateRowCount() {
            //     $('#row_count').val(count);
            // }



            let rowIndex = {{ count($patientPrescriptions) + 1 }};
            $(document).on('click', '#medicineAddRow', function() {

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
                        <select class="form-control" id="dosage${rowIndex}" name="prescriptions[${rowIndex}][dosage_id]" required style="width: 100%;">
                            <option value=""> Select a Dosage </option>
                            @foreach ($dosages as $dosage)
                                <option value="{{ $dosage->id }}">{{ $dosage->dos_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control" id="duration${rowIndex}" name="prescriptions[${rowIndex}][duration]" aria-describedby="basic-addon2" required>
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
                        <input type="text" class="form-control" id="remarks${rowIndex}" name="prescriptions[${rowIndex}][remark]" placeholder="remarks">
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
    </script>

    @include('appointment.teeth')
    @include('appointment.documents')
    @include('appointment.teeth_delete')
@endsection
