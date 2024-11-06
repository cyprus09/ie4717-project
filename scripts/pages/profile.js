// scripts/pages/profile.js
function toggleEdit() {
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    
    const messageContainer = document.getElementById('messageContainer');
    if (messageContainer) {
        messageContainer.innerHTML = '';
    }
    
    document.getElementById('firstNameInput').value = document.getElementById('firstNameValue').textContent.trim();
    document.getElementById('lastNameInput').value = document.getElementById('lastNameValue').textContent.trim();
    document.getElementById('usernameInput').value = document.getElementById('usernameValue').textContent.trim();
    document.getElementById('mobileInput').value = document.getElementById('mobileValue').textContent.trim();

    viewMode.classList.add('hidden');
    editMode.classList.remove('hidden');
}

async function submitChanges() {
    const messageContainer = document.getElementById('messageContainer');
    
    // Get and trim values
    const firstName = document.getElementById('firstNameInput').value.trim();
    const lastName = document.getElementById('lastNameInput').value.trim();
    const username = document.getElementById('usernameInput').value.trim();
    const mobile = document.getElementById('mobileInput').value.trim();

    // Client-side validation
    const nameRegex = /^[A-Za-z\s]+$/;  // Allow letters and spaces
    const numbersOnly = /^[0-9]{8}$/;

    // Validate First Name
    if (!nameRegex.test(firstName)) {
        messageContainer.innerHTML = '<div class="error-message">First Name must contain only alphabets and spaces</div>';
        return;
    }

    // Validate Last Name
    if (!nameRegex.test(lastName)) {
        messageContainer.innerHTML = '<div class="error-message">Last Name must contain only alphabets and spaces</div>';
        return;
    }

    // Validate Mobile Number
    if (!numbersOnly.test(mobile)) {
        messageContainer.innerHTML = '<div class="error-message">Mobile Number must be exactly 8 digits</div>';
        return;
    }

    const userData = {
        firstName,
        lastName,
        username,
        mobile
    };

    try {
        const response = await fetch('../utils/profile/update-profile.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(userData)
        });

        const result = await response.json();

        if (result.success) {
            // Update the view with new values
            document.getElementById('firstNameValue').textContent = userData.firstName;
            document.getElementById('lastNameValue').textContent = userData.lastName;
            document.getElementById('usernameValue').textContent = userData.username;
            document.getElementById('mobileValue').textContent = userData.mobile;

            // Switch back to view mode
            document.getElementById('viewMode').classList.remove('hidden');
            document.getElementById('editMode').classList.add('hidden');

            messageContainer.innerHTML = `<div class="success-message">${result.message}</div>`;
        } else {
            messageContainer.innerHTML = `<div class="error-message">${result.message}</div>`;
        }
    } catch (error) {
        messageContainer.innerHTML = '<div class="error-message">An error occurred while updating the profile</div>';
    }
}