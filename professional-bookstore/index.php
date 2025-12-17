<?php
$page_title = 'Home - Discover Your Next Favorite Book';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text" data-aos="fade-right">
                <h1 class="hero-title">
                    Discover Your Next <span class="highlight">Favorite Book</span>
                </h1>
                <p class="hero-description">
                    Explore our curated collection of over 50,000 books spanning all genres. 
                    From timeless classics to contemporary bestsellers, find your perfect read 
                    with Pendulum Books.
                </p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">50,000+</span>
                        <span class="stat-label">Books Available</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">4.8★</span>
                        <span class="stat-label">Customer Rating</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Support</span>
                    </div>
                </div>
                <div class="hero-cta">
                    <a href="#featured-books" class="btn btn-primary btn-lg">
                        <i class="fas fa-book-open"></i> Browse Collection
                    </a>
                    <a href="#categories" class="btn btn-secondary btn-lg">
                        <i class="fas fa-th-large"></i> View Categories
                    </a>
                </div>
            </div>
            <div class="hero-visual" data-aos="fade-left">
                <div class="floating-books">
                    <div class="book book-1"></div>
                    <div class="book book-2"></div>
                    <div class="book book-3"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="categories-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <p class="section-subtitle">Browse Collection</p>
            <h2 class="section-title">Explore by Category</h2>
            <p class="section-description">
                Discover books across all genres. From fiction to technology, 
                we have something for every reader.
            </p>
        </div>
        <div class="categories-grid" id="categories-container">
            <!-- Categories will be loaded by JavaScript -->
        </div>
    </div>
</section>

<!-- Featured Books Section -->
<section id="featured-books" class="featured-books">
    <div class="container">
        <div class="books-header" data-aos="fade-up">
            <div>
                <p class="section-subtitle">Curated Selection</p>
                <h2 class="section-title">Featured Books</h2>
            </div>
            <div class="filter-controls">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Best Sellers</button>
                <button class="filter-btn">New Releases</button>
                <button class="filter-btn">On Sale</button>
                <a href="#" class="view-all">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="books-grid" id="featured-books-container">
            <!-- Books will be loaded by JavaScript -->
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content" data-aos="zoom-in">
            <h2 class="newsletter-title">Stay Updated</h2>
            <p class="newsletter-description">
                Subscribe to our newsletter and be the first to know about new arrivals, 
                exclusive deals, and reading recommendations.
            </p>
            <form id="newsletter-form" class="newsletter-form">
                <input type="email" class="newsletter-input" placeholder="Enter your email address" required>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane"></i> Subscribe
                </button>
            </form>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
?>