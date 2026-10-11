<?php
/**
 * TaskFlow - Minimal & Clean To-Do List Application
 * Built with Pure PHP, HTML5 & CSS3 (Zero JavaScript)
 */

require_once __DIR__ . '/db.php';

// ============================================================================
// 1. Theme Management (Stored via 30-day Cookie)
// ============================================================================
$theme = $_COOKIE['theme'] ?? 'light';
if (isset($_GET['toggle_theme'])) {
    $newTheme = ($theme === 'dark') ? 'light' : 'dark';
    setcookie('theme', $newTheme, time() + (86400 * 30), '/');
    $filterParam = isset($_GET['filter']) ? '?filter=' . urlencode($_GET['filter']) : '';
    header("Location: index.php" . $filterParam);
    exit;
}

// Current Filter state ('all' | 'active' | 'completed')
$currentFilter = $_GET['filter'] ?? 'all';
if (!in_array($currentFilter, ['all', 'active', 'completed'], true)) {
    $currentFilter = 'all';
}

// ============================================================================
// 2. Request Handling (POST / GET Actions with Post-Redirect-Get Pattern)
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $redirectFilter = $_POST['filter'] ?? $currentFilter;

    if ($action === 'add') {
        $taskText = trim($_POST['task_text'] ?? '');
        if ($taskText !== '') {
            addTask($taskText);
        }
    } elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            toggleTask($id);
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            deleteTask($id);
        }
    } elseif ($action === 'clear_completed') {
        clearCompletedTasks();
    }

    // PRG Pattern: Prevents form resubmission upon page refresh
    header("Location: index.php?filter=" . urlencode($redirectFilter));
    exit;
}

// Optional GET handlers (allows direct URL triggers if preferred)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = (int)($_GET['id'] ?? 0);

    if ($action === 'toggle' && $id > 0) {
        toggleTask($id);
    } elseif ($action === 'delete' && $id > 0) {
        deleteTask($id);
    }

    header("Location: index.php?filter=" . urlencode($currentFilter));
    exit;
}

// ============================================================================
// 3. Data Retrieval & Calculation
// ============================================================================
$allTasks = getAllTasks();
$totalCount = count($allTasks);
$completedCount = 0;
foreach ($allTasks as $t) {
    if (!empty($t['completed'])) {
        $completedCount++;
    }
}
$activeCount = $totalCount - $completedCount;
$percentage = ($totalCount > 0) ? (int)round(($completedCount / $totalCount) * 100) : 0;

$filteredTasks = getFilteredTasks($currentFilter);
$formattedDate = date('l, M j');
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= htmlspecialchars($theme, ENT_QUOTES, 'UTF-8') ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Minimal To-Do • Clean & Simple</title>
  
  <!-- Modern Clean Typography -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="todo-wrapper">
    <!-- Header -->
    <header class="todo-header">
      <div class="header-left">
        <h1 class="title">Today</h1>
        <p class="date" id="date-display"><?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8') ?></p>
      </div>
      <a href="index.php?toggle_theme=1&filter=<?= urlencode($currentFilter) ?>" id="theme-btn" class="theme-toggle" title="Switch Theme (Light/Dark)" aria-label="Toggle theme">
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
      </a>
    </header>

    <!-- Clean Progress Bar -->
    <div class="progress-section">
      <div class="progress-header">
        <span class="progress-text" id="progress-text"><?= $percentage ?>% completed</span>
        <span class="task-counter" id="task-counter"><?= $completedCount ?> of <?= $totalCount ?> done</span>
      </div>
      <div class="progress-bar" id="progress-bar" role="progressbar" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Task completion progress">
        <div class="progress-fill" id="progress-fill" style="width: <?= $percentage ?>%;"></div>
      </div>
    </div>

    <!-- Input Form -->
    <form id="todo-form" class="todo-form" method="POST" action="index.php">
      <input type="hidden" name="action" value="add" />
      <input type="hidden" name="filter" value="<?= htmlspecialchars($currentFilter, ENT_QUOTES, 'UTF-8') ?>" />
      <div class="input-box">
        <input 
          type="text" 
          name="task_text"
          id="task-input" 
          placeholder="Add a new task..." 
          autocomplete="off"
          required
          autofocus
        />
        <button type="submit" id="add-btn" class="add-button" title="Add Task" aria-label="Add task">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
        </button>
      </div>
    </form>

    <!-- Filters & Actions -->
    <div class="filter-bar">
      <div class="filter-tabs" role="tablist" aria-label="Task filters">
        <a href="index.php?filter=all" class="filter-tab <?= $currentFilter === 'all' ? 'active' : '' ?>" role="tab" aria-selected="<?= $currentFilter === 'all' ? 'true' : 'false' ?>">All</a>
        <a href="index.php?filter=active" class="filter-tab <?= $currentFilter === 'active' ? 'active' : '' ?>" role="tab" aria-selected="<?= $currentFilter === 'active' ? 'true' : 'false' ?>">Active</a>
        <a href="index.php?filter=completed" class="filter-tab <?= $currentFilter === 'completed' ? 'active' : '' ?>" role="tab" aria-selected="<?= $currentFilter === 'completed' ? 'true' : 'false' ?>">Completed</a>
      </div>
      <form method="POST" action="index.php" style="display:inline;">
        <input type="hidden" name="action" value="clear_completed" />
        <input type="hidden" name="filter" value="<?= htmlspecialchars($currentFilter, ENT_QUOTES, 'UTF-8') ?>" />
        <button type="submit" id="clear-btn" class="clear-btn" title="Clear completed tasks">Clear completed</button>
      </form>
    </div>

    <!-- Task List -->
    <?php if (empty($filteredTasks)): ?>
      <!-- Empty State -->
      <div id="empty-state" class="empty-state" style="display: block;">
        <p id="empty-message">
          <?php if ($currentFilter === 'active'): ?>
            All active tasks completed! 🎉
          <?php elseif ($currentFilter === 'completed'): ?>
            No completed tasks yet.
          <?php else: ?>
            No tasks yet. Enjoy your day! 🌿
          <?php endif; ?>
        </p>
      </div>
    <?php else: ?>
      <ul id="task-list" class="task-list">
        <?php foreach ($filteredTasks as $task): ?>
          <?php $isDone = !empty($task['completed']); ?>
          <li class="task-item <?= $isDone ? 'completed' : '' ?>">
            <div class="task-left">
              <form method="POST" action="index.php" style="display:inline-flex; align-items:center; margin:0;">
                <input type="hidden" name="action" value="toggle" />
                <input type="hidden" name="id" value="<?= (int)$task['id'] ?>" />
                <input type="hidden" name="filter" value="<?= htmlspecialchars($currentFilter, ENT_QUOTES, 'UTF-8') ?>" />
                <button type="submit" class="task-checkbox-btn <?= $isDone ? 'checked' : '' ?>" title="<?= $isDone ? 'Mark incomplete' : 'Mark complete' ?>" aria-label="Toggle task completion">
                  <span class="checkbox-circle">
                    <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                      <polyline points="2.5 6 4.8 9 9.5 3.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                </button>
              </form>
              <span class="task-text"><?= htmlspecialchars($task['text'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <form method="POST" action="index.php" class="delete-form">
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="<?= (int)$task['id'] ?>" />
              <input type="hidden" name="filter" value="<?= htmlspecialchars($currentFilter, ENT_QUOTES, 'UTF-8') ?>" />
              <button type="submit" class="delete-btn" title="Delete task" aria-label="Delete task">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
              </button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <!-- Footer Status -->
    <footer class="todo-footer">
      <span id="items-left"><?= $activeCount ?> item<?= $activeCount === 1 ? '' : 's' ?> left</span>
      <span class="auto-save">✓ Stored securely with PHP & SQLite</span>
    </footer>
  </div>
</body>
</html>
