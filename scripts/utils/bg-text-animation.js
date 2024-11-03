// Enhanced version with performance optimizations
const bgTextElements = {
    top: [],
    bottom: []
};

// Cache elements on load
document.addEventListener('DOMContentLoaded', () => {
    // Cache all bg-text elements
    document.querySelectorAll('.highlight-section').forEach(section => {
        const topText = section.querySelector('.bg-text-top');
        const bottomText = section.querySelector('.bg-text-bottom');
        
        if (topText && bottomText) {
            bgTextElements.top.push(topText);
            bgTextElements.bottom.push(bottomText);
        }
    });
    
    handleBgTextScroll();
});

// Function to handle the background text animations
function handleBgTextScroll() {
    const mainContent = document.querySelector('.main-content');
    const currentScroll = mainContent.scrollTop;
    const windowHeight = window.innerHeight;
    
    bgTextElements.top.forEach((topText, index) => {
        const bottomText = bgTextElements.bottom[index];
        const section = topText.closest('.highlight-section');
        const rect = section.getBoundingClientRect();
        
        if (rect.top < windowHeight && rect.bottom > 0) {
            const sectionProgress = 1 - (rect.top / windowHeight);
            const moveAmount = sectionProgress * 800;
            
            topText.style.transform = `translateX(${moveAmount}px)`;
            bottomText.style.transform = `translateX(-${moveAmount}px)`;
        }
    });
    
    lastScrollPosition = currentScroll;
}

// Add scroll listener with debounce
let scrollTimeout;
mainContent.addEventListener('scroll', () => {
    if (!scrollTimeout) {
        scrollTimeout = setTimeout(() => {
            window.requestAnimationFrame(handleBgTextScroll);
            scrollTimeout = null;
        }, 5);
    }
}, { passive: true });

// Handle resize with debounce
let resizeTimeout;
window.addEventListener('resize', () => {
    if (!resizeTimeout) {
        resizeTimeout = setTimeout(() => {
            window.requestAnimationFrame(handleBgTextScroll);
            resizeTimeout = null;
        }, 10);
    }
}, { passive: true });