let currentSlide = 0;
let slides = document.querySelectorAll('.carousel-item');
let indicators = document.querySelectorAll('.indicator');

function nextSlide() {
  currentSlide = (currentSlide + 1) % slides.length;
  updateCarousel();
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + slides.length) % slides.length;
  updateCarousel();
}

function goToSlide(index) {
  currentSlide = index;
  updateCarousel();
}

function updateCarousel() {
    slides.forEach((slide, index) => {
        slide.classList.toggle('active', index === currentSlide);
    });
    indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentSlide);
    });
}

setInterval(nextSlide, 3000);

updateCarousel();