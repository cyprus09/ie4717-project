const container = document.getElementById("container");
const registerBtn = document.getElementById("registerToggle");
const loginBtn = document.getElementById("loginToggle");
const signUpForm = document.querySelector(".register-form form");
const signInForm = document.querySelector(".login-form form");

registerBtn.addEventListener("click", () => {
  container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
  container.classList.remove("active");
});

// Helper function to handle form submission
function handleFormSubmission(form, url) {
  const formData = new FormData(form);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      const result = JSON.parse(xhr.responseText);
      if (result.success) {
        window.location.href = "../../pages/home.php";
      } else {
        showError(result.message);
      }
    }
  };
  xhr.send(formData);
}

signUpForm.addEventListener("submit", function (event) {
  event.preventDefault();

  const firstName = document.getElementById("firstName").value.trim();
  const lastName = document.getElementById("lastName").value.trim();
  const username = document.getElementById("username").value.trim();
  const email = signUpForm.querySelector('input[type="email"]').value.trim();
  const password = signUpForm.querySelector('input[type="password"]').value.trim();

  if (!validateFirstName(firstName)) {
    alert("First name should be atleast 3 characters");
    return;
  }
  if (!validateLastName(lastName)) {
    alert("Last name should be atleast 3 characters");
    return;
  }
  if (!validateUsername(firstName)) {
    alert("Username should be atleast 3 characters");
    return;
  }
  if (!validateEmail(firstName)) {
    alert("The email address is not valid");
    return;
  }
  if (!validatePassword(firstName)) {
    alert(
      "Password should be atleast 8 characters, at least one uppercase letter, one lowercase letter and one number"
    );
    return;
  }
  handleFormSubmission(signUpForm, "../../pages/auth.php");
});

signInForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const email = signInForm.querySelector('input[type="email"]').value.trim();
  const password = signInForm.querySelector('input[type="password"]').value.trim();

  if (!validateEmail(email) || !validatePassword(password)) {
    alert("Please enter valid credentials.");
    return;
  }

  handleFormSubmission(signInForm, "../../pages/auth.php");
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
  const re = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g;
  return re.test(email);
}

function validatePassword(password) {
  // minimum eight characters, at least one uppercase letter, one lowercase letter and one number
  const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
  return re.test(password);
}

// email: mayank1@gmail.com
// password: Hello!4321