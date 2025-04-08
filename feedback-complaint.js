document.addEventListener('DOMContentLoaded', function () {
    const feedbackForm = document.getElementById('feedback-form');
    const feedbackText = document.getElementById('feedback-text');
    const feedbackMessage = document.getElementById('feedback-message');

    // Event listener for form submission
    feedbackForm.addEventListener('submit', async function (e) {
        e.preventDefault(); // Prevent default form submission

        // Validate feedback content
        const feedbackContent = feedbackText.value.trim();
        if (!feedbackContent) {
            alert("Please provide your feedback or complaint.");
            return;
        }

        const formData = new FormData(feedbackForm);

        // Show a loading message while the form is being processed
        feedbackMessage.innerHTML = "Submitting your feedback...";
        feedbackMessage.style.color = "blue";

        try {
            // Submit the form data via fetch API
            const response = await fetch('../backend/feedback-complaint.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }

            const result = await response.text();
            feedbackMessage.innerHTML = result; // Display server response
            feedbackMessage.style.color = "green";

            // Optionally reset the form after successful submission
            feedbackForm.reset();
        } catch (error) {
            console.error('Error:', error);
            feedbackMessage.innerHTML = 'An error occurred. Please try again.';
            feedbackMessage.style.color = "red";
        }
    });
});
