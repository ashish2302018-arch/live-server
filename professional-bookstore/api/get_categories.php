<?php
require_once '../includes/config.php';
require_once '../includes/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $db->query("SELECT c.*, COUNT(b.id) as book_count 
                FROM categories c 
                LEFT JOIN books b ON c.id = b.category_id 
                GROUP BY c.id 
                ORDER BY c.name");
    
    $categories = $db->resultSet();
    
    echo json_encode([
        'success' => true,
        'categories' => $categories
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching categories: ' . $e->getMessage()
    ]);
}
?>