<?php

require_once __DIR__ . '/../config/config.php';

class Database {
    private static $instance = null;
    private $pdo;
    private $lastError = null;

    private function __construct() {
        try {
            $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=%s',
                $dbType,
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => true
            ];

            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            $this->logError('Database connection failed: ' . $e->getMessage());
            if (ENVIRONMENT === 'development') {
                die('Database connection failed: ' . $e->getMessage());
            } else {
                die('Database connection failed. Please try again later.');
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function getLastError() {
        return $this->lastError;
    }

    private function logError($message) {
        $this->lastError = $message;
        error_log('[Database Error] ' . $message);
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logError('Query failed: ' . $e->getMessage() . ' | SQL: ' . $sql);
            throw $e;
        }
    }

    public function fetchAll($sql, $params = []) {
        try {
            return $this->query($sql, $params)->fetchAll();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function fetchOne($sql, $params = []) {
        try {
            return $this->query($sql, $params)->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function select($table, $columns = '*', $where = '', $params = [], $orderBy = '', $limit = '') {
        try {
            $sql = "SELECT {$columns} FROM {$table}";
            
            if (!empty($where)) {
                $sql .= " WHERE {$where}";
            }
            
            if (!empty($orderBy)) {
                $sql .= " ORDER BY {$orderBy}";
            }
            
            if (!empty($limit)) {
                $sql .= " LIMIT {$limit}";
            }
            
            return $this->fetchAll($sql, $params);
        } catch (PDOException $e) {
            $this->logError('Select failed: ' . $e->getMessage());
            return false;
        }
    }

    public function count($table, $where = '', $params = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$table}";
            
            if (!empty($where)) {
                $sql .= " WHERE {$where}";
            }
            
            $result = $this->fetchOne($sql, $params);
            return $result ? (int)$result['count'] : 0;
        } catch (PDOException $e) {
            $this->logError('Count failed: ' . $e->getMessage());
            return 0;
        }
    }

    public function insert($table, $data) {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
            $this->query($sql, $data);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->logError('Insert failed: ' . $e->getMessage());
            return false;
        }
    }

    public function update($table, $data, $where, $whereParams = []) {
        try {
            $set = [];
            foreach (array_keys($data) as $column) {
                $set[] = "{$column} = :{$column}";
            }
            $setClause = implode(', ', $set);
            $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
            $params = array_merge($data, $whereParams);
            return $this->query($sql, $params)->rowCount();
        } catch (PDOException $e) {
            $this->logError('Update failed: ' . $e->getMessage());
            return false;
        }
    }

    public function delete($table, $where, $whereParams = []) {
        try {
            $sql = "DELETE FROM {$table} WHERE {$where}";
            return $this->query($sql, $whereParams)->rowCount();
        } catch (PDOException $e) {
            $this->logError('Delete failed: ' . $e->getMessage());
            return false;
        }
    }

    public function paginate($table, $page = 1, $perPage = 10, $columns = '*', $where = '', $params = [], $orderBy = '') {
        try {
            $page = max(1, (int)$page);
            $perPage = max(1, (int)$perPage);
            $offset = ($page - 1) * $perPage;

            $total = $this->count($table, $where, $params);
            $totalPages = ceil($total / $perPage);

            $data = $this->select($table, $columns, $where, $params, $orderBy, $offset . ', ' . $perPage);

            return [
                'data' => $data,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages,
                    'has_next' => $page < $totalPages,
                    'has_prev' => $page > 1
                ]
            ];
        } catch (PDOException $e) {
            $this->logError('Pagination failed: ' . $e->getMessage());
            return false;
        }
    }

    public function filter($table, $filters = [], $columns = '*', $orderBy = '', $limit = '') {
        try {
            $where = '';
            $params = [];

            if (!empty($filters)) {
                $conditions = [];
                foreach ($filters as $column => $value) {
                    if (is_array($value)) {
                        $placeholders = [];
                        foreach ($value as $i => $val) {
                            $paramName = $column . '_' . $i;
                            $params[$paramName] = $val;
                            $placeholders[] = ':' . $paramName;
                        }
                        $conditions[] = "{$column} IN (" . implode(', ', $placeholders) . ")";
                    } elseif (strpos($value, '%') !== false) {
                        $params[$column] = $value;
                        $conditions[] = "{$column} LIKE :{$column}";
                    } else {
                        $params[$column] = $value;
                        $conditions[] = "{$column} = :{$column}";
                    }
                }
                $where = implode(' AND ', $conditions);
            }

            return $this->select($table, $columns, $where, $params, $orderBy, $limit);
        } catch (PDOException $e) {
            $this->logError('Filter failed: ' . $e->getMessage());
            return false;
        }
    }

    public function search($table, $columns, $searchTerm, $where = '', $params = [], $orderBy = '', $limit = '') {
        try {
            if (!is_array($columns)) {
                $columns = [$columns];
            }

            $searchConditions = [];
            $searchParams = [];

            foreach ($columns as $column) {
                $paramName = 'search_' . $column;
                $searchConditions[] = "{$column} LIKE :{$paramName}";
                $searchParams[$paramName] = '%' . $searchTerm . '%';
            }

            $searchWhere = implode(' OR ', $searchConditions);

            if (!empty($where)) {
                $where = "({$where}) AND ({$searchWhere})";
            } else {
                $where = $searchWhere;
            }

            $allParams = array_merge($params, $searchParams);

            return $this->select($table, '*', $where, $allParams, $orderBy, $limit);
        } catch (PDOException $e) {
            $this->logError('Search failed: ' . $e->getMessage());
            return false;
        }
    }

    public function beginTransaction() {
        try {
            return $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            $this->logError('Begin transaction failed: ' . $e->getMessage());
            return false;
        }
    }

    public function commit() {
        try {
            return $this->pdo->commit();
        } catch (PDOException $e) {
            $this->logError('Commit failed: ' . $e->getMessage());
            return false;
        }
    }

    public function rollback() {
        try {
            return $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->logError('Rollback failed: ' . $e->getMessage());
            return false;
        }
    }

    public function executeTransaction(callable $callback) {
        try {
            $this->beginTransaction();
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (Exception $e) {
            $this->rollback();
            $this->logError('Transaction failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }

    private function __clone() {}

    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
