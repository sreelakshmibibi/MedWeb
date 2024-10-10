$(document).ready(function() {
    var selectedDate;

    $("#attendance_paginator").datepaginator({
        onSelectedDateChanged: function(a, t) {
            selectedDate = moment(t).format("YYYY-MM-DD");
            $('#selected_date').val(selectedDate); // Set the hidden input value
            loadAttendanceData(selectedDate); // Load attendance data for the selected date
        },
    });

    // Initial loading of data
    selectedDate = moment().format("YYYY-MM-DD");
    $('#selected_date').val(selectedDate); // Set the initial value for the hidden input
    loadAttendanceData(selectedDate);

    function loadAttendanceData(date) {
        $.ajax({
            url: attendanceCreateRoute, // Replace with your route
            method: 'POST',
            data: { _token: window.csrfToken, date: date },
            success: function(response) {
                const tbody = $('table tbody');
                tbody.empty(); // Clear existing rows

                if (response.length > 0) {
                    response.forEach((attendance, index) => {
                        tbody.append(`
                            <tr>
                                <td class="text-center">${index + 1}
                                    <input type="hidden" name="user_id[]" value="${attendance.user_id}">
                                </td>
                                <td class="text-center">${attendance.name}</td>
                                <td class="text-center">
                                    <select class="form-control" name="attendance_status[]" onchange="handleAttendanceStatusChange(this)">
                                        <option value="${present}" ${attendance.attendance_status === "1" ? 'selected' : ''}>Present</option>
                                        <option value="${on_leave}" ${attendance.attendance_status === "2" ? 'selected' : ''}>Leave</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="time" class="form-control login-time" name="login_time[]" value="${attendance.login_time}" onchange="calculateWorkedHours(this)" required>
                                </td>
                                <td>
                                    <input type="time" class="form-control logout-time" name="logout_time[]" value="${attendance.logout_time}" onchange="calculateWorkedHours(this)">
                                    <div class="text-danger error-message" style="display:none;"></div>
                                </td>
                                <td>
                                    <input type="text" class="form-control worked-hours" name="worked_hours[]" value="${attendance.worked_hours}" readonly>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="6" class="text-center">No attendance records found.</td></tr>');
                }

                // Reapply the error handling logic
                document.querySelectorAll('select[name="attendance_status[]"]').forEach(function(select) {
                    handleAttendanceStatusChange(select);
                });
            },
            error: function(xhr) {
                console.error("Error loading attendance data:", xhr);
            }
        });
    }

    window.calculateWorkedHours = function(element) {
        const row = element.closest('tr'); // Get the current row
        const loginTime = row.querySelector('.login-time').value;
        const logoutTime = row.querySelector('.logout-time').value;
        const workedHoursField = row.querySelector('.worked-hours');
        const errorMessageField = row.querySelector('.error-message');

        // Clear the error message at the beginning
        errorMessageField.style.display = 'none';
        errorMessageField.textContent = '';

        if (loginTime) {
            const loginParts = loginTime.split(':');
            const loginDate = new Date(1970, 0, 1, loginParts[0], loginParts[1]);

            if (logoutTime) {
                const logoutParts = logoutTime.split(':');
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
                // If logout time is empty, clear worked hours
                workedHoursField.value = '';
            }
        } else {
            workedHoursField.value = ''; // Clear worked hours if login time is empty
        }
    }

    window.handleAttendanceStatusChange = function(element) {
        const row = element.closest('tr');
        const loginTimeField = row.querySelector('.login-time');
        const logoutTimeField = row.querySelector('.logout-time');
        const workedHoursField = row.querySelector('.worked-hours');

        if (element.value == on_leave) {
            // Set values to zero and make fields non-editable
            loginTimeField.value = '00:00';
            logoutTimeField.value = ''; // Make logout time empty
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
});
