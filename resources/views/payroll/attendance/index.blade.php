@extends('layouts.dashboard')
@section('title', 'Attendance')
@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <h3 class="page-title">Today's Attendance</h3>
        </div>

        <section class="content">
            <form method="post" action="{{ route('attendance.store') }}" id="attendanceForm">
                @csrf
                <div id="formError" class="text-danger" style="display: none; margin-bottom: 1rem;"></div> <!-- Error message container -->
                <div class="box">
                    <div class="box-body">
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
                                        <?php $i = 1; ?>
                                        @forelse ($usersWithAttendance as $attendance)
                                            <tr>
                                                <td class="text-center">{{ $i++ }}
                                                    <input type="hidden" name="user_id[]" value="{{ $attendance['user_id'] }}">
                                                </td>
                                                <td class="text-center">{{ $attendance['name'] }}</td>
                                                <td class="text-center">
                                                    <select class="form-control" name="attendance_status[]" onchange="handleAttendanceStatusChange(this)">
                                                        <option value="{{ App\Models\EmployeeAttendance::PRESENT }}" {{ $attendance['attendance_status'] == App\Models\EmployeeAttendance::PRESENT ? 'selected' : '' }}>Present</option>
                                                        <option value="{{ App\Models\EmployeeAttendance::ON_LEAVE }}" {{ $attendance['attendance_status'] == App\Models\EmployeeAttendance::ON_LEAVE ? 'selected' : '' }}>Leave</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control login-time" name="login_time[]" value="{{ $attendance['login_time'] }}" onchange="calculateWorkedHours(this)" required>
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control logout-time" name="logout_time[]" value="{{ $attendance['logout_time'] }}" onchange="calculateWorkedHours(this)" required>
                                                    <div class="text-danger error-message" style="display:none;"></div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control worked-hours" name="worked_hours[]" value="{{ $attendance['worked_hours'] }}" readonly>
                                                </td>
                                            </tr>
                                            
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No attendance records found.</td>
                                            </tr>
                                        @endforelse
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

<script type="text/javascript">
    function calculateWorkedHours(element) {
        const row = element.closest('tr'); // Get the current row
        const loginTime = row.querySelector('.login-time').value;
        const logoutTime = row.querySelector('.logout-time').value;
        const workedHoursField = row.querySelector('.worked-hours');
        // Get the next row for the error message
        const errorMessageField =  row.querySelector('.error-message');
    
        // Clear the error message at the beginning
        errorMessageField.style.display = 'none';
        errorMessageField.textContent = '';

        if (loginTime && logoutTime) {
            const loginParts = loginTime.split(':');
            const logoutParts = logoutTime.split(':');

            const loginDate = new Date(1970, 0, 1, loginParts[0], loginParts[1]);
            const logoutDate = new Date(1970, 0, 1, logoutParts[0], logoutParts[1]);

            if (loginDate >= logoutDate) {
                // Show error message
                errorMessageField.textContent = 'Logout time must be after Login time.';
                errorMessageField.style.display = 'block';
                workedHoursField.value = 'Invalid Time';
                return;
            }

            const diffMs = logoutDate - loginDate;
            const totalSeconds = Math.floor(diffMs / 1000);
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);

            workedHoursField.value = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
        } else {
            workedHoursField.value = '';
        }
    }

    function handleAttendanceStatusChange(element) {
        const row = element.closest('tr');
        const loginTimeField = row.querySelector('.login-time');
        const logoutTimeField = row.querySelector('.logout-time');
        const workedHoursField = row.querySelector('.worked-hours');

        if (element.value === '{{ App\Models\EmployeeAttendance::ON_LEAVE }}') {
            // Set values to zero and make fields non-editable
            loginTimeField.value = '00:00';
            logoutTimeField.value = '00:00';
            workedHoursField.value = '00:00:00';

            loginTimeField.setAttribute('readonly', 'readonly');
            logoutTimeField.setAttribute('readonly', 'readonly');
        } else {
            // Remove read-only attributes if the user is present
            loginTimeField.removeAttribute('readonly');
            logoutTimeField.removeAttribute('readonly');
        }
    }

    function hasErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        return Array.from(errorMessages).some(error => error.style.display === 'block');
    }

    document.querySelector('#attendanceForm').addEventListener('submit', function(event) {
        const formError = document.querySelector('#formError');
        formError.style.display = 'none'; // Hide previous error message
        formError.textContent = ''; // Clear previous error message text
        
        if (hasErrors()) {
            event.preventDefault(); // Prevent form submission
            formError.textContent = 'Please fix the errors before submitting the form.'; // Set error message
            formError.style.display = 'block'; // Show error message
        }
    });


    document.querySelectorAll('select[name="attendance_status[]"]').forEach(function(select) {
        handleAttendanceStatusChange(select);
    });
</script>

@endsection
