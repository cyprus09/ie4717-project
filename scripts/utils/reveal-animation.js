// Function to check if element is in viewport with snap scrolling
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    
    return (
        (rect.top >= -windowHeight * 0.2 && rect.top <= windowHeight * 0.8) ||
        (rect.bottom >= windowHeight * 0.2 && rect.bottom <= windowHeight * 1.2)
    );
}

// Keep track of last reveal time
let lastRevealTime = 0;

// Function to handle scroll reveal animation
function handleRevealAnimation() {
    const elements = document.querySelectorAll('.reveal:not(.revealed)');
    const currentTime = Date.now();
    
    elements.forEach((element, index) => {
        if (isInViewport(element)) {
            // Calculate delay based on sequential order
            const baseDelay = 100; // 0.1s in milliseconds
            const totalDelay = baseDelay * index;
            
            // Only reveal if enough time has passed since last reveal
            if (currentTime - lastRevealTime >= baseDelay) {
                setTimeout(() => {
                    element.classList.add('revealed');
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
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        will-change: opacity, transform;
    }
    
    .reveal.revealed {
        opacity: 1;
        transform: translateY(0);
    }

    /* Different animations for different sections */
    .highlight-section .brand-category-item.reveal {
        transform: translateY(30px);
    }
    
    .highlight-section .brand-category-item.reveal.revealed {
        transform: translateX(0);
    }
    
    .category-card.reveal {
        transform: translateY(30px);;
        opacity: 0;
    }
    
    .category-card.reveal.revealed {
        transform: translateY(0);
        opacity: 1;
    }

    /* Group-specific delays (for items that should animate together) */
    .brand-category-section .brand-category-item.reveal,
    .category-carousel .category-card.reveal {
        transition-delay: calc(var(--reveal-index, 0) * 0.15s);
    }
`;
document.head.appendChild(style);

// Add reveal index to elements that should animate as groups
function addRevealIndexes() {
    // Add indexes to brand category items
    const brandItems = document.querySelectorAll('.brand-category-section .brand-category-item.reveal');
    brandItems.forEach((item, index) => {
        item.style.setProperty('--reveal-index', index);
    });

    // Add indexes to category cards
    const categoryCards = document.querySelectorAll('.category-carousel .category-card.reveal');
    categoryCards.forEach((card, index) => {
        card.style.setProperty('--reveal-index', index);
    });
}

// Throttle function to limit how often the scroll handler fires
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

// Add event listeners
window.addEventListener('scroll', throttle(handleRevealAnimation, 100));
window.addEventListener('DOMContentLoaded', () => {
    addRevealIndexes();
    handleRevealAnimation();
});
window.addEventListener('resize', throttle(handleRevealAnimation, 100));

// Trigger check on snap scroll completion
document.querySelector('.main-content').addEventListener('scrollend', handleRevealAnimation);

// Initial check
handleRevealAnimation();