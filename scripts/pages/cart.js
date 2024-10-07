document.addEventListener("DOMContentLoaded", () => {
  // Quantity buttons
  const quantityButtons = document.querySelectorAll(".quantity-btn");
  quantityButtons.forEach(button => {
    button.addEventListener("click", function () {
      const input = this.parentElement.querySelector("input[type='number']");
      let currentValue = parseInt(input.value);

      if (this.classList.contains("plus")) {
        if (currentValue < 10) {
          input.value = currentValue + 1;
        }
      } else if (this.classList.contains("minus")) {
        if (currentValue > 1) {
          input.value = currentValue - 1;
        }
      }
    });
  });

  // Form Validation
  const checkoutForm = document.querySelector(".checkout-form");

  checkoutForm.addEventListener("submit", event => {
    // Prevent form submission for validation
    event.preventDefault();

    const address = document.querySelector("input[placeholder='Address']");
    const postalCode = document.querySelector("input[placeholder='Postal Code']");
    const fullName = document.querySelector("input[placeholder='Full Name']");
    const mobile = document.querySelector("input[placeholder='Mobile']");
    const cardNumber = document.querySelector("input[placeholder='Card Number']");
    const expiryDate = document.querySelector("input[placeholder='Expiry Date']");
    const cvv = document.querySelector("input[placeholder='CVV']");
    const paymentOptions = document.querySelectorAll("input[name='payment']");

    // Validations
    if (!validateAddress(address.value)) {
      alert("Please enter a valid address.");
      return;
    }

    if (!validatePostalCode(postalCode.value)) {
      alert("Please enter a valid postal code.");
      return;
    }

    if (!validateFullName(fullName.value)) {
      alert("Please enter a valid full name.");
      return;
    }

    if (!validateMobile(mobile.value)) {
      alert("Please enter a valid mobile number.");
      return;
    }

    if (!validateCardNumber(cardNumber.value)) {
      alert("Please enter a valid card number.");
      return;
    }

    if (!validateExpiryDate(expiryDate.value)) {
      alert("Please enter a valid expiry date (MM/YY).");
      return;
    }

    if (!validateCVV(cvv.value)) {
      alert("Please enter a valid CVV.");
      return;
    }

    if (!validatePaymentOption(paymentOptions)) {
      alert("Please select a payment option.");
      return;
    }

    alert("Form submitted successfully!");
    checkoutForm.submit();
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
    return expiryDateRegex.test(expiryDate);
  }

  function validateCVV(cvv) {
    const cvvRegex = /^[0-9]{3,4}$/;
    return cvvRegex.test(cvv);
  }

  function validatePaymentOption(paymentOptions) {
    let selected = false;
    paymentOptions.forEach(option => {
      if (option.checked) {
        selected = true;
      }
    });
    return selected;
  }
});
