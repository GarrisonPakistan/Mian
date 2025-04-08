document.addEventListener('DOMContentLoaded', function () {
    const leaveForm = document.getElementById('leave-application-form');
    const leaveType = document.getElementById('leave-type');
    const leaveFromDate = document.getElementById('leave-from-date');
    const leaveToDate = document.getElementById('leave-to-date');
    const reasonTextarea = document.getElementById('leave-reason');
    const submitButton = document.getElementById('submit-leave');
    const leaveDurationDisplay = document.getElementById('leave-duration');
    const feedbackMessage = document.getElementById('feedback-message');

    // Handle form submission
    leaveForm.addEventListener('submit', async function (e) {
        e.preventDefault(); // Prevent default form submission

        // Collect form data
        const leaveData = new FormData(leaveForm);
        const type = leaveData.get('leave-type');
        const fromDate = leaveData.get('leave-from-date');
        const toDate = leaveData.get('leave-to-date');
        const reason = leaveData.get('leave-reason');

        // Simple validation
        if (!type || !fromDate || !toDate || !reason) {
            alert("All fields are required!");
            return;
        }

        // Show a loading message
        feedbackMessage.textContent = "Submitting your leave application...";
        feedbackMessage.style.color = "blue";

        try {
            // Submit the form data via fetch API
            const response = await fetch('../backend/leave-application.php', {
                method: 'POST',
                body: leaveData
            });

            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                feedbackMessage.textContent = "Your leave application has been submitted successfully!";
                feedbackMessage.style.color = "green";
                leaveForm.reset(); // Reset the form after successful submission
                leaveDurationDisplay.textContent = ""; // Clear the leave duration display
            } else {
                feedbackMessage.textContent = "There was an issue with your leave application.";
                feedbackMessage.style.color = "red";
            }
        } catch (error) {
            console.error('Error:', error);
            feedbackMessage.textContent = "An error occurred. Please try again.";
            feedbackMessage.style.color = "red";
        }
    });

    // Display leave duration when from date and to date are selected
    leaveFromDate.addEventListener('change', updateLeaveDuration);
    leaveToDate.addEventListener('change', updateLeaveDuration);

    function updateLeaveDuration() {
        const fromDate = new Date(leaveFromDate.value);
        const toDate = new Date(leaveToDate.value);

        if (fromDate && toDate && fromDate <= toDate) {
            const duration = Math.ceil((toDate - fromDate) / (1000 * 3600 * 24)) + 1;
            leaveDurationDisplay.textContent = `Duration: ${duration} days`;
        } else {
            leaveDurationDisplay.textContent = "Please select a valid date range.";
        }
    }
});
