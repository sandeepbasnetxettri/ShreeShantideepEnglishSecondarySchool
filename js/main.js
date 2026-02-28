document.addEventListener('DOMContentLoaded', () => {
    // Hero Slider
    const slider = document.querySelector('.hero-slider');
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    
    if (slider && slides.length > 0) {
        let currentSlide = 0;
        const totalSlides = slides.length;

        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentSlide].classList.add('active');
        }

        // Auto slide
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }, 5000);

        // Click on dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
            });
        });
    }

    // Sticky Navbar shadow on scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            navbar.style.boxShadow = '0 10px 15px -3px rgb(0 0 0 / 0.1)';
        } else {
            navbar.style.boxShadow = '0 4px 6px -1px rgb(0 0 0 / 0.1)';
        }
    });
});
