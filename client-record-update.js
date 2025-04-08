document.addEventListener('DOMContentLoaded', function () {
    const updateForm = document.getElementById('update-client-form');
    const feedbackMessage = document.getElementById('feedback-message');

    // Event listener for form submission
    updateForm.addEventListener('submit', async function (e) {
        e.preventDefault(); // Prevent default form submission

        // Validate required fields
        const clientName = document.getElementById('client-name').value.trim();
        const clientCnic = document.getElementById('client-cnic').value.trim();

        if (!clientName || !clientCnic) {
            alert("Client Name and CNIC are required fields.");
            return;
        }

        const formData = new FormData(updateForm);

        // Show a loading message while processing
        feedbackMessage.innerHTML = "Updating client record, please wait...";
        feedbackMessage.style.color = "blue";

        try {
            // Submit the form data via fetch API
            const response = await fetch('../backend/client-record-update.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }

            const result = await response.text();
            feedbackMessage.innerHTML = result; // Display server response
            feedbackMessage.style.color = "green";

            // Optionally reset the form after a successful update
            updateForm.reset();
        } catch (error) {
            console.error('Error:', error);
            feedbackMessage.innerHTML = 'An error occurred. Please try again.';
            feedbackMessage.style.color = "red";
        }
    });
});
