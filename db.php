<?php
/**
 * Database Handler for Minimal To-Do List Application
 * Supports SQLite (Default, Zero-Config) and MySQL (for Lab/Server Deployment)
 */

// Configuration
define('DB_DRIVER', 'sqlite'); // 'sqlite' or 'mysql'

// MySQL Credentials (used only if DB_DRIVER is set to 'mysql')
define('DB_HOST', 'localhost');
define('DB_NAME', 'todo_db');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Returns a PDO database connection instance.
 *
 * @return PDO
 */
function getDbConnection() {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    try {
        if (DB_DRIVER === 'sqlite') {
            $dbPath = __DIR__ . '/tasks.db';
            $pdo = new PDO("sqlite:" . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Initialize SQLite schema
            initSqliteDb($pdo);
        } else {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        die("Database Connection Error: " . htmlspecialchars($e->getMessage()));
    }

    return $pdo;
}

/**
 * Automatically creates the table and seeds starter tasks for SQLite on first launch.
 *
 * @param PDO $pdo
 */
function initSqliteDb($pdo) {
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            text TEXT NOT NULL,
            completed INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ";
    $pdo->exec($createTableQuery);

    // Seed default tasks if empty
    $countStmt = $pdo->query("SELECT COUNT(*) AS total FROM tasks");
    $rowCount = (int)$countStmt->fetchColumn();

    if ($rowCount === 0) {
        $seedTasks = [
            ['text' => 'Prepare project presentation', 'completed' => 0],
            ['text' => 'Review PHP CRUD logic', 'completed' => 1],
            ['text' => 'Complete weekly tasks', 'completed' => 0],
        ];

        $insertStmt = $pdo->prepare("INSERT INTO tasks (text, completed) VALUES (:text, :completed)");
        foreach ($seedTasks as $task) {
            $insertStmt->execute([
                ':text' => $task['text'],
                ':completed' => $task['completed']
            ]);
        }
    }
}

/**
 * Fetches all tasks from the database.
 *
 * @return array
 */
function getAllTasks() {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");
    return $stmt->fetchAll();
}

/**
 * Fetches tasks based on the active filter ('all', 'active', 'completed').
 *
 * @param string $filter
 * @return array
 */
function getFilteredTasks($filter = 'all') {
    $pdo = getDbConnection();

    if ($filter === 'active') {
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE completed = 0 ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    if ($filter === 'completed') {
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE completed = 1 ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Default 'all'
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");
    return $stmt->fetchAll();
}

/**
 * Adds a new task to the database.
 *
 * @param string $text
 * @return bool
 */
function addTask($text) {
    $trimmed = trim($text);
    if ($trimmed === '') {
        return false;
    }

    $pdo = getDbConnection();
    $stmt = $pdo->prepare("INSERT INTO tasks (text, completed) VALUES (:text, 0)");
    return $stmt->execute([':text' => $trimmed]);
}

/**
 * Toggles a task's completion status.
 *
 * @param int $id
 * @return bool
 */
function toggleTask($id) {
    $pdo = getDbConnection();
    $id = (int)$id;

    $stmt = $pdo->prepare("
        UPDATE tasks 
        SET completed = CASE WHEN completed = 1 THEN 0 ELSE 1 END 
        WHERE id = :id
    ");
    return $stmt->execute([':id' => $id]);
}

/**
 * Deletes a single task by ID.
 *
 * @param int $id
 * @return bool
 */
function deleteTask($id) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    return $stmt->execute([':id' => (int)$id]);
}

/**
 * Clears all completed tasks.
 *
 * @return bool
 */
function clearCompletedTasks() {
    $pdo = getDbConnection();
    $stmt = $pdo->query("DELETE FROM tasks WHERE completed = 1");
    return (bool)$stmt;
}
