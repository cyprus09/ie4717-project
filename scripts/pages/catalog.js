document.getElementById('filter-form').addEventListener('change', updateURLParams);

// Function to Update URL parameters when user provide input on filter forms
function updateURLParams() {
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams(window.location.search);

    // Collecting checkboxes with multiple selections
    ['brand', 'category', 'gender'].forEach(key => {
        const values = formData.getAll(key);
        if (values.length > 0) {
            params.set(key, values.join(',')); // use set to update existing or add new params
        } else {
            params.delete(key); // remove param if no checkbox is selected
        }
    });

    // Collecting min and max price
    const minPrice = formData.get('minPrice');
    const maxPrice = formData.get('maxPrice');
    if (minPrice) {
        params.set('min-price', minPrice);
    } else {
        params.delete('min-price');
    }
    if (maxPrice) {
        params.set('max-price', maxPrice);
    } else {
        params.delete('max-price');
    }

    // Update the current URL without reloading the page
    window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
}

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

function fetchProducts(filters) {
    fetch("../utils/catalog/fetch-prod.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(filters),
    })
    .then(response => response.json())
    .then(products => {
        populateProductGrid(products);
    })
    .catch(error => console.error('Error fetching products:', error));
}

function populateProductGrid(products) {
    const productGrid = document.querySelector('.product-grid');
    productGrid.innerHTML = ''; // Clear the existing product cards

    products.forEach(product => {
        // Create a new element to hold the product card
        const productCardContainer = document.createElement('div');
        productCardContainer.innerHTML = `
            <?php include "../components/product-card.php"; ?>
        `;

        // Replace the placeholders in product-card.php with actual product data
        const productCard = productCardContainer.firstChild;
        productCard.querySelector('.product-card').href = `../pages/prod-desc.php?id=${product.product_id}`;
        productCard.querySelector('.product-name').textContent = product.name;
        productCard.querySelector('.product-price').textContent = `$${parseFloat(product.price).toFixed(2)}`;
        
        productGrid.appendChild(productCard);
    });
}
