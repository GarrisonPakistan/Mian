// Wait for the DOM to load completely
document.addEventListener('DOMContentLoaded', function () {
    // Function to toggle between different sections in the dashboard
    const toggleSection = (sectionId) => {
        const sections = document.querySelectorAll('.dashboard-section');
        sections.forEach(section => {
            section.style.display = section.id === sectionId ? 'block' : 'none';
        });
    };

    // Sidebar navigation event listeners
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetSection = e.target.getAttribute('data-section');
            toggleSection(targetSection);
        });
    });

    // Admin login/logout functionality
    const loginBtn = document.querySelector('#admin-login-btn');
    const logoutBtn = document.querySelector('#admin-logout-btn');

    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            alert('You are now logged in as Admin!');
            toggleSection('admin-dashboard');
        });
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            alert('You have been logged out.');
            toggleSection('login-page');
        });
    }

    // Iframe content loading
    const iframe = document.querySelector('#iframe-panel');
    const iframeLinks = document.querySelectorAll('.iframe-link');

    iframeLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const iframeSrc = e.target.getAttribute('data-src');
            iframe.src = iframeSrc;
        });
    });

    // Dynamic content example
    const dynamicInfo = document.querySelector('#dynamic-info');
    if (dynamicInfo) {
        dynamicInfo.innerHTML = '<h3>Welcome, Admin!</h3>';
    }

    // Form submission handling (e.g., employee approval)
    const form = document.querySelector('#employee-approval-form');
    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            alert('Employee approval/rejection has been processed.');
            // Add logic to send data to the server here
        });
    }

    // Confirmation for critical actions (e.g., delete)
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const confirmation = confirm('Are you sure you want to delete this record?');
            if (!confirmation) {
                e.preventDefault();
            }
        });
    });

    // Fetch records dynamically and populate the dashboard
    const fetchRecords = async () => {
        try {
            const response = await fetch('/api/records'); // Placeholder API endpoint
            if (!response.ok) {
                throw new Error(`Error fetching records: ${response.status}`);
            }
            const data = await response.json();
            const recordsContainer = document.querySelector('#records-container');
            if (recordsContainer) {
                recordsContainer.innerHTML = data.records.map(record => `
                    <tr>
                        <td>${record.name}</td>
                        <td>${record.cnic}</td>
                        <td>${record.phone}</td>
                        <td><a href="#" class="delete-btn">Delete</a></td>
                    </tr>
                `).join('');
            }
        } catch (error) {
            console.error('Error fetching records:', error);
        }
    };

    // Fetch records when the dashboard loads
    fetchRecords();
});
