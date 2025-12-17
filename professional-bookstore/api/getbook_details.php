<?php
require_once '../includes/config.php';
require_once '../includes/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($book_id <= 0) {
        throw new Exception('Invalid book ID');
    }
    
    $db->query("SELECT b.*, c.name as category_name,
                (SELECT AVG(rating) FROM reviews WHERE book_id = b.id) as avg_rating,
                (SELECT COUNT(*) FROM reviews WHERE book_id = b.id) as review_count
                FROM books b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.id = ?");
    
    $db->bind('i', $book_id);
    $book = $db->single();
    
    if (!$book) {
        throw new Exception('Book not found');
    }
    
    echo json_encode([
        'success' => true,
        'book' => $book
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>