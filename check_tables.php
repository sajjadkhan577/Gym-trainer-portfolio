<?php
require_once 'config/config.php';
require_once 'database/database.php';

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Current tables in elite_fitness database:\n";
    print_r($tables);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
