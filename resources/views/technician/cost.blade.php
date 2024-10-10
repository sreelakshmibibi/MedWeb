@extends('layouts.dashboard')
@section('title', 'Lab Cost')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div id="successMessage" style="display:none;" class="alert alert-success">
                </div>
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Lab Cost</h3>                    
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body">
                        <form action="{{ route('technicianCost.store') }}" method="POST" id="technicianCostForm">
                            @csrf <!-- Include CSRF token for security -->
                            <div class="row text-end">
                                <div class="col-md-10 mt-4 text-end">
                                </div>
                                <div class="col-md-2 mt-4 text-end">
                                    <input type="text" id="searchInput" class="form-control" placeholder="Search for plans...">
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive" id="planCostFormContainer">
                            
                                <input type="hidden" name="technician_id" value="{{$technicianId}}" id="technician_id">
                                <table id="myTable_planCost" class="table table-bordered table-hover table-striped mb-0 text-center">

                                    <thead>
                                        <tr class="bg-primary-light">
                                            <th style="width:1%;">No</th>
                                            <th style="width:20%;">Plan</th>
                                            <th style="width:11%;">Cost</th>
                                            <!-- <th style="width:10%;">Status</th> -->
                                            <th style="width:10%;">
                                                <button id="costAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-primary">
                                                    <i class="fa fa-add"></i>
                                                    Add Row
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="planCosttablebody">
                                        @forelse ($technicianCost as $index => $cost)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <select class="form-control plan_id_select" id="plan_id{{ $index + 1 }}"
                                                        name="cost[{{ $index + 1 }}][plan_id]" style="width: 100%;">
                                                        <option value=""> Select a plan </option>
                                                        @foreach ($treatmentPlans as $treatmentPlan)
                                                            <option value="{{ $treatmentPlan->id }}"
                                                                {{ $cost->treatment_plan_id == $treatmentPlan->id ? 'selected' : '' }}>
                                                                {{ $treatmentPlan->plan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div id="planError{{ $index + 1 }}" class="invalid-feedback"></div>
                                                </td>
                                                <td>
                                                    <div class="input-group col-12">
                                                        <input type="text" class="form-control text-center" id="cost{{ $index + 1 }}"
                                                            name="cost[{{ $index + 1 }}][cost]" placeholder="Cost"
                                                            value="{{ $cost->cost ?? '' }}">
                                                        <div id="costError{{ $index + 1 }}" class="invalid-feedback"></div>
                                                    </div>
                                                </td>
                                                <!-- <td>
                                                    <select class="form-control" id="status{{ $index + 1 }}" name="cost[{{ $index + 1 }}][status]">
                                                        <option value="Y">Active</option>
                                                        <option value="N">Inactive</option>
                                                    </select>
                                                    <div id="statusError{{ $index + 1 }}" class="invalid-feedback"></div>
                                                </td> -->
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            {{-- Empty state --}}
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script type="text/javascript">
        let rowIndex = '{{ count($technicianCost) + 1 }}';
    
        // Function to add a new row
        $(document).on('click', '#costAddRow', function() {
            const tbody = document.getElementById('planCosttablebody');

            if (!tbody) {
                console.error('No tbody element found.');
                return;
            }

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${rowIndex}</td>
                <td>
                    <select class="form-control" id="plan_id${rowIndex}" name="cost[${rowIndex}][plan_id]" style="width: 100%;">
                        <option value="">Select a Plan</option>
                        @foreach ($treatmentPlans as $treatmentPlan)
                            <option value="{{ $treatmentPlan->id }}">{{ $treatmentPlan->plan }}</option>
                        @endforeach
                    </select>
                    <div id="planError${rowIndex}" class="invalid-feedback"></div>
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" class="form-control" id="cost${rowIndex}" name="cost[${rowIndex}][cost]" placeholder="Cost">
                        <div id="costError${rowIndex}" class="invalid-feedback"></div>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;
                //  <td>
                //     <select class="form-control" id="status${rowIndex}" name="cost[${rowIndex}][status]">
                //         <option value="Y">Active</option>
                //         <option value="N">Inactive</option>
                //     </select>
                //     <div id="statusError${rowIndex}" class="invalid-feedback"></div>
                // </td>
                

            tbody.appendChild(row);

            // Initialize select2 for the new row
            $(`#plan_id${rowIndex}`).select2({
                width: "100%",
                placeholder: "Select a Plan"
            });
            $(`#status${rowIndex}`).select2({
                width: "100%",
                placeholder: "Select Status"
            });

            rowIndex++;

            // Check row count after adding a new row
            checkRowCount();
        });

        // Function to remove a row and renumber the indices
        window.removeRow = function(button) {
            button.closest('tr').remove();
            updateRowIndices(); // Update the row numbers after removing a row
            
            // Check row count after removing a row
            checkRowCount();
        };

        // Function to update the row indices
        function updateRowIndices() {
            const tbody = document.getElementById('planCosttablebody');
            const rows = tbody.querySelectorAll('tr');

            rows.forEach((row, index) => {
                row.cells[0].innerText = index + 1; // Update the index in the first cell
            });

            // Reset rowIndex to the correct value after row removal
            rowIndex = rows.length + 1;
        }

        // Function to check the row count and enable/disable the save button
        function checkRowCount() {
            const tbody = $('#planCosttablebody');
            const rows = tbody ? tbody.find('tr').length : 0;
            const saveButton = $('#saveButton');

            if (saveButton.length) {
                saveButton.prop('disabled', rows === 0); // Enable the save button if there is at least one row
            }
        }

        // Call checkRowCount on page load to set the initial state of the save button
        $(document).ready(function() {
            checkRowCount();
        });
// Search functionality
$('#searchInput').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('#planCosttablebody tr').each(function() {
            const planText = $(this).find('select[name^="cost["] option:selected').text().toLowerCase();
            if (planText.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
        $('#technicianCostForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            let isValid = true;
            const tbody = document.getElementById('planCosttablebody');
            const rows = tbody.querySelectorAll('tr');

            rows.forEach((row, index) => {
                const planSelect = row.querySelector(`select[name="cost[${index + 1}][plan_id]"]`);
                const costInput = row.querySelector(`input[name="cost[${index + 1}][cost]"]`);
                // const statusSelect = row.querySelector(`select[name="cost[${index + 1}][status]"]`);

                // Validate the Plan field
                if (planSelect && !planSelect.value)  {
                    isValid = false;
                    $(planSelect).addClass('is-invalid'); // Highlight the error field
                    $(`#planError${index + 1}`).text('Please select a plan.'); // Show error message
                } else {
                    $(planSelect).removeClass('is-invalid');
                    $(`#planError${index + 1}`).text('');
                }

                // Validate the Cost field
                if (costInput && !costInput.value) {
                    isValid = false;
                    $(costInput).addClass('is-invalid');
                    $(`#costError${index + 1}`).text('Cost is required.');
                } else {
                    $(costInput).removeClass('is-invalid');
                    $(`#costError${index + 1}`).text('');
                }

                // Validate the Status field
                // if (statusSelect && !statusSelect.value) {
                //     isValid = false;
                //     $(statusSelect).addClass('is-invalid');
                //     $(`#statusError${index + 1}`).text('Please select a status.');
                // } else {
                //     $(statusSelect).removeClass('is-invalid');
                //     $(`#statusError${index + 1}`).text('');
                // }
            });

            // Prevent form submission if validation fails or if no rows exist
            if (!isValid || tbody.querySelectorAll('tr').length === 0) {
                return;
            }

            // Serialize the form data
            const formData = $(this).serialize();

            // AJAX call
            $.ajax({
                url: $(this).attr('action'), // URL from form action attribute
                method: 'POST', // You can also use $(this).attr('method') if it is set
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show success message and reset form or take any other actions
                        $('#successMessage').text('Costs saved successfully.').show();
                        // Optionally reset form or update the page
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                            $('#modal-right').modal('hide');
                        $('#technicianCostForm')[0].reset(); 
                        $('#planCosttablebody').empty(); // Clear table rows

                        // Reload the page
                        window.location.reload();
                    } else {
                        // Handle validation errors returned from the server (if any)
                        if (response.errors) {
                            Object.keys(response.errors).forEach(function(key) {
                                const errorDiv = $(`#${key}Error`);
                                errorDiv.text(response.errors[key]);
                                errorDiv.addClass('is-invalid');
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Handle the error here, e.g., show error message
                    console.error('Error:', error);
                    alert('An error occurred while submitting the form. Please try again.');
                }
            });
        });
</script>
@endsection
