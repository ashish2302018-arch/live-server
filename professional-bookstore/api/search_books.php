<?php
require_once '../includes/config.php';
require_once '../includes/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';
    
    if (strlen($query) < 2) {
        echo json_encode(['success' => true, 'books' => []]);
        return;
    }
    
    $searchTerm = "%{$query}%";
    
    $db->query("SELECT b.*, c.name as category_name 
                FROM books b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.title LIKE ? 
                OR b.author LIKE ? 
                OR b.description LIKE ?
                OR c.name LIKE ?
                ORDER BY b.created_at DESC
                LIMIT 10");
    
    $db->bind('s', $searchTerm);
    $db->bind('s', $searchTerm);
    $db->bind('s', $searchTerm);
    $db->bind('s', $searchTerm);
    
    $books = $db->resultSet();
    
    echo json_encode([
        'success' => true,
        'books' => $books
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error searching books: ' . $e->getMessage()
    ]);
}
?>