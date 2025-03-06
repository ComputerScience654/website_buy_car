document.addEventListener("DOMContentLoaded", function () {
    let slides = document.querySelectorAll(".slideshow-item");
    let currentIndex = 0;

    function showNextSlide() {
        slides[currentIndex].classList.remove("active");
        currentIndex = (currentIndex + 1) % slides.length;
        slides[currentIndex].classList.add("active");
    }

    setInterval(showNextSlide, 5000);
});