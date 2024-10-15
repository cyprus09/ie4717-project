document.addEventListener('DOMContentLoaded', function() {
    const bgTexts = document.querySelectorAll('.bg-text-top');
    let lastScrollPosition = window.scrollY;

    window.addEventListener('scroll', function() {
      const currentScrollPosition = window.scrollY;
      const scrollDifference = currentScrollPosition - lastScrollPosition;

      bgTexts.forEach(bgText => {
        const currentTransform = getComputedStyle(bgText).getPropertyValue('transform');
        const matrix = new DOMMatrix(currentTransform);
        const currentX = matrix.m41;
        
        bgText.style.transform = `translateX(${currentX + scrollDifference}px)`;
      });

      lastScrollPosition = currentScrollPosition;
    });
});