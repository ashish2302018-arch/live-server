<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Professional Online Bookstore with curated selection of books across all genres">
    <meta name="keywords" content="books, bookstore, reading, literature, ebooks, novels">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo ASSETS_PATH; ?>/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo ASSETS_PATH; ?>/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo ASSETS_PATH; ?>/images/favicon/favicon-16x16.png">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>/css/loading.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loading-container">
            <div class="pendulum-wrapper">
                <div class="pendulum">
                    <div class="string"></div>
                    <div class="weight">
                        <div class="inner-circle"></div>
                        <div class="glow"></div>
                    </div>
                </div>
            </div>
            <div class="loading-content">
                <div class="logo-loading">
                    <i class="fas fa-book"></i>
                    <h1>Pendulum<span>Books</span></h1>
                </div>
                <p class="loading-subtitle">Curating Knowledge for the Modern Reader</p>
                <div class="loading-progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="container">
                <div class="top-bar-content">
                    <div class="announcement">
                        <i class="fas fa-gift"></i>
                        <span>Free shipping on orders over $50 • Use code: READ2024</span>
                    </div>
                    <div class="top-bar-links">
                        <a href="#"><i class="fas fa-question-circle"></i> Help</a>
                        <a href="#"><i class="fas fa-truck"></i> Track Order</a>
                        <div class="theme-switcher">
                            <button id="theme-toggle">
                                <i class="fas fa-moon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <header class="main-header">
            <div class="container">
                <div class="header-content">
                    <!-- Logo -->
                    <div class="logo" data-aos="fade-right">
                        <a href="index.php" class="logo-link">
                            <div class="logo-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="logo-text">
                                <h1>Pendulum<span>Books</span></h1>
                                <p class="logo-tagline">Where Stories Find You</p>
                            </div>
                        </a>
                    </div>

                    <!-- Search Bar -->
                    <div class="search-container" data-aos="fade-down">
                        <form id="search-form" class="search-form">
                            <div class="search-input-group">
                                <select class="search-category">
                                    <option value="all">All Categories</option>
                                    <?php
                                    // Fetch categories for dropdown
                                    $db->query("SELECT id, name FROM categories ORDER BY name");
                                    $categories = $db->resultSet();
                                    foreach ($categories as $category) {
                                        echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
                                    }
                                    ?>
                                </select>
                                <input type="text" id="search-input" placeholder="Search for books, authors, or ISBN..." autocomplete="off">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="search-suggestions" id="search-suggestions"></div>
                        </form>
                    </div>

                    <!-- User Actions -->
                    <div class="user-actions" data-aos="fade-left">
                        <div class="action-item wishlist">
                            <a href="#" class="action-link">
                                <div class="action-icon">
                                    <i class="far fa-heart"></i>
                                    <span class="badge">3</span>
                                </div>
                                <span class="action-label">Wishlist</span>
                            </a>
                        </div>
                        
                        <div class="action-item cart" id="cart-preview">
                            <a href="#" class="action-link">
                                <div class="action-icon">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span class="badge cart-count">0</span>
                                </div>
                                <span class="action-label">Cart</span>
                            </a>
                            <!-- Cart Dropdown -->
                            <div class="cart-dropdown">
                                <div class="cart-dropdown-header">
                                    <h4>Shopping Cart</h4>
                                    <span class="cart-items-count">0 items</span>
                                </div>
                                <div class="cart-dropdown-items" id="cart-items">
                                    <!-- Cart items will be loaded here -->
                                    <div class="empty-cart">
                                        <i class="fas fa-shopping-bag"></i>
                                        <p>Your cart is empty</p>
                                    </div>
                                </div>
                                <div class="cart-dropdown-footer">
                                    <div class="cart-total">
                                        <span>Total:</span>
                                        <span class="total-amount">$0.00</span>
                                    </div>
                                    <a href="#" class="btn btn-primary btn-sm">View Cart</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="action-item account">
                            <a href="#" class="action-link">
                                <div class="action-icon">
                                    <i class="far fa-user"></i>
                                </div>
                                <span class="action-label">Account</span>
                            </a>
                            <div class="account-dropdown">
                                <a href="#"><i class="fas fa-user-circle"></i> My Profile</a>
                                <a href="#"><i class="fas fa-history"></i> Order History</a>
                                <a href="#"><i class="fas fa-cog"></i> Settings</a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="main-navigation">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="index.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-dropdown">
                        <a href="#"><i class="fas fa-th-large"></i> Categories <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-grid">
                                <?php
                                // Display categories in dropdown
                                $db->query("SELECT id, name, icon, color FROM categories LIMIT 8");
                                $categories = $db->resultSet();
                                foreach ($categories as $category) {
                                    echo '<a href="category.php?id=' . $category['id'] . '" class="dropdown-item">
                                            <div class="dropdown-icon" style="background: ' . $category['color'] . '">
                                                <i class="' . $category['icon'] . '"></i>
                                            </div>
                                            <div class="dropdown-text">
                                                <h4>' . htmlspecialchars($category['name']) . '</h4>
                                                <p>Browse collection</p>
                                            </div>
                                            <i class="fas fa-chevron-right"></i>
                                          </a>';
                                }
                                ?>
                            </div>
                        </div>
                    </li>
                    <li><a href="#"><i class="fas fa-fire"></i> Best Sellers</a></li>
                    <li><a href="#"><i class="fas fa-bolt"></i> New Arrivals</a></li>
                    <li><a href="#"><i class="fas fa-percentage"></i> Special Offers</a></li>
                    <li><a href="#"><i class="fas fa-star"></i> Featured</a></li>
                    <li><a href="#"><i class="fas fa-book-reader"></i> Audio Books</a></li>
                    <li><a href="#"><i class="fas fa-shipping-fast"></i> Free Shipping</a></li>
                </ul>
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>

        <!-- Mobile Navigation -->
        <nav class="mobile-navigation" id="mobile-nav">
            <div class="mobile-nav-header">
                <h3>Menu</h3>
                <button class="mobile-nav-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <ul class="mobile-nav-menu">
                <!-- Mobile menu items will be populated by JS -->
            </ul>
        </nav>