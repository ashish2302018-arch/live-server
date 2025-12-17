<?php
require_once '../includes/config.php';
require_once '../includes/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
    $featured = isset($_GET['featured']) ? (bool)$_GET['featured'] : false;
    $offset = ($page - 1) * $limit;

    // Build query
    $query = "SELECT b.*, c.name as category_name, 
              COUNT(DISTINCT r.id) as review_count,
              AVG(r.rating) as avg_rating
              FROM books b
              LEFT JOIN categories c ON b.category_id = c.id
              LEFT JOIN reviews r ON b.id = r.book_id
              WHERE 1=1";
    
    $params = [];
    
    if ($category_id) {
        $query .= " AND b.category_id = ?";
        $params[] = $category_id;
    }
    
    if ($featured) {
        $query .= " AND b.featured = 1";
    }
    
    $query .= " GROUP BY b.id
                ORDER BY b.created_at DESC
                LIMIT ? OFFSET ?";
    
    $params[] = $limit;
    $params[] = $offset;
    
    $db->query($query);
    
    if (!empty($params)) {
        $types = str_repeat('i', count($params));
        $db->bindParams($types, ...$params);
    }
    
    $books = $db->resultSet();
    
    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM books b WHERE 1=1";
    if ($category_id) {
        $countQuery .= " AND b.category_id = $category_id";
    }
    if ($featured) {
        $countQuery .= " AND b.featured = 1";
    }
    
    $db->query($countQuery);
    $total = $db->single()['total'];
    
    echo json_encode([
        'success' => true,
        'books' => $books,
        'total' => $total,
        'page' => $page,
        'total_pages' => ceil($total / $limit)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching books: ' . $e->getMessage()
    ]);
}
?>