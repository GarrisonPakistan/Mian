document.addEventListener('DOMContentLoaded', function () {
    const uploadForm = document.getElementById('upload-form');
    const fileInput = document.getElementById('upload-file');
    const feedbackMessage = document.getElementById('feedback-message');

    // Handle form submission
    uploadForm.addEventListener('submit', async function (e) {
        e.preventDefault(); // Prevent default form submission

        // Validate file input
        if (!fileInput.files.length) {
            alert("Please select a file to upload.");
            return;
        }

        const formData = new FormData(uploadForm);

        // Display a loading message
        feedbackMessage.textContent = "Uploading, please wait...";
        feedbackMessage.style.color = "blue";

        try {
            // Submit the form data via fetch API
            const response = await fetch('../backend/upload-documents.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }

            const result = await response.text();

            // Display server response
            feedbackMessage.textContent = result;
            feedbackMessage.style.color = result.includes("success") ? "green" : "red";

            // Reset the form if the upload is successful
            if (result.includes("success")) {
                uploadForm.reset();
            }
        } catch (error) {
            console.error('Error:', error);
            feedbackMessage.textContent = "An error occurred. Please try again.";
            feedbackMessage.style.color = "red";
        }
    });
});
