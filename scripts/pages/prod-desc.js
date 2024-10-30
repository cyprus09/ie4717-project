// Quantity buttons
document.addEventListener("DOMContentLoaded", () => {
  const quantityButtons = document.querySelectorAll(".quantity-btn");
  quantityButtons.forEach(button => {
    button.addEventListener("click", function () {
      const input = this.parentElement.querySelector("input[type='number']");
      let currentValue = parseInt(input.value);

      if (this.classList.contains("plus") && currentValue < 10) {
        input.value = currentValue + 1;
      } else if (this.classList.contains("minus") && currentValue > 1) {
        input.value = currentValue - 1;
      }
    });
  });
});

// // size chart modal js
// var modal = document.getElementById("myModal");
// var btn = document.getElementById("sizeChart");
// var span = document.getElementsByClassName("close")[0];

// btn.onclick = function (event) {
//   event.preventDefault();
//   modal.style.display = "block";
// };

// span.onclick = function () {
//   modal.style.display = "none";
// };

// window.onclick = function (event) {
//   if (event.target == modal) {
//     modal.style.display = "none";
//   }
// };

