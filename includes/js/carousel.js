document.addEventListener('DOMContentLoaded', function() {
    const carousels = document.querySelectorAll('.carousel-container');
    
    carousels.forEach(carousel => {
        const track = carousel.querySelector('.carousel-track');
        const items = carousel.querySelectorAll('.carousel-item');
        const prevButton = carousel.querySelector('.carousel-prev');
        const nextButton = carousel.querySelector('.carousel-next');
        
        if (!track || items.length === 0) return;
        
        let currentIndex = 0;
        const itemWidth = items[0].offsetWidth;
        const itemsToShow = Math.floor(carousel.offsetWidth / itemWidth) || 3;
        const maxIndex = Math.max(0, items.length - itemsToShow);
        
        // Set initial position
        updateCarousel();
        
        // Add event listeners for buttons
        if (prevButton) {
            prevButton.addEventListener('click', () => {
                currentIndex = Math.max(0, currentIndex - 1);
                updateCarousel();
            });
        }
        
        if (nextButton) {
            nextButton.addEventListener('click', () => {
                currentIndex = Math.min(maxIndex, currentIndex + 1);
                updateCarousel();
            });
        }
        
        // Update carousel position
        function updateCarousel() {
            const translateX = -currentIndex * (itemWidth + 20); // 20px is the margin-right
            track.style.transform = `translateX(${translateX}px)`;
            
            // Update button states
            if (prevButton) prevButton.disabled = currentIndex === 0;
            if (nextButton) nextButton.disabled = currentIndex >= maxIndex;
        }
        
        // Handle window resize
        window.addEventListener('resize', () => {
            const newItemsToShow = Math.floor(carousel.offsetWidth / itemWidth) || 3;
            const newMaxIndex = Math.max(0, items.length - newItemsToShow);
            currentIndex = Math.min(currentIndex, newMaxIndex);
            updateCarousel();
        });
    });
    
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
});
