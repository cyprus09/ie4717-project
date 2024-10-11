const container = document.getElementById("container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");
const signUpForm = document.querySelector(".sign-up form");
const signInForm = document.querySelector(".sign-in form");

registerBtn.addEventListener("click", () => {
  container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
  container.classList.remove("active");
});

signUpForm.addEventListener("submit", function (event) {
  event.preventDefault();

  const name = signUpForm.querySelector('input[type="text"]').value.trim();
  const email = signUpForm.querySelector('input[type="email"]').value.trim();
  const password = signUpForm.querySelector('input[type="password"]').value.trim();

  if (!validateName(name) || !validateEmail(email) || !validatePassword(password)) {
    alert("Please fill out all fields correctly.");
    return;
  }

  alert("Registration successful!");
});

signInForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const email = signInForm.querySelector('input[type="email"]').value.trim();
  const password = signInForm.querySelector('input[type="password"]').value.trim();

  if (!validateEmail(email) || !validatePassword(password)) {
    alert("Please enter valid credentials.");
    return;
  }

  alert("Login successful!");
});

function validateName(name) {
  return name.length >= 3;
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function validatePassword(password) {
  return password.length >= 6;
}
