let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const wrapper = document.querySelector('.slider-wrapper');

function showSlide(n) {
    currentSlide = (n + slides.length) % slides.length;
    const offset = -currentSlide * 100;
    wrapper.style.transform = `translateX(${offset}%)`;
}

function changeSlide(step) {
    showSlide(currentSlide + step);
}

// Auto-slide every 5 seconds
setInterval(() => changeSlide(1), 5000);

// Initialize
showSlide(0);