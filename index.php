<?php
/**
 * Minimal & Modern To-Do List Application
 * Connected to MySQL Database: web_class
 */

require_once 'db_connect.php';

// Automatically create 'tasks' table if it doesn't already exist
$tableQuery = "CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `text` VARCHAR(255) NOT NULL,
    `completed` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($tableQuery);

// -------------------------------------------------------------
// 1. Handle Actions (Add, Delete, Toggle, Clear Completed)
// -------------------------------------------------------------

// Add a new task (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_text'])) {
    $text = trim($_POST['task_text']);
    if (!empty($text)) {
        $stmt = $conn->prepare("INSERT INTO `tasks` (`text`, `completed`) VALUES (?, 0)");
        $stmt->bind_param("s", $text);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: index.php");
    exit;
}

// Delete task by ID (GET)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM `tasks` WHERE `id` = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
}

// Toggle task status (Completed / Active) (GET)
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $conn->prepare("UPDATE `tasks` SET `completed` = 1 - `completed` WHERE `id` = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
}

// Clear all completed tasks (GET)
if (isset($_GET['clear_completed'])) {
    $conn->query("DELETE FROM `tasks` WHERE `completed` = 1");
    header("Location: index.php");
    exit;
}

// -------------------------------------------------------------
// 2. Fetch Tasks & Compute Statistics
// -------------------------------------------------------------
$tasks = [];
$res = $conn->query("SELECT * FROM `tasks` ORDER BY `id` DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $tasks[] = $row;
    }
}

$totalTasks = count($tasks);
$completedTasks = 0;
foreach ($tasks as $task) {
    if ($task['completed'] == 1) {
        $completedTasks++;
    }
}
$activeTasks = $totalTasks - $completedTasks;
$percent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Minimal To-Do • Web Class</title>
  
  <!-- Modern Typography -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  
  <!-- Stylesheet -->
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="todo-wrapper">
    <!-- Header -->
    <header class="todo-header">
      <div class="header-left">
        <h1 class="title">Today</h1>
        <p class="date" id="date-display"><?= date('l, M j') ?></p>
      </div>
      <button id="theme-btn" class="theme-toggle" title="Switch Theme" aria-label="Toggle theme">
        <!-- Sun Icon -->
        <svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="5"></circle>
          <line x1="12" y1="1" x2="12" y2="3"></line>
          <line x1="12" y1="21" x2="12" y2="23"></line>
          <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
          <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
          <line x1="1" y1="12" x2="3" y2="12"></line>
          <line x1="21" y1="12" x2="23" y2="12"></line>
          <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
          <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
        <!-- Moon Icon -->
        <svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
      </button>
    </header>

    <!-- Dynamic Progress Bar -->
    <div class="progress-section">
      <div class="progress-header">
        <span class="progress-text"><?= $percent ?>% completed</span>
        <span class="task-counter"><?= $completedTasks ?> of <?= $totalTasks ?> done</span>
      </div>
      <div class="progress-bar" role="progressbar" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-fill" style="width: <?= $percent ?>%;"></div>
      </div>
    </div>

    <!-- Input Form (Add Task) -->
    <form action="index.php" method="POST" class="todo-form">
      <div class="input-box">
        <input 
          type="text" 
          name="task_text" 
          placeholder="Add a new task..." 
          autocomplete="off"
          required
          autofocus
        />
        <button type="submit" class="add-button" title="Add Task">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
        </button>
      </div>
    </form>

    <!-- Filters & Actions -->
    <div class="filter-bar">
      <div class="filter-tabs" role="tablist">
        <button type="button" class="filter-tab active" data-filter="all">All</button>
        <button type="button" class="filter-tab" data-filter="active">Active</button>
        <button type="button" class="filter-tab" data-filter="completed">Completed</button>
      </div>
      <?php if ($completedTasks > 0): ?>
        <a href="index.php?clear_completed=1" class="clear-btn" title="Clear completed tasks">Clear completed</a>
      <?php endif; ?>
    </div>

    <!-- Task List -->
    <ul id="task-list" class="task-list">
      <?php if ($totalTasks === 0): ?>
        <div class="empty-state" style="display: block;">
          <p>No tasks yet. Enjoy your day! 🌿</p>
        </div>
      <?php else: ?>
        <?php foreach ($tasks as $task): ?>
          <li class="task-item <?= $task['completed'] ? 'completed' : '' ?>" data-status="<?= $task['completed'] ? 'completed' : 'active' ?>">
            <div class="task-left">
              <!-- Checkbox Toggle Link -->
              <a href="index.php?toggle=<?= $task['id'] ?>" class="task-checkbox" title="<?= $task['completed'] ? 'Mark active' : 'Mark complete' ?>">
                <input type="checkbox" <?= $task['completed'] ? 'checked' : '' ?> onclick="window.location.href='index.php?toggle=<?= $task['id'] ?>'">
                <span class="checkbox-circle">
                  <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                    <polyline points="2.5 6 4.8 9 9.5 3.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
              </a>
              <!-- Task Title -->
              <span class="task-text"><?= htmlspecialchars($task['text']) ?></span>
            </div>
            
            <!-- Delete Button (Requested Feature) -->
            <a 
              href="index.php?delete=<?= $task['id'] ?>" 
              class="delete-btn" 
              title="Delete task" 
              aria-label="Delete task"
              onclick="return confirm('Are you sure you want to delete this task?');"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
              </svg>
            </a>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>

    <!-- Filter Empty State (Hidden by default, shown when filtering yields 0 items) -->
    <div id="filter-empty" class="empty-state" style="display: none;">
      <p id="filter-empty-message">No matching tasks found.</p>
    </div>

    <!-- Footer Status -->
    <footer class="todo-footer">
      <span><?= $activeTasks ?> item<?= $activeTasks === 1 ? '' : 's' ?> left</span>
      <span class="auto-save">✓ Synced with MySQL Database (web_class)</span>
    </footer>
  </div>

  <!-- Interactive Client-side Script -->
  <script>
    // 1. Theme Management (Light / Dark Mode)
    const THEME_KEY = 'minimal_todo_theme';
    const themeBtn = document.getElementById('theme-btn');

    function applyTheme() {
      const savedTheme = localStorage.getItem(THEME_KEY) || 'light';
      document.documentElement.setAttribute('data-theme', savedTheme);
    }

    themeBtn.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem(THEME_KEY, newTheme);
    });

    applyTheme();

    // 2. Instant Filter Tabs (All / Active / Completed)
    const filterTabs = document.querySelectorAll('.filter-tab');
    const taskItems = document.querySelectorAll('.task-item');
    const filterEmpty = document.getElementById('filter-empty');
    const filterEmptyMsg = document.getElementById('filter-empty-message');

    filterTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        filterTabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        const filter = tab.getAttribute('data-filter');
        let visibleCount = 0;

        taskItems.forEach(item => {
          const status = item.getAttribute('data-status');
          if (filter === 'all' || filter === status) {
            item.style.display = 'flex';
            visibleCount++;
          } else {
            item.style.display = 'none';
          }
        });

        if (visibleCount === 0 && taskItems.length > 0) {
          filterEmpty.style.display = 'block';
          if (filter === 'active') {
            filterEmptyMsg.textContent = 'All active tasks completed! 🎉';
          } else if (filter === 'completed') {
            filterEmptyMsg.textContent = 'No completed tasks yet.';
          }
        } else {
          filterEmpty.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>
