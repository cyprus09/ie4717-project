document.addEventListener("DOMContentLoaded", () => {
  // Get references to key elements
  const cartItems = document.querySelectorAll(".cart-item");
  const subtotalElement = document.getElementById("subtotal");
  const gstElement = document.getElementById("gst");
  const totalElement = document.getElementById("total");
  const deliveryFee = 15.0;

  // Quantity buttons
  const quantityButtons = document.querySelectorAll(".quantity-btn");
  quantityButtons.forEach(button => {
    button.addEventListener("click", event => {
      event.preventDefault();
      const input = event.target.parentElement.querySelector(".quantity-input");
      const cartItem = event.target.closest(".cart-item");
      const itemIndex = Array.from(document.querySelectorAll(".cart-item")).indexOf(cartItem);
      let currentValue = parseInt(input.value);

      if (event.target.classList.contains("plus") && currentValue < 10) {
        currentValue += 1;
      } else if (event.target.classList.contains("minus") && currentValue > 1) {
        currentValue -= 1;
      }

      updateQuantity(itemIndex, currentValue, cartItem);
    });
  });

  // Function to update quantity on server
  function updateQuantity(index, quantity, cartItem) {
    fetch("../utils/cart/update-quantity.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        index: index,
        quantity: quantity,
      }),
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          cartItem.querySelector(".quantity-input").value = data.newQuantity;
          updateCart();
        } else {
          alert(data.message || "Failed to update quantity");
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while updating the cart");
      });
  }

  // Remove item function
  window.removeItem = function (index) {
    if (confirm("Are you sure you want to remove this item?")) {
      fetch("../utils/cart/remove-item.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ index: index }),
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert(data.message || "Failed to remove item");
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("An error occurred while removing the item");
        });
    }
  };

  // Function to update the cart's subtotal, GST, and total
  function updateCart() {
    let subtotal = 0;

    cartItems.forEach(item => {
      const price = parseFloat(item.getAttribute("data-price"));
      const quantityInput = item.querySelector(".quantity-input");
      const quantity = parseInt(quantityInput.value);
      const itemTotal = price * quantity;
      subtotal += itemTotal;
    });

    const gst = subtotal * 0.1; // 10% GST
    const total = subtotal + gst + deliveryFee;

    // Update the displayed values
    subtotalElement.textContent = subtotal.toFixed(2);
    gstElement.textContent = gst.toFixed(2);
    totalElement.textContent = total.toFixed(2);
  }

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

    // If all validations pass, show success message and submit the form
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
