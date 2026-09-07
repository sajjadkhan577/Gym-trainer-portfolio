<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/helpers.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 6;
$category = isset($_GET['category']) ? strtoupper($_GET['category']) : 'ALL';
$offset = ($page - 1) * $perPage;

// Build query
$where = 'status = :status';
$params = ['status' => 'active'];

if ($category !== 'ALL') {
    $where .= ' AND category = :category';
    $params['category'] = $category;
}

// Get gallery items
$galleryItems = db_select('gallery', '*', $where, $params, 'sort_order ASC', $offset . ', ' . $perPage);

if ($galleryItems && count($galleryItems) > 0) {
    $html = '';
    foreach ($galleryItems as $item) {
        $galleryData = [
            'image' => $item['image_url'],
            'video' => $item['video_url'],
            'category' => $item['category'],
            'title' => $item['title'],
            'aspect' => $item['aspect_ratio'],
            'isVideo' => $item['is_video'],
            'description' => $item['description']
        ];
        
        // Start output buffer to capture component HTML
        ob_start();
        extract($galleryData);
        require __DIR__ . '/../includes/components/gallery_item.php';
        $html .= ob_get_clean();
    }
    
    echo json_encode([
        'success' => true,
        'html' => $html,
        'hasMore' => count($galleryItems) === $perPage,
        'page' => $page
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No more items to load',
        'hasMore' => false
    ]);
}
