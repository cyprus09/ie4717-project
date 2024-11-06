// scripts/pages/profile.js
function toggleEdit() {
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    
    // Remove any existing messages
    const messageContainer = document.getElementById('messageContainer');
    if (messageContainer) {
        messageContainer.innerHTML = '';
    }
    
    // Populate input fields with current values (trim to remove any whitespace)
    document.getElementById('emailInput').value = document.getElementById('emailValue').textContent.trim();
    document.getElementById('firstNameInput').value = document.getElementById('firstNameValue').textContent.trim();
    document.getElementById('lastNameInput').value = document.getElementById('lastNameValue').textContent.trim();
    document.getElementById('usernameInput').value = document.getElementById('usernameValue').textContent.trim();
    document.getElementById('mobileInput').value = document.getElementById('mobileValue').textContent.trim();

    viewMode.classList.add('hidden');
    editMode.classList.remove('hidden');
}

async function submitChanges() {
    const messageContainer = document.getElementById('messageContainer');
    
    // Get form values and trim whitespace
    const userData = {
        email: document.getElementById('emailInput').value.trim(),
        firstName: document.getElementById('firstNameInput').value.trim(),
        lastName: document.getElementById('lastNameInput').value.trim(),
        username: document.getElementById('usernameInput').value.trim(),
        mobile: document.getElementById('mobileInput').value.trim()
    };

    // Basic client-side validation
    if (!userData.email || !userData.firstName || !userData.lastName || !userData.username || !userData.mobile) {
        messageContainer.innerHTML = '<div class="error-message">All fields are required</div>';
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(userData.email)) {
        messageContainer.innerHTML = '<div class="error-message">Please enter a valid email address</div>';
        return;
    }

    // Mobile number validation
    if (!/^[0-9]{8}$/.test(userData.mobile)) {
        messageContainer.innerHTML = '<div class="error-message">Mobile number must be 8 digits</div>';
        return;
    }

    try {
        // Adjust the path to go up two levels from pages to reach utils
        const response = await fetch('../../utils/profile/update_profile.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(userData)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            // Update the view with new values
            document.getElementById('emailValue').textContent = userData.email;
            document.getElementById('firstNameValue').textContent = userData.firstName;
            document.getElementById('lastNameValue').textContent = userData.lastName;
            document.getElementById('usernameValue').textContent = userData.username;
            document.getElementById('mobileValue').textContent = userData.mobile;

            // Switch back to view mode
            document.getElementById('viewMode').classList.remove('hidden');
            document.getElementById('editMode').classList.add('hidden');

            // Show success message
            messageContainer.innerHTML = `<div class="success-message">${result.message}</div>`;
        } else {
            // Show error message
            messageContainer.innerHTML = `<div class="error-message">${result.message}</div>`;
        }
    } catch (error) {
        console.error('Error:', error);
        messageContainer.innerHTML = '<div class="error-message">An error occurred while updating the profile</div>';
    }
}