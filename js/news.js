// News page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Newsletter subscription
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Simple validation
            if (email && email.includes('@')) {
                newsletterForm.style.display = 'block';
                newsletterForm.className = 'form-message success';
                newsletterForm.textContent = 'Спасибо за подписку! Вы будете получать наши новости на ' + email;
                this.reset();
            } else {
                alert('Пожалуйста, введите корректный email адрес');
            }
        });
    }

    // Read more functionality for featured article
    const readMoreBtn = document.querySelector('.read-more-btn');
    if (readMoreBtn) {
        readMoreBtn.addEventListener('click', function() {
            const details = document.querySelector('.news-details');
            if (details.style.display === 'none' || !details.style.display) {
                details.style.display = 'block';
                this.textContent = 'Скрыть';
            } else {
                details.style.display = 'none';
                this.textContent = 'Подробнее';
            }
        });
    }

    // News card hover effects
    const newsCards = document.querySelectorAll('.news-card');
    newsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Announcement items animation
    const announcementItems = document.querySelectorAll('.announcement-item');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateX(0)';
            }
        });
    }, observerOptions);

    announcementItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-30px)';
        item.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(item);
    });
    // News links functionality
    const newsLinks = document.querySelectorAll('.news-link');
    newsLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const title = this.closest('.news-card').querySelector('h3').textContent;
            alert(`Полная статья "${title}" будет доступна в ближайшее время!`);
        });
    });
});