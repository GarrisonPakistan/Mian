document.addEventListener('DOMContentLoaded', function () {
    const attendanceForm = document.getElementById('attendance-form');
    const attendanceStatus = document.getElementById('attendance-status');

    // Event listener for attendance status change
    attendanceStatus.addEventListener('change', function () {
        if (attendanceStatus.value === "Absent") {
            alert("Please make sure to mention the reason for absence in the remarks section.");
        }
    });

    // Form submission handling
    attendanceForm.addEventListener('submit', async function (e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Collect form data
        const attendanceData = new FormData(attendanceForm);
        const status = attendanceData.get('attendance-status');
        const date = attendanceData.get('attendance-date');
        const remarks = attendanceData.get('attendance-remarks');

        // Form validation
        if (!status || !date) {
            alert("Attendance date and status are required.");
            return;
        }

        try {
            // Submit the form data via fetch API
            const response = await fetch('../backend/attendance.php', {
                method: 'POST',
                body: attendanceData
            });

            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }

            const result = await response.text();
            alert(result); // Show the server response message

            // Optionally, reset the form after successful submission
            attendanceForm.reset();
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while submitting your attendance. Please try again.');
        }
    });
});
