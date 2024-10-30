document.addEventListener('DOMContentLoaded', () => {
  let carousels = document.querySelectorAll('.card-carousel');
  let cards = document.querySelectorAll('.product-card');
  let currentIndex = 0;
  let totalCards = cards.length / carousels.length;
  let visibleCards = 4;  // Maximum of 4 cards visible at a time
  let cardWidthWithMargin = 25;  // 23.7% width + 1.3% margin

  // Auto slide every 3 seconds
  setInterval(() => {
    swipeCards();
  }, 3000);

  function swipeCards() {
    // If there are 4 or more cards remaining to display on next period, swipe 4 cards
    if (totalCards - currentIndex - visibleCards >= visibleCards) {
      currentIndex += visibleCards;
    // If reached the last 4 cards
    } else if (totalCards - currentIndex - visibleCards === 0) {
      currentIndex = 0;
    } else {
      // If fewer than 4 cards remain to display in next period, move to show the last 4 cards
      currentIndex = totalCards - visibleCards;
    }

    updateCarousel();
  }

  function updateCarousel() {
    carousels.forEach(carousel => {
      // Move each carousel by changing the transform property
      carousel.style.transform = `translateX(-${currentIndex * cardWidthWithMargin}%)`;
    });
  }
});