@extends('layouts.dashboard')
@section('title', 'Staff')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
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
                <div id="successMessage" style="display:none;" class="alert alert-success">
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Staff Details</h3>
                    <div>
                        <a href="{{ route('staff.staff_list.edit', $staffProfile->id) }}"
                            class="waves-effect waves-light btn btn-circle btn-success btn-edit btn-xs me-1"
                            title="edit"><i class="fa fa-pencil"></i></a>


                        <button type="button" class="waves-effect waves-light btn btn-circle btn-warning btn-xs me-1"
                            data-bs-toggle="modal" data-bs-target="#modal-status" data-id="{{ $staffProfile->id }}"
                            title="change status"><i class="fa-solid fa-sliders"></i></button>

                        <a type="button" class="waves-effect waves-light btn btn-circle btn-primary btn-xs" title="back"
                            href="{{ route('staff.staff_list') }}">
                            <i class="fa-solid fa-angles-left"></i></a>
                    </div>
                </div>
            </div>

            <section class="content">
                <!--bio -->
                @include('staff.staff_list.view_part1')

                @if ($userDetails->is_doctor == 1)
                    <!--patients -->
                    @include('staff.staff_list.view_part2')
                @endif

            </section>
        </div>
    </div>
    @include('staff.staff_list.status')
    @include('staff.staff_list.documents')
    <script>
        $(document).ready(function() {
            if ('{{ $userDetails->is_doctor }}' == 1) {
                $('.doctor-section').show();
                $('.staff-section').hide();
            } else {
                $('.staff-section').show();
                $('.doctor-section').hide();
            }

            // Function to fetch and display timings for the selected branch
            function displayTimings(branchId) {
                var branchData = $.grep(@json($availableBranches), function(e) {
                    return e.clinic_branch_id == branchId;
                })[0];
                if (branchData) {
                    updateTimings(branchData); // Update timings on the page
                }
            }

            // Initial display of timings for the first branch in the dropdown
            var initialBranchId = $('#clinic_branch_id1').val();
            displayTimings(initialBranchId);

            // Event handler for when the user selects a different branch
            $('#clinic_branch_id1').on('change', function() {
                var branchId = $(this).val();
                displayTimings(branchId); // Display timings for the selected branch
            });

            function convertTo12HourFormat(time) {
                if (!time) return '';
                return moment(time, 'HH:mm').format('h:mm A');
            }

            // Function to update timings on the page
            function updateTimings(branchData) {
                var daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                var availabilityList = $('#availability-list');
                availabilityList.empty(); // Clear previous timings

                // Loop through each day of the week and update timings
                $.each(daysOfWeek, function(index, day) {
                    var dayName = day.charAt(0).toUpperCase() + day.slice(1); // Capitalize first letter

                    // Convert times to 12-hour format
                    var fromTime = convertTo12HourFormat(branchData.timings[day + '_from']);
                    var toTime = convertTo12HourFormat(branchData.timings[day + '_to']);

                    var timingsHtml = `
                        <div class="media align-items-center justify" style="padding: 0.5rem;">
                            <div class="media-body d-flex justify-content-between">
                                <h6 class="text-muted">${dayName}</h6>
                                <div class="fw-600 min-w-120 text-center">
                                    ${fromTime} - ${toTime}
                                </div>
                            </div>
                        </div>
                    `;

                    availabilityList.append(timingsHtml);
                });
            }

            $(document).on('click', '.btn-warning', function() {
                var staffId = $(this).data('id');
                $('#modal-status').modal('show');
            });
            $('#btn-confirm-status').click(function() {
                var staffId = '{{ $staffProfile->id }}';
                var url = "{{ route('staff.staff_list.changeStatus', [':staffId']) }}";
                url = url.replace(':staffId', staffId);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#successMessage').text('Staff status changed successfully');
                        $('#successMessage').fadeIn().delay(3000)
                            .fadeOut(); // Show for 3 seconds
                        location.reload();
                    },
                    error: function(xhr) {
                        // Handle error response, e.g., hide modal and show error message
                        $('#modal-status').modal('hide');
                        swal("Error!", xhr.responseJSON.message, "error");
                    }
                });
            });
        });
    </script>
@endsection
