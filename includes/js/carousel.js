function moveCarousel(carouselId, direction) {
    const carousel = document.getElementById(carouselId);
    if (!carousel) {
        console.error('Carousel element not found:', carouselId);
        return;
    }
    
    const cardWidth = 250 + 16;
    const visibleCards = Math.floor(carousel.parentElement.offsetWidth / cardWidth);
    const scrollAmount = direction * cardWidth * Math.min(visibleCards, 3);
    
    carousel.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Get the category from URL parameter if it exists
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');
    
    if (category) {
        const categorySection = document.getElementById(category);
        if (categorySection) {
            setTimeout(function() {
                categorySection.scrollIntoView({ behavior: 'smooth' });
            }, 100);
        }
    }
    
    // Add event listeners to all carousel buttons
    document.querySelectorAll('.carousel-controls .prev, .carousel-controls .next').forEach(button => {
        button.addEventListener('click', function() {
            const carouselId = this.closest('.carousel-container').querySelector('.carousel').id;
            const direction = this.classList.contains('prev') ? -1 : 1;
            moveCarousel(carouselId, direction);
        });
    });
});
