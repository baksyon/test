// Gallery filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryPhotos = document.querySelectorAll('.gallery-photo');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter photos
            galleryPhotos.forEach(photo => {
                if (category === 'all' || photo.getAttribute('data-category') === category) {
                    photo.style.display = 'block';
                    photo.style.opacity = '0';
                    setTimeout(() => {
                        photo.style.opacity = '1';
                    }, 100);
                } else {
                    photo.style.opacity = '0';
                    setTimeout(() => {
                        photo.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

});