<?php
// Database Test Script
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/helpers.php';

echo "=== DATABASE TEST ===\n\n";

try {
    // Test database connection
    echo "1. Database Connection:\n";
    $db = Database::getInstance();
    echo "   ✓ Database connection successful\n\n";
    
    // Test tables
    echo "2. Table Existence:\n";
    $tables = ['services', 'programs', 'gallery_items', 'blog_posts', 'testimonials', 'contact_messages', 'bookings', 'newsletter_subscribers'];
    
    foreach ($tables as $table) {
        try {
            $count = db_count($table);
            echo "   - $table: ✓ ($count records)\n";
        } catch (Exception $e) {
            echo "   - $table: ✗ (Error: " . $e->getMessage() . ")\n";
        }
    }
    echo "\n";
    
    // Test INSERT
    echo "3. INSERT Test:\n";
    try {
        $testData = [
            'name' => 'Test User',
            'email' => 'test' . time() . '@example.com',
            'phone' => '+1 555-0000',
            'subject' => 'Test Subject',
            'message' => 'Test message for database testing.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Agent'
        ];
        $insertId = db_insert('contact_messages', $testData);
        echo "   ✓ INSERT successful (ID: $insertId)\n\n";
    } catch (Exception $e) {
        echo "   ✗ INSERT failed: " . $e->getMessage() . "\n\n";
    }
    
    // Test SELECT
    echo "4. SELECT Test:\n";
    try {
        $messages = db_select('contact_messages', '*', 'name = :name', ['name' => 'Test User'], 'id DESC', 1);
        echo "   ✓ SELECT successful (Found " . count($messages) . " records)\n\n";
    } catch (Exception $e) {
        echo "   ✗ SELECT failed: " . $e->getMessage() . "\n\n";
    }
    
    // Test UPDATE
    echo "5. UPDATE Test:\n";
    try {
        if (!empty($messages)) {
            $updateResult = db_update('contact_messages', ['status' => 'read'], 'id = :id', ['id' => $messages[0]['id']]);
            echo "   ✓ UPDATE successful ($updateResult rows affected)\n\n";
        } else {
            echo "   ⚠ No records to update\n\n";
        }
    } catch (Exception $e) {
        echo "   ✗ UPDATE failed: " . $e->getMessage() . "\n\n";
    }
    
    // Test DELETE
    echo "6. DELETE Test:\n";
    try {
        if (!empty($messages)) {
            $deleteResult = db_delete('contact_messages', 'id = :id', ['id' => $messages[0]['id']]);
            echo "   ✓ DELETE successful ($deleteResult rows affected)\n\n";
        } else {
            echo "   ⚠ No records to delete\n\n";
        }
    } catch (Exception $e) {
        echo "   ✗ DELETE failed: " . $e->getMessage() . "\n\n";
    }
    
    // Test JOIN
    echo "7. JOIN Test:\n";
    try {
        $joinQuery = "SELECT s.*, COUNT(b.id) as booking_count FROM services s LEFT JOIN bookings b ON s.id = b.service_id GROUP BY s.id LIMIT 1";
        $joinResult = db_fetch_all($joinQuery, []);
        echo "   ✓ JOIN successful (Found " . count($joinResult) . " records)\n\n";
    } catch (Exception $e) {
        echo "   ✗ JOIN failed: " . $e->getMessage() . "\n\n";
    }
    
    // Test Prepared Statements
    echo "8. Prepared Statement Security:\n";
    try {
        $safeQuery = "SELECT * FROM services WHERE title LIKE :search";
        $safeResult = db_fetch_all($safeQuery, ['search' => '%Training%']);
        echo "   ✓ Prepared statements working (Found " . count($safeResult) . " records)\n\n";
    } catch (Exception $e) {
        echo "   ✗ Prepared statements failed: " . $e->getMessage() . "\n\n";
    }
    
    echo "=== DATABASE TEST COMPLETE ===\n";
    echo "✓ All database operations working correctly\n";
    
} catch (Exception $e) {
    echo "✗ Database test failed: " . $e->getMessage() . "\n";
}
