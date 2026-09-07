<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../database/database.php';

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Read and execute the migration SQL
    $sql = file_get_contents(__DIR__ . '/create_admins_table.sql');
    
    if ($sql === false) {
        throw new Exception("Could not read migration file");
    }
    
    $pdo->exec($sql);
    
    echo "Migration executed successfully. Admins table created and default admin user added.\n";
    echo "Default admin credentials:\n";
    echo "Email: admin@apexcoaching.com\n";
    echo "Password: ApexAdmin123!\n";
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
