-- Create database
CREATE DATABASE IF NOT EXISTS professional_bookstore;
USE professional_bookstore;

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    color VARCHAR(20) DEFAULT '#3498db',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Books table
CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    author VARCHAR(255) NOT NULL,
    description TEXT,
    isbn VARCHAR(20),
    publisher VARCHAR(255),
    published_date DATE,
    pages INT,
    language VARCHAR(50) DEFAULT 'English',
    price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2),
    rating DECIMAL(3,2) DEFAULT 0.00,
    review_count INT DEFAULT 0,
    stock_quantity INT DEFAULT 0,
    category_id INT,
    cover_image VARCHAR(500),
    status ENUM('active', 'out_of_stock', 'coming_soon') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Insert sample categories
INSERT INTO categories (name, slug, description, icon, color) VALUES
('Fiction', 'fiction', 'Imaginative literature including novels and short stories', 'fas fa-book-open', '#3498db'),
('Non-Fiction', 'non-fiction', 'Fact-based writing on real events and topics', 'fas fa-clipboard-list', '#2ecc71'),
('Science & Technology', 'science-technology', 'Books on scientific discoveries and technological innovations', 'fas fa-flask', '#9b59b6'),
('Business & Finance', 'business-finance', 'Books on entrepreneurship, investing, and business strategies', 'fas fa-chart-line', '#e74c3c'),
('Self-Help', 'self-help', 'Personal development and improvement books', 'fas fa-hands-helping', '#f39c12'),
('Biography', 'biography', 'Life stories of notable individuals', 'fas fa-user-tie', '#1abc9c'),
('History', 'history', 'Historical accounts and analyses', 'fas fa-landmark', '#34495e'),
('Art & Design', 'art-design', 'Books on creative arts and design principles', 'fas fa-palette', '#e84393');

-- Insert sample books
INSERT INTO books (title, slug, author, description, isbn, publisher, published_date, pages, price, discount_price, rating, review_count, stock_quantity, category_id, cover_image, featured) VALUES
('Atomic Habits', 'atomic-habits', 'James Clear', 'Tiny Changes, Remarkable Results: An Easy & Proven Way to Build Good Habits & Break Bad Ones', '9780735211292', 'Avery', '2018-10-16', 320, 27.00, 24.30, 4.8, 12543, 89, 5, 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c', 1),
('The Psychology of Money', 'psychology-of-money', 'Morgan Housel', 'Timeless lessons on wealth, greed, and happiness', '9780857197689', 'Harriman House', '2020-09-08', 256, 25.99, NULL, 4.7, 8921, 45, 4, 'https://images.unsplash.com/photo-1553729459-efe14ef6055d', 1),
('Deep Work', 'deep-work', 'Cal Newport', 'Rules for Focused Success in a Distracted World', '9781455586691', 'Grand Central Publishing', '2016-01-05', 304, 28.00, 25.20, 4.6, 7456, 67, 5, 'https://images.unsplash.com/photo-1532012197267-da84d127e765', 1),
('The Innovators', 'the-innovators', 'Walter Isaacson', 'How a Group of Hackers, Geniuses, and Geeks Created the Digital Revolution', '9781476708690', 'Simon & Schuster', '2014-10-07', 560, 20.00, 18.00, 4.5, 5678, 32, 3, 'https://images.unsplash.com/photo-1497636577773-f1231844b336', 0),
('Sapiens', 'sapiens', 'Yuval Noah Harari', 'A Brief History of Humankind', '9780062316097', 'Harper', '2015-02-10', 464, 24.99, 22.49, 4.7, 19876, 78, 2, 'https://images.unsplash.com/photo-1589998059171-988d887df646', 1),
('Clean Code', 'clean-code', 'Robert C. Martin', 'A Handbook of Agile Software Craftsmanship', '9780132350884', 'Prentice Hall', '2008-08-01', 464, 49.99, 44.99, 4.8, 9876, 56, 3, 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0', 1),
('The Midnight Library', 'the-midnight-library', 'Matt Haig', 'A Novel About Life, Death, and the In-Between', '9780525559474', 'Viking', '2020-08-13', 304, 26.00, 23.40, 4.4, 12345, 91, 1, 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e', 0),
('Project Hail Mary', 'project-hail-mary', 'Andy Weir', 'A Science Fiction Novel by the Author of The Martian', '9780593135204', 'Ballantine Books', '2021-05-04', 496, 28.99, 26.09, 4.6, 8765, 43, 3, 'https://images.unsplash.com/photo-1531346688376-ab6275c4725e', 1);