# Database Usage Guide

This project now includes a comprehensive MariaDB database layer with reusable helper functions for all database operations.

## Configuration

Database configuration is in `config/config.php`:
- `DB_TYPE`: Database type (set to 'mariadb' for MariaDB)
- `DB_HOST`: Database host
- `DB_PORT`: Database port
- `DB_NAME`: Database name
- `DB_USER`: Database username
- `DB_PASS`: Database password
- `DB_CHARSET`: Character set (utf8mb4)

## Available Helper Functions

All helper functions are available in `includes/helpers.php` after including it.

### Basic Database Connection

```php
// Get database instance
$db = db();
```

### SELECT Operations

```php
// Select all records
$results = db_select('users');

// Select specific columns
$results = db_select('users', 'id, name, email');

// Select with WHERE clause
$results = db_select('users', '*', 'status = :status', ['status' => 'active']);

// Select with ORDER BY
$results = db_select('users', '*', '', [], 'created_at DESC');

// Select with LIMIT
$results = db_select('users', '*', '', [], '', '10');

// Combined usage
$results = db_select('users', 'id, name', 'status = :status', ['status' => 'active'], 'name ASC', '10');
```

### INSERT Operations

```php
// Insert single record
$insertId = db_insert('users', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'status' => 'active'
]);

if ($insertId) {
    echo "Inserted with ID: " . $insertId;
}
```

### UPDATE Operations

```php
// Update records
$affectedRows = db_update('users', 
    ['status' => 'inactive'], 
    'id = :id', 
    ['id' => 5]
);

echo "Updated $affectedRows rows";
```

### DELETE Operations

```php
// Delete records
$affectedRows = db_delete('users', 'id = :id', ['id' => 5]);
echo "Deleted $affectedRows rows";
```

### COUNT Operations

```php
// Count all records
$total = db_count('users');

// Count with conditions
$activeUsers = db_count('users', 'status = :status', ['status' => 'active']);
```

### PAGINATION

```php
// Paginate results
$result = db_paginate('users', 1, 10, '*', '', [], 'created_at DESC');

// Access data
$users = $result['data'];
$pagination = $result['pagination'];

// Pagination info
echo "Current page: " . $pagination['current_page'];
echo "Total pages: " . $pagination['total_pages'];
echo "Has next: " . ($pagination['has_next'] ? 'Yes' : 'No');
echo "Has prev: " . ($pagination['has_prev'] ? 'Yes' : 'No');
```

### FILTERING

```php
// Filter with exact match
$results = db_filter('users', ['status' => 'active']);

// Filter with multiple conditions
$results = db_filter('users', [
    'status' => 'active',
    'role' => 'admin'
]);

// Filter with LIKE (wildcard search)
$results = db_filter('users', ['name' => '%John%']);

// Filter with IN clause
$results = db_filter('users', ['role' => ['admin', 'moderator']]);

// Filter with ordering and limit
$results = db_filter('users', ['status' => 'active'], '*', 'created_at DESC', '10');
```

### SEARCHING

```php
// Search across multiple columns
$results = db_search('users', ['name', 'email'], 'john');

// Search with conditions
$results = db_search('users', 'name', 'john', 'status = :status', ['status' => 'active']);

// Search with ordering and limit
$results = db_search('users', ['name', 'email'], 'john', '', [], 'name ASC', '10');
```

### RAW QUERIES

```php
// Execute raw query with parameters
$stmt = db_query("SELECT * FROM users WHERE id = :id", ['id' => 5]);

// Fetch all results
$results = db_fetch_all("SELECT * FROM users WHERE status = :status", ['status' => 'active']);

// Fetch single result
$user = db_fetch_one("SELECT * FROM users WHERE id = :id", ['id' => 5]);
```

### TRANSACTIONS

```php
// Execute transaction
$result = db_transaction(function($db) {
    $userId = $db->insert('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ]);
    
    $db->insert('user_profiles', [
        'user_id' => $userId,
        'bio' => 'New user'
    ]);
    
    return $userId;
});

if ($result) {
    echo "Transaction completed successfully";
}
```

## Direct Database Class Usage

You can also use the Database class directly for more control:

```php
$db = Database::getInstance();

// Get PDO connection
$pdo = $db->getConnection();

// Get last error
$error = $db->getLastError();

// Manual transaction control
$db->beginTransaction();
try {
    $db->insert('users', $data);
    $db->commit();
} catch (Exception $e) {
    $db->rollback();
}
```

## Error Handling

All database functions include comprehensive error handling:
- Errors are logged to the error log
- Functions return false on failure
- Use `db()->getLastError()` to retrieve the last error message
- In development mode, detailed errors are shown
- In production, generic error messages are shown

## Best Practices

1. Always use parameterized queries to prevent SQL injection
2. Check return values (false indicates failure)
3. Use transactions for multiple related operations
4. Use pagination for large datasets
5. Use filtering and searching instead of complex WHERE clauses
6. Index frequently searched columns for better performance

## Example: Complete CRUD Operations

```php
// CREATE
$userId = db_insert('users', [
    'name' => 'Jane Doe',
    'email' => 'jane@example.com',
    'status' => 'active'
]);

// READ
$user = db_fetch_one("SELECT * FROM users WHERE id = :id", ['id' => $userId]);
$allUsers = db_select('users');

// UPDATE
db_update('users', 
    ['status' => 'inactive'], 
    'id = :id', 
    ['id' => $userId]
);

// DELETE
db_delete('users', 'id = :id', ['id' => $userId]);
```

## Migration from Direct Queries

Replace your existing database code with these helper functions:

**Before:**
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE status = ?");
$stmt->execute(['active']);
$users = $stmt->fetchAll();
```

**After:**
```php
$users = db_select('users', '*', 'status = :status', ['status' => 'active']);
```

All future pages should use these reusable functions for consistency and maintainability.