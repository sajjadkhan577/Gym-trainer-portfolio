<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/database.php';

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Read the schema file
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    
    // Split by semicolon to get individual statements
    $statements = explode(';', $schema);
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                $successCount++;
            } catch (PDOException $e) {
                // Ignore duplicate table and column errors
                if (strpos($e->getMessage(), 'already exists') === false && strpos($e->getMessage(), 'duplicate column') === false) {
                    echo "Error executing statement: " . $e->getMessage() . "\n";
                    $errorCount++;
                }
            }
        }
    }
    
    echo "Database setup completed!\n";
    echo "Successful statements: $successCount\n";
    echo "Failed statements: $errorCount\n";
    
} catch (Exception $e) {
    echo "Setup failed: " . $e->getMessage() . "\n";
}
