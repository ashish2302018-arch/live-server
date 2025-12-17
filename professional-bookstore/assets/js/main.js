// Main Application
class BookstoreApp {
    constructor() {
        this.initialize();
    }

    async initialize() {
        // Initialize components
        this.initTheme();
        this.initLoadingScreen();
        this.initMobileMenu();
        this.initSearch();
        this.initCart();
        this.initBookHoverEffects();
        this.initSmoothScrolling();
        this.initAOS();
        
        // Load initial data
        await this.loadFeaturedBooks();
        await this.loadCategories();
        
        // Initialize event listeners
        this.initEventListeners();
    }

    initTheme() {
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = themeToggle.querySelector('i');
        
        // Check for saved theme or prefer-color-scheme
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            document.documentElement.setAttribute('data-theme', 'dark');
            themeIcon.className = 'fas fa-sun';
        }
        
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            localStorage.setItem('theme', newTheme);
        });
    }

    initLoadingScreen() {
        // Simulate loading progress
        const progressBar = document.querySelector('.progress-bar');
        const loadingScreen = document.getElementById('loading-screen');
        
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress > 100) {
                progress = 100;
                clearInterval(interval);
                
                // Hide loading screen
                setTimeout(() => {
                    loadingScreen.classList.add('hidden');
                }, 500);
            }
            progressBar.style.width = `${progress}%`;
        }, 200);
    }

    initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const menuClose = document.querySelector('.mobile-nav-close');
        const mobileNav = document.getElementById('mobile-nav');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                mobileNav.classList.add('active');
            });
        }
        
        if (menuClose) {
            menuClose.addEventListener('click', () => {
                mobileNav.classList.remove('active');
            });
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileNav.contains(e.target) && !menuToggle.contains(e.target)) {
                mobileNav.classList.remove('active');
            }
        });
    }

    initSearch() {
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');
        const suggestions = document.getElementById('search-suggestions');
        
        let searchTimeout;
        
        searchInput.addEventListener('input', async (e) => {
            clearTimeout(searchTimeout);
            
            const query = e.target.value.trim();
            if (query.length < 2) {
                suggestions.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`api/search_books.php?q=${encodeURIComponent(query)}`);
                    const results = await response.json();
                    
                    if (results.success && results.books.length > 0) {
                        this.displaySearchSuggestions(results.books);
                        suggestions.style.display = 'block';
                    } else {
                        suggestions.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Search error:', error);
                }
            }, 300);
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchForm.contains(e.target)) {
                suggestions.style.display = 'none';
            }
        });
    }

    displaySearchSuggestions(books) {
        const suggestions = document.getElementById('search-suggestions');
        suggestions.innerHTML = '';
        
        books.slice(0, 5).forEach(book => {
            const suggestion = document.createElement('div');
            suggestion.className = 'search-suggestion';
            suggestion.innerHTML = `
                <a href="book.php?id=${book.id}" class="suggestion-link">
                    <div class="suggestion-image">
                        <img src="${book.cover_image}" alt="${book.title}">
                    </div>
                    <div class="suggestion-info">
                        <h5>${book.title}</h5>
                        <p>${book.author}</p>
                        <span class="price">$${book.price}</span>
                    </div>
                </a>
            `;
            suggestions.appendChild(suggestion);
        });
    }

    initCart() {
        this.cart = JSON.parse(localStorage.getItem('bookstore_cart')) || [];
        this.updateCartUI();
    }

    updateCartUI() {
        const cartCount = document.querySelector('.cart-count');
        const cartItemsCount = document.querySelector('.cart-items-count');
        const cartTotal = document.querySelector('.total-amount');
        const cartItems = document.getElementById('cart-items');
        
        // Update cart count
        const totalItems = this.cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
        cartItemsCount.textContent = `${totalItems} item${totalItems !== 1 ? 's' : ''}`;
        
        // Update total
        const total = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartTotal.textContent = `$${total.toFixed(2)}`;
        
        // Update cart items
        this.updateCartItems();
    }

    updateCartItems() {
        const cartItems = document.getElementById('cart-items');
        
        if (this.cart.length === 0) {
            cartItems.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-bag"></i>
                    <p>Your cart is empty</p>
                </div>
            `;
            return;
        }
        
        cartItems.innerHTML = this.cart.map(item => `
            <div class="cart-item" data-id="${item.id}">
                <img src="${item.image}" alt="${item.title}">
                <div class="cart-item-info">
                    <h5>${item.title}</h5>
                    <p>${item.author}</p>
                    <div class="cart-item-price">
                        <span>$${item.price} x ${item.quantity}</span>
                        <span class="item-total">$${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                </div>
                <button class="remove-item" data-id="${item.id}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `).join('');
        
        // Add event listeners to remove buttons
        cartItems.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                this.removeFromCart(id);
            });
        });
    }

    addToCart(book) {
        const existingItem = this.cart.find(item => item.id === book.id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.cart.push({
                id: book.id,
                title: book.title,
                author: book.author,
                price: book.price,
                image: book.cover_image,
                quantity: 1
            });
        }
        
        this.saveCart();
        this.updateCartUI();
        this.showNotification(`${book.title} added to cart!`);
    }

    removeFromCart(id) {
        this.cart = this.cart.filter(item => item.id !== id);
        this.saveCart();
        this.updateCartUI();
        this.showNotification('Item removed from cart');
    }

    saveCart() {
        localStorage.setItem('bookstore_cart', JSON.stringify(this.cart));
    }

    async loadFeaturedBooks() {
        try {
            const response = await fetch('api/get_books.php?featured=1&limit=8');
            const data = await response.json();
            
            if (data.success) {
                this.displayFeaturedBooks(data.books);
            }
        } catch (error) {
            console.error('Error loading featured books:', error);
        }
    }

    displayFeaturedBooks(books) {
        const container = document.getElementById('featured-books-container');
        if (!container) return;
        
        container.innerHTML = books.map(book => `
            <div class="book-card" data-aos="fade-up" data-aos-delay="${books.indexOf(book) * 100}">
                ${book.discount_price ? `<span class="book-badge">Save ${Math.round((1 - book.discount_price/book.price) * 100)}%</span>` : ''}
                <div class="book-image">
                    <img src="${book.cover_image}" alt="${book.title}">
                    <div class="book-overlay">
                        <div class="quick-actions">
                            <button class="action-btn quick-view" data-id="${book.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn wishlist-btn" data-id="${book.id}">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="action-btn add-to-cart" data-id="${book.id}">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="book-info">
                    <h3 class="book-title">${book.title}</h3>
                    <p class="book-author">${book.author}</p>
                    <div class="book-rating">
                        <div class="stars">
                            ${this.generateStars(book.rating)}
                        </div>
                        <span class="rating-count">(${book.review_count})</span>
                    </div>
                    <div class="book-price">
                        <div>
                            <span class="price-current">$${book.discount_price || book.price}</span>
                            ${book.discount_price ? `<span class="price-original">$${book.price}</span>` : ''}
                        </div>
                        <button class="add-to-cart-btn" data-id="${book.id}">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
        
        // Add event listeners
        this.initBookCardEvents();
    }

    async loadCategories() {
        try {
            const response = await fetch('api/get_categories.php');
            const data = await response.json();
            
            if (data.success) {
                this.displayCategories(data.categories);
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    displayCategories(categories) {
        const container = document.getElementById('categories-container');
        if (!container) return;
        
        container.innerHTML = categories.map(category => `
            <div class="category-card" data-aos="zoom-in" data-aos-delay="${categories.indexOf(category) * 100}">
                <div class="category-icon" style="background: ${category.color}">
                    <i class="${category.icon}"></i>
                </div>
                <h3 class="category-title">${category.name}</h3>
                <p class="category-count">${category.book_count || 0} books</p>
            </div>
        `).join('');
        
        // Add event listeners
        container.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', () => {
                const categoryName = card.querySelector('.category-title').textContent;
                window.location.href = `category.php?name=${encodeURIComponent(categoryName)}`;
            });
        });
    }

    initBookCardEvents() {
        // Quick view
        document.querySelectorAll('.quick-view').forEach(button => {
            button.addEventListener('click', async (e) => {
                const bookId = e.currentTarget.dataset.id;
                await this.showBookModal(bookId);
            });
        });
        
        // Add to cart from card
        document.querySelectorAll('.add-to-cart, .add-to-cart-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                const bookId = e.currentTarget.dataset.id;
                await this.addBookToCart(bookId);
            });
        });
        
        // Wishlist
        document.querySelectorAll('.wishlist-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                const bookId = e.currentTarget.dataset.id;
                this.toggleWishlist(bookId);
            });
        });
    }

    async showBookModal(bookId) {
        try {
            const response = await fetch(`api/get_book_details.php?id=${bookId}`);
            const data = await response.json();
            
            if (data.success) {
                this.displayBookModal(data.book);
            }
        } catch (error) {
            console.error('Error loading book details:', error);
        }
    }

    displayBookModal(book) {
        // Create modal HTML
        const modalHTML = `
            <div class="book-modal">
                <div class="modal-content">
                    <button class="modal-close"><i class="fas fa-times"></i></button>
                    <div class="modal-body">
                        <!-- Book details will be loaded here -->
                        <h2>${book.title}</h2>
                        <p>by ${book.author}</p>
                        <!-- Add more details -->
                    </div>
                </div>
            </div>
        `;
        
        // Append to body and show
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Add close functionality
        document.querySelector('.modal-close').addEventListener('click', () => {
            document.querySelector('.book-modal').remove();
        });
    }

    async addBookToCart(bookId) {
        try {
            const response = await fetch(`api/get_book_details.php?id=${bookId}`);
            const data = await response.json();
            
            if (data.success) {
                this.addToCart(data.book);
            }
        } catch (error) {
            console.error('Error adding book to cart:', error);
        }
    }

    toggleWishlist(bookId) {
        let wishlist = JSON.parse(localStorage.getItem('bookstore_wishlist')) || [];
        
        if (wishlist.includes(bookId)) {
            wishlist = wishlist.filter(id => id !== bookId);
            this.showNotification('Removed from wishlist');
        } else {
            wishlist.push(bookId);
            this.showNotification('Added to wishlist');
        }
        
        localStorage.setItem('bookstore_wishlist', JSON.stringify(wishlist));
        this.updateWishlistUI();
    }

    updateWishlistUI() {
        const wishlist = JSON.parse(localStorage.getItem('bookstore_wishlist')) || [];
        const wishlistCount = document.querySelector('.wishlist .badge');
        
        if (wishlistCount) {
            wishlistCount.textContent = wishlist.length;
        }
    }

    initBookHoverEffects() {
        // Book card hover effects
        const bookCards = document.querySelectorAll('.book-card');
        
        bookCards.forEach(card => {
            card.addEventListener('mouseenter', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    }

    initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    initAOS() {
        // Initialize AOS (Animate on Scroll)
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100
            });
        }
    }

    initEventListeners() {
        // Newsletter form submission
        const newsletterForm = document.getElementById('newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleNewsletterSubmission(e.target);
            });
        }
    }

    async handleNewsletterSubmission(form) {
        const email = form.querySelector('input[type="email"]').value;
        
        try {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            this.showNotification('Thank you for subscribing to our newsletter!', 'success');
            form.reset();
        } catch (error) {
            this.showNotification('Something went wrong. Please try again.', 'error');
        }
    }

    showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
            <button class="notification-close"><i class="fas fa-times"></i></button>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            animation: slideInRight 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        // Add close functionality
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.remove();
        });
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }

    generateStars(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        
        let stars = '';
        
        // Full stars
        for (let i = 0; i < fullStars; i++) {
            stars += '<i class="fas fa-star"></i>';
        }
        
        // Half star
        if (hasHalfStar) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        }
        
        // Empty stars
        for (let i = 0; i < emptyStars; i++) {
            stars += '<i class="far fa-star"></i>';
        }
        
        return stars;
    }
}

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.bookstoreApp = new BookstoreApp();
});

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .book-card {
        --mouse-x: 50%;
        --mouse-y: 50%;
        position: relative;
        overflow: hidden;
    }
    
    .book-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(
            600px circle at var(--mouse-x) var(--mouse-y),
            rgba(255, 255, 255, 0.1),
            transparent 40%
        );
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }
    
    .book-card:hover::before {
        opacity: 1;
    }
`;
document.head.appendChild(style);