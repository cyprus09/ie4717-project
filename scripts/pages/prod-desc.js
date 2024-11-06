document.addEventListener('DOMContentLoaded', function() {
  const sizeSelect = document.getElementById('size-select');
  const quantityInput = document.getElementById('quantity-input');
  const plusBtn = document.querySelector('.quantity-btn.plus');
  const minusBtn = document.querySelector('.quantity-btn.minus');

  // Function to update max quantity based on selected size
  function updateMaxQuantity() {
      const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
      const maxQty = parseInt(selectedOption.dataset.quantity);
      quantityInput.max = maxQty;

      // If current quantity is more than max, adjust it
      if (parseInt(quantityInput.value) > maxQty) {
          quantityInput.value = maxQty;
      }
  }

  // Update max quantity when size changes
  sizeSelect.addEventListener('change', updateMaxQuantity);

  // Handle plus button click
  plusBtn.addEventListener('click', function() {
      const currentValue = parseInt(quantityInput.value);
      const maxValue = parseInt(quantityInput.max);
      if (currentValue < maxValue) {
          quantityInput.value = currentValue + 1;
      }
  });

  // Handle minus button click
  minusBtn.addEventListener('click', function() {
      const currentValue = parseInt(quantityInput.value);
      if (currentValue > 1) {
          quantityInput.value = currentValue - 1;
      }
  });

  // Initialize max quantity for default selected size
  updateMaxQuantity();
});

function addToCart(name, price) {
  const selectedSize = document.querySelector('.size-select').value;
  const selectedQuantity = parseInt(document.querySelector('.quantity-input').value);
  const maxQuantity = parseInt(document.querySelector('.size-select').options[document.querySelector('.size-select').selectedIndex].dataset.quantity);

  // Check if selected quantity is valid
  if (selectedQuantity > maxQuantity) {
      alert(`Sorry, only ${maxQuantity} items available for size UK ${selectedSize}`);
      return;
  }

  fetch("../utils/cart/add-to-cart.php", {
      method: "POST",
      headers: {
          "Content-Type": "application/json",
      },
      body: JSON.stringify({
          name: name,
          price: price,
          quantity: selectedQuantity,
          size: selectedSize
      }),
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert("Product added to cart!");
      } else {
          alert("Failed to add product to cart.");
      }
  })
  .catch(error => {
      console.error('Error:', error);
      alert("An error occurred while adding to cart.");
  });
}