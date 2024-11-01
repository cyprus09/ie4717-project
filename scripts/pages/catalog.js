// Function to update the URL with form values upon submission
function updateURLParams(event) {
    event.preventDefault(); // Prevent the form from submitting the traditional way

    const form = event.target; // Get the form that triggered the event
    const formData = new FormData(form);
    const params = new URLSearchParams();

    // Iterate over form data and append it to the URLSearchParams object
    formData.forEach((value, key) => {
        // If the key is for a checkbox group
        if (key.endsWith('[]')) {
            // Remove the brackets from the key for URL parameters
            const cleanKey = key.slice(0, -2); // Remove the '[]' from the key
            params.append(cleanKey, value); // Append the value
        } else {
            params.set(key, value); // Use set to ensure only one value for non-multiple fields
        }
    });

    // Construct the new URL with parameters
    const newURL = `${window.location.pathname}?${params.toString()}`;

    // Navigate to the new URL
    window.location.href = newURL;
}

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

// function fetchProducts(filters) {
//     fetch("../utils/catalog/fetch-prod.php", {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//         },
//         body: JSON.stringify(filters),
//     })
//     .then(response => response.json())
//     .then(products => {
//         populateProductGrid(products);
//     })
//     .catch(error => console.error('Error fetching products:', error));
// }

// function populateProductGrid(products) {
//     const productGrid = document.querySelector('.product-grid');
//     productGrid.innerHTML = ''; // Clear the existing product cards

//     products.forEach(product => {
//         // Create a new element to hold the product card
//         const productCardContainer = document.createElement('div');
//         productCardContainer.innerHTML = `
//             <?php include "../components/product-card.php"; ?>
//         `;

//         // Replace the placeholders in product-card.php with actual product data
//         const productCard = productCardContainer.firstChild;
//         productCard.querySelector('.product-card').href = `../pages/prod-desc.php?id=${product.product_id}`;
//         productCard.querySelector('.product-name').textContent = product.name;
//         productCard.querySelector('.product-price').textContent = `$${parseFloat(product.price).toFixed(2)}`;
        
//         productGrid.appendChild(productCard);
//     });
// }
