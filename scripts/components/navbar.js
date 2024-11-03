document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.search-bar');
    const searchInput = document.querySelector('.search-input');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Get the search value and trim whitespace
        const searchValue = searchInput.value.trim();

        // Only redirect if search value is not empty
        if (searchValue !== '') {
            // Construct the URL with the search parameter
            const searchURL = `../pages/catalog.php?search=${encodeURIComponent(searchValue)}`;
            window.location.href = searchURL;
        }
    });
});