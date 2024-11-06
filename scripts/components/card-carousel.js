// Add this at the beginning of your carousel initialization
function initCarousel() {
  const carousel = document.querySelector('.card-carousel');
  
  // Add this class while dragging
  carousel.addEventListener('mousedown', () => {
      carousel.classList.add('is-dragging');
  });
  
  // Remove class when done dragging
  document.addEventListener('mouseup', () => {
      carousel.classList.remove('is-dragging');
  });
}

// Preserve hover states
function preserveHoverStates() {
  const cards = document.querySelectorAll('.card-carousel .product-card');
  cards.forEach(card => {
      card.addEventListener('mouseenter', () => {
          if (card.classList.contains('reveal-complete')) {
              // Prevent carousel from interfering with hover
              card.style.pointerEvents = 'auto';
              card.style.zIndex = '10';
          }
      });
      
      card.addEventListener('mouseleave', () => {
          card.style.removeProperty('z-index');
      });
  });
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  initCarousel();
  preserveHoverStates();
});

// Function to check if element is in viewport with snap scrolling
function isInViewport(element) {
  const rect = element.getBoundingClientRect();
  const windowHeight = window.innerHeight || document.documentElement.clientHeight;
  
  // Increased detection range for snapped sections
  return (
      (rect.top >= -windowHeight * 0.5 && rect.top <= windowHeight) ||
      (rect.bottom >= 0 && rect.bottom <= windowHeight * 1.5)
  );
}

// Keep track of last reveal time
let lastRevealTime = 0;
let isScrolling;

// Function to handle scroll reveal animation
function handleRevealAnimation() {
  const elements = document.querySelectorAll('.reveal:not(.revealed)');
  const currentTime = Date.now();
  
  elements.forEach((element, index) => {
      if (isInViewport(element)) {
          const baseDelay = 50; // Reduced to 0.05s for snappier response
          const totalDelay = baseDelay * index;
          
          if (currentTime - lastRevealTime >= baseDelay) {
              setTimeout(() => {
                  element.classList.add('revealed');
                  if (element.classList.contains('category-card')) {
                      element.classList.add('reveal-complete');
                  }
                  lastRevealTime = Date.now();
              }, totalDelay);
          }
      }
  });
}

// Add necessary CSS
const style = document.createElement('style');
style.textContent = `
  .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.6s ease-out, transform 0.6s ease-out; /* Faster transition */
      will-change: opacity, transform;
  }
  
  .reveal.revealed {
      opacity: 1;
      transform: translateY(0);
  }

  /* Brand category items animation */
  .highlight-section .brand-category-item.reveal {
      transform: translateX(-30px);
  }
  
  .highlight-section .brand-category-item.reveal.revealed {
      transform: translateX(0);
  }
  
  /* Enhanced category card animations */
  .category-card.reveal {
      opacity: 0;
      transform: scale(0.9) translateY(30px);
      transition: opacity 0.6s ease-out, transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  
  .category-card.reveal.revealed {
      opacity: 1;
      transform: scale(1) translateY(0);
  }

  .category-card {
      transition: none;
  }

  .category-card.reveal-complete {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .category-card.reveal-complete:hover {
      transform: scale(1.05);
      box-shadow: 0.8em 0.8em 0.8em rgba(54, 46, 91, 0.6);
  }

  /* Category name animation */
  .category-card .category-name {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.4s ease, transform 0.4s ease;
      transition-delay: 0.1s; /* Reduced delay */
  }

  .category-card.revealed .category-name {
      opacity: 1;
      transform: translateY(0);
  }

  /* Group-specific delays */
  .brand-category-section .brand-category-item.reveal,
  .category-carousel .category-card.reveal {
      transition-delay: calc(var(--reveal-index, 0) * 0.1s); /* Reduced delay */
  }
`;
document.head.appendChild(style);

// Add reveal index to elements that should animate as groups
function addRevealIndexes() {
  const brandItems = document.querySelectorAll('.brand-category-section .brand-category-item.reveal');
  brandItems.forEach((item, index) => {
      item.style.setProperty('--reveal-index', index);
  });

  const categoryCards = document.querySelectorAll('.category-carousel .category-card.reveal');
  categoryCards.forEach((card, index) => {
      card.style.setProperty('--reveal-index', index);
  });
}

// Enhanced scroll handling
function handleScroll() {
  // Clear previous timeout
  window.clearTimeout(isScrolling);

  // Run animation check immediately
  handleRevealAnimation();

  // Set new timeout
  isScrolling = setTimeout(() => {
      handleRevealAnimation();
  }, 50); // Check again after scroll stops
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  addRevealIndexes();
  handleRevealAnimation();
});

// Add optimized event listeners
const mainContent = document.querySelector('.main-content');
mainContent.addEventListener('scroll', handleScroll, { passive: true });
mainContent.addEventListener('scrollend', handleRevealAnimation);

// Handle resize
window.addEventListener('resize', handleRevealAnimation, { passive: true });

// Add intersection observer for better performance
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
      if (entry.isIntersecting && !entry.target.classList.contains('revealed')) {
          handleRevealAnimation();
      }
  });
}, {
  threshold: 0.1,
  rootMargin: '20% 0px'
});

// Observe all reveal elements
document.querySelectorAll('.reveal').forEach(element => {
  observer.observe(element);
});

// Initial check
handleRevealAnimation();

// Automate carousel scrolling
document.addEventListener('DOMContentLoaded', () => {
    let carousels = document.querySelectorAll('.card-carousel');
    let cards = document.querySelectorAll('.product-card');
    let currentIndex = 0;
    let totalCards = cards.length / carousels.length;
    let visibleCards = 4;
    let cardWidthWithMargin = 25;  
  
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