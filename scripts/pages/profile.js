// profile.js
async function submitChanges() {
    const messageContainer = document.getElementById('messageContainer');
    
    const userData = {
        firstName: document.getElementById('firstNameInput').value.trim(),
        lastName: document.getElementById('lastNameInput').value.trim(),
        username: document.getElementById('usernameInput').value.trim(),
        mobile: document.getElementById('mobileInput').value.trim()
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
            // Update the view with new values (excluding email)
            document.getElementById('firstNameValue').textContent = userData.firstName;
            document.getElementById('lastNameValue').textContent = userData.lastName;
            document.getElementById('usernameValue').textContent = userData.username;
            document.getElementById('mobileValue').textContent = userData.mobile;

            // Hide edit mode
            document.getElementById('editMode').classList.remove('display');
            document.getElementById('editMode').classList.add('hidden');

            // Show view mode
            document.getElementById('viewMode').classList.remove('hidden');
            document.getElementById('viewMode').classList.add('display');

            // Show success message
            messageContainer.innerHTML = `<div class="success-message">${result.message}</div>`;
        } else {
            // Show error message
            messageContainer.innerHTML = `<div class="error-message">${result.message}</div>`;
        }
    } catch (error) {
        messageContainer.innerHTML = '<div class="error-message">An error occurred while updating the profile</div>';
    }
}

function toggleEdit() {
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    
    const messageContainer = document.getElementById('messageContainer');
    if (messageContainer) {
        messageContainer.innerHTML = '';
    }
    
    // Populate input fields (excluding email)
    document.getElementById('firstNameInput').value = document.getElementById('firstNameValue').textContent.trim();
    document.getElementById('lastNameInput').value = document.getElementById('lastNameValue').textContent.trim();
    document.getElementById('usernameInput').value = document.getElementById('usernameValue').textContent.trim();
    document.getElementById('mobileInput').value = document.getElementById('mobileValue').textContent.trim();

    // Hide information section
    viewMode.classList.remove('display');
    viewMode.classList.add('hidden');

    // Show edit form
    editMode.classList.remove('hidden');
    editMode.classList.add('display');
}