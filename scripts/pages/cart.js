document.addEventListener("DOMContentLoaded", function () {
  const cartItems = document.querySelectorAll(".cart-item");

  cartItems.forEach(item => {
    const minusBtn = item.querySelector(".minus");
    const plusBtn = item.querySelector(".plus");
    const quantityInput = item.querySelector(".quantity-input");
    const index = Array.from(cartItems).indexOf(item);

    // Initialize quantity buttons
    minusBtn.addEventListener("click", () => {
      const currentQuantity = parseInt(quantityInput.value);
      if (currentQuantity > 1) {
        updateQuantity(index, currentQuantity - 1, item);
      }
    });

    plusBtn.addEventListener("click", () => {
      const currentQuantity = parseInt(quantityInput.value);
      if (currentQuantity < 10) {
        updateQuantity(index, currentQuantity + 1, item);
      }
    });
  });

  // Function to update quantity on server
  function updateQuantity(index, quantity, cartItem) {
    const loadingOverlay = document.createElement("div");
    loadingOverlay.className = "loading-overlay";
    cartItem.appendChild(loadingOverlay);

    fetch("../utils/cart/update-quantity.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        index: index,
        quantity: quantity,
      }),
      credentials: "same-origin",
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          cartItem.querySelector(".quantity-input").value = data.newQuantity;
          updateCartTotals();
        } else {
          alert(data.message || "Failed to update quantity");
          // Reset to previous quantity
          cartItem.querySelector(".quantity-input").value = quantity;
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while updating the cart");
        // Reset to previous quantity
        cartItem.querySelector(".quantity-input").value = quantity;
      })
      .finally(() => {
        loadingOverlay.remove();
      });
  }

  // Function to update cart totals
  function updateCartTotals() {
    let subtotal = 0;
    cartItems.forEach(item => {
      const price = parseFloat(item.dataset.price);
      const quantity = parseInt(item.querySelector(".quantity-input").value);
      subtotal += price * quantity;
    });

    const deliveryFee = 15.0;
    const gst = (subtotal + deliveryFee) * 0.1;
    const total = subtotal + deliveryFee + gst;

    document.getElementById("subtotal").textContent = subtotal.toFixed(2);
    document.getElementById("delivery-fee").textContent = deliveryFee.toFixed(2);
    document.getElementById("gst").textContent = gst.toFixed(2);
    document.getElementById("total").textContent = total.toFixed(2);
  }

  // Remove item function
  window.removeItem = function (index) {
    if (confirm("Are you sure you want to remove this item?")) {
      const cartItem = cartItems[index];
      const loadingOverlay = document.createElement("div");
      loadingOverlay.className = "loading-overlay";
      cartItem.appendChild(loadingOverlay);

      fetch("../utils/cart/remove-item.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ index: index }),
        credentials: "same-origin",
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            cartItem.remove();
            updateCartTotals();

            // If cart is empty, show empty cart message
            if (document.querySelectorAll(".cart-item").length === 0) {
              location.reload();
            }
          } else {
            alert(data.message || "Failed to remove item");
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("An error occurred while removing the item");
        })
        .finally(() => {
          loadingOverlay.remove();
        });
    }
  };

  // Initial update of totals
  updateCartTotals();

  // Form Validation
  const checkoutForm = document.querySelector(".checkout-form");

  checkoutForm.addEventListener("submit", event => {
    event.preventDefault();

    const formInputs = {
      address: document.querySelector("input[placeholder='Address']"),
      postalCode: document.querySelector("input[placeholder='Postal Code']"),
      fullName: document.querySelector("input[placeholder='Full Name']"),
      mobile: document.querySelector("input[placeholder='Mobile']"),
      cardName: document.querySelector("input[name='card_name']"),
      cardNumber: document.querySelector("input[placeholder='Card Number']"),
      expiryDate: document.querySelector("input[placeholder='MM/YY']"),
      cvv: document.querySelector("input[placeholder='CVV']"),
      paymentOptions: document.querySelectorAll("input[name='payment']"),
    };

    // Validation checks
    const validations = [
      {
        field: "address",
        check: () => validateAddress(formInputs.address.value),
        message: "Please enter a valid address.",
      },
      {
        field: "postalCode",
        check: () => validatePostalCode(formInputs.postalCode.value),
        message: "Please enter a valid postal code.",
      },
      {
        field: "fullName",
        check: () => validateFullName(formInputs.fullName.value),
        message: "Please enter a valid full name.",
      },
      {
        field: "mobile",
        check: () => validateMobile(formInputs.mobile.value),
        message: "Please enter a valid mobile number.",
      },
      {
        field: "cardNumber",
        check: () => validateCardNumber(formInputs.cardNumber.value),
        message: "Please enter a valid card number.",
      },
      {
        field: "expiryDate",
        check: () => validateExpiryDate(formInputs.expiryDate.value),
        message: "Please enter a valid expiry date (MM/YY).",
      },
      { field: "cvv", check: () => validateCVV(formInputs.cvv.value), message: "Please enter a valid CVV." },
      {
        field: "payment",
        check: () => validatePaymentOption(formInputs.paymentOptions),
        message: "Please select a payment option.",
      },
    ];

    // Check all validations
    let allValidationsPassed = true;
    for (const validation of validations) {
      if (!validation.check()) {
        alert(validation.message);
        allValidationsPassed = false;
        return;
      }
    }

    // If all validations pass, submit the form and redirect
    if (allValidationsPassed) {
      alert("Order placed successfully!");
      checkoutForm.submit();
    }
  });

  // Validation Functions
  function validateAddress(address) {
    return address.trim().length > 5;
  }

  function validatePostalCode(postalCode) {
    const postalCodeRegex = /^[0-9]{5,6}$/;
    return postalCodeRegex.test(postalCode);
  }

  function validateFullName(fullName) {
    return fullName.trim().length > 2;
  }

  function validateMobile(mobile) {
    const mobileRegex = /^[0-9]{8,15}$/;
    return mobileRegex.test(mobile);
  }

  function validateCardNumber(cardNumber) {
    const cardNumberRegex = /^[0-9]{16}$/;
    return cardNumberRegex.test(cardNumber);
  }

  function validateExpiryDate(expiryDate) {
    const expiryDateRegex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
    if (!expiryDateRegex.test(expiryDate)) return false;

    // Additional validation for expiry date
    const [month, year] = expiryDate.split("/");
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear() % 100;
    const currentMonth = currentDate.getMonth() + 1;

    const cardYear = parseInt(year);
    const cardMonth = parseInt(month);

    if (cardYear < currentYear || (cardYear === currentYear && cardMonth < currentMonth)) {
      return false;
    }

    return true;
  }

  function validateCVV(cvv) {
    const cvvRegex = /^[0-9]{3,4}$/;
    return cvvRegex.test(cvv);
  }

  function validatePaymentOption(paymentOptions) {
    return Array.from(paymentOptions).some(option => option.checked);
  }

  // Initialize cart totals
  updateCart();
});
