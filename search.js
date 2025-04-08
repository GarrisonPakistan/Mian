// search.js
document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-form');
    const otpSection = document.getElementById('otp-section');
    const verifyOtpBtn = document.getElementById('verify-otp-btn');
    const otpInput = document.getElementById('otp-code');

    // Handle Search Form Submission (Send OTP)
    searchForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();

        // Validate input fields
        if (!email || !phone) {
            alert("Please provide both email and phone number.");
            return;
        }

        try {
            // Send OTP request to backend
            const response = await fetch('../backend/otp-verification.php', {
                method: 'POST',
                body: JSON.stringify({ email: email, phone: phone }),
                headers: { 'Content-Type': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }

            const data = await response.json();

            if (data.status === 'success') {
                otpSection.style.display = 'block'; // Show OTP verification form
                alert('OTP sent! Please enter the OTP to proceed.');
            } else {
                alert(data.message || 'Error sending OTP.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while sending the OTP. Please try again.');
        }
    });

    // Handle OTP Verification
    verifyOtpBtn.addEventListener('click', function () {
        const enteredOtp = otpInput.value.trim();

        // Validate OTP input
        if (!enteredOtp) {
            alert("Please enter the OTP.");
            return;
        }

        // Simulate OTP verification (replace with backend verification in production)
        if (enteredOtp === '111') {
            alert("OTP verified successfully.");
            window.location.href = "search-results.html"; // Redirect to the record search page
        } else {
            alert("Incorrect OTP. Please try again.");
        }
    });
});
