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

  const firstName = document.getElementById("firstName").value.trim();
  const lastName = document.getElementById("lastName").value.trim();
  const username = document.getElementById("username").value.trim();
  const email = signUpForm.querySelector('input[type="email"]').value.trim();
  const password = signUpForm.querySelector('input[type="password"]').value.trim();

  if (!validateName(firstName) || !validateEmail(email) || !validatePassword(password)) {
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

function validateFirstName(firstName) {
  return firstName.length >= 3;
}

function validateLastName(lastName) {
  return lastName.length >= 3;
}

function validateUsername(username) {
  return username.length >= 3;
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function validatePassword(password) {
  // minimum eight characters, at least one uppercase letter, one lowercase letter and one number
  const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
  return re.test(password);
}
