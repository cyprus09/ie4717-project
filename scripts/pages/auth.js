document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("container");
  const registerBtn = document.getElementById("registerToggle");
  const loginBtn = document.getElementById("loginToggle");
  const signUpForm = document.querySelector(".register-form form");
  const signInForm = document.querySelector(".login-form form");

  if (registerBtn) {
    registerBtn.addEventListener("click", () => {
      container.classList.add("active");
    });
  }

  if (loginBtn) {
    loginBtn.addEventListener("click", () => {
      container.classList.remove("active");
    });
  }

  document.getElementById("register-form").addEventListener("submit", function (e) {
    const password = document.querySelector('input[name="password"]').value;

    // Check password requirements
    const hasLowerCase = /[a-z]/.test(password);
    const hasUpperCase = /[A-Z]/.test(password);
    const hasNumber = /\d/.test(password);
    const isLongEnough = password.length >= 8;

    if (!isLongEnough) {
      alert("Password must be at least 8 characters long.");
      e.preventDefault();
      return;
    }
    if (!hasLowerCase) {
      alert("Password must contain at least one lowercase letter.");
      e.preventDefault();
      return;
    }
    if (!hasUpperCase) {
      alert("Password must contain at least one uppercase letter.");
      e.preventDefault();
      return;
    }
    if (!hasNumber) {
      alert("Password must contain at least one number.");
      e.preventDefault();
      return;
    }
  });
});
