// Add event listeners after the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    
    // Listen for form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        updateURLParams();
    });
});

// Function to update the URL with form values upon submission
function updateURLParams(event) {
    event.preventDefault(); // Prevent the form from submitting traditionally
    
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Get search value from navbar search input
    const searchInput = document.querySelector('.search-input');
    const searchValue = searchInput.value.trim();
    
    // Add search parameter if it's not empty
    if (searchValue) {
        params.set('search', searchValue);
    }
    
    // Create an object to store multiple values
    const multiValueParams = {};

    // Collect all form data
    formData.forEach((value, key) => {
        const multipleSet = new Set(['brand', 'category', 'gender']);
        if (multipleSet.has(key)) {
            // Initialize array if it doesn't exist
            if (!multiValueParams[key]) {
                multiValueParams[key] = [];
            }
            // Add value to array
            multiValueParams[key].push(value);
        } else {
            // For single value params (like min-price and max-price)
            params.set(key, value);
        }
    });

    // Add collected multiple values to params with comma separation
    for (const [key, values] of Object.entries(multiValueParams)) {
        if (values.length > 0) {
            params.set(key, values.join(','));
        }
    }

    // Construct the new URL with parameters
    const newURL = `${window.location.pathname}?${params.toString()}`;

    // Navigate to the new URL
    window.location.href = newURL;
}

// Add event listener for form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    form.addEventListener('submit', updateURLParams);
});

// Add an event listener to the form to call the function on submission
document.getElementById('filter-form').addEventListener('submit', updateURLParams);



// function to parse URL params into object
function parseURLParams() {
    const params = new URLSearchParams(window.location.search);
    const parsedParams = {};

    // Iterate over each parameter in the URL
    for (const [key, value] of params.entries()) {
        if (key === 'min-price' || key === 'max-price') {
            // Convert min-price and max-price to numbers
            parsedParams[key] = Number(value);
        } else {
            // Convert comma-separated values into an array for multi-select fields
            parsedParams[key] = value.split(',').map(item => item.toLowerCase());
        }
    }

    return parsedParams;
}

// Populate Filter Form using user selection from URL params
function populateFiltersFromURL() {
    const params = parseURLParams();

    // Populate checkboxes for multi-select fields (brand, category, gender)
    ['brand', 'category', 'gender'].forEach(key => {
        if (params[key]) {
            params[key].forEach(value => {
                const checkbox = document.querySelector(`input[name="${key}"][value="${value.charAt(0).toUpperCase() + value.slice(1)}"]`);
                if (checkbox) checkbox.checked = true;
            });
        }
    });

    // Populate min and max price fields
    if (params['min-price'] !== undefined) {
        document.getElementById('min-price').value = params['min-price'];
    }
    if (params['max-price'] !== undefined) {
        document.getElementById('max-price').value = params['max-price'];
    }
}