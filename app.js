/**
 * TaskFlow - Minimal & Clean To-Do List Application
 * Built with Pure Vanilla JavaScript (ES6+)
 */

// ============================================================================
// 1. DOM Elements Selection
// ============================================================================
const todoForm = document.getElementById('todo-form');
const taskInput = document.getElementById('task-input');
const taskList = document.getElementById('task-list');
const emptyState = document.getElementById('empty-state');
const emptyMessage = document.getElementById('empty-message');

// Progress Bar Elements
const progressBar = document.getElementById('progress-bar');
const progressFill = document.getElementById('progress-fill');
const progressText = document.getElementById('progress-text');
const taskCounter = document.getElementById('task-counter');
const itemsLeft = document.getElementById('items-left');

// Filter & Action Elements
const filterTabs = document.querySelectorAll('.filter-tab');
const clearBtn = document.getElementById('clear-btn');
const themeBtn = document.getElementById('theme-btn');
const dateDisplay = document.getElementById('date-display');

// ============================================================================
// 2. Application State & Storage Keys
// ============================================================================
const STORAGE_KEY = 'minimal_todo_tasks';
const THEME_KEY = 'minimal_todo_theme';

let tasks = [];
let currentFilter = 'all'; // 'all' | 'active' | 'completed'

// ============================================================================
// 3. Application Lifecycle
// ============================================================================
document.addEventListener('DOMContentLoaded', () => {
  displayDate();
  loadTheme();
  loadTasks();
  setupEvents();
});

// Display Current Formatted Date (e.g. "Tuesday, Sep 29")
function displayDate() {
  const options = { weekday: 'long', month: 'short', day: 'numeric' };
  dateDisplay.textContent = new Date().toLocaleDateString('en-US', options);
}

// ============================================================================
// FEATURE 1: LOCAL STORAGE (Save & Retrieve Tasks)
// ============================================================================

/**
 * Persists the current tasks array into the browser's localStorage.
 * Wrapped in try/catch to handle potential quota limits or restricted modes.
 */
function saveTasks() {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(tasks));
  } catch (error) {
    console.error('Failed to save tasks to localStorage:', error);
  }
}

/**
 * Retrieves stored tasks from browser's localStorage and populates state.
 * If no data exists yet, seeds friendly initial tasks.
 */
function loadTasks() {
  try {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved !== null) {
      tasks = JSON.parse(saved);
    } else {
      // Default starter tasks for first-time visitors
      tasks = [
        { id: '1', text: 'Prepare project presentation', completed: false },
        { id: '2', text: 'Review JavaScript CRUD logic', completed: true },
        { id: '3', text: 'Complete weekly tasks', completed: false }
      ];
      saveTasks();
    }
  } catch (error) {
    console.error('Failed to parse tasks from localStorage:', error);
    tasks = [];
  }
  render();
}

// ============================================================================
// FEATURE 2: TASK FILTERING ("All", "Pending/Active", "Completed")
// ============================================================================

/**
 * Returns filtered subset of tasks according to the active filter state.
 * @returns {Array} Filtered tasks array
 */
function getFilteredTasks() {
  if (currentFilter === 'active') {
    return tasks.filter(task => !task.completed);
  }
  if (currentFilter === 'completed') {
    return tasks.filter(task => task.completed);
  }
  return tasks; // 'all'
}

/**
 * Changes active filter, updates ARIA states and CSS classes on tab buttons.
 * @param {string} filter - 'all' | 'active' | 'completed'
 */
function setFilter(filter) {
  currentFilter = filter;
  filterTabs.forEach(tab => {
    const isActive = tab.dataset.filter === filter;
    tab.classList.toggle('active', isActive);
    tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
  });
  render();
}

// ============================================================================
// FEATURE 3: DYNAMIC TASK PROGRESS BAR
// ============================================================================

/**
 * Calculates completion ratio and updates progress bar width and counter text.
 */
function updateProgressBar() {
  const total = tasks.length;
  const completed = tasks.filter(task => task.completed).length;
  const active = total - completed;
  
  // Calculate percentage (0 to 100)
  const percentage = total === 0 ? 0 : Math.round((completed / total) * 100);

  // Update visual fill width and accessibility attributes
  progressFill.style.width = `${percentage}%`;
  if (progressBar) {
    progressBar.setAttribute('aria-valuenow', percentage);
  }

  // Update label indicators
  progressText.textContent = `${percentage}% completed`;
  taskCounter.textContent = `${completed} of ${total} done`;
  itemsLeft.textContent = `${active} item${active === 1 ? '' : 's'} left`;
}

// ============================================================================
// 4. CRUD Operations
// ============================================================================

/**
 * Adds a new task object to the state and triggers persistence & re-render.
 * @param {string} text - The task description
 */
function addTask(text) {
  const newTask = {
    id: Date.now().toString(),
    text: text.trim(),
    completed: false
  };
  tasks.unshift(newTask);
  saveTasks();
  render();
}

/**
 * Toggles a task's completed status by its unique ID.
 * @param {string} id - Unique task identifier
 */
function toggleTask(id) {
  const task = tasks.find(t => t.id === id);
  if (task) {
    task.completed = !task.completed;
    saveTasks();
    render();
  }
}

/**
 * Deletes a single task by its unique ID.
 * @param {string} id - Unique task identifier
 */
function deleteTask(id) {
  tasks = tasks.filter(t => t.id !== id);
  saveTasks();
  render();
}

/**
 * Clears all completed tasks from state.
 */
function clearCompleted() {
  tasks = tasks.filter(t => !t.completed);
  saveTasks();
  render();
}

// ============================================================================
// 5. Render Logic
// ============================================================================

/**
 * Renders the filtered task list, updates progress bar, and updates empty state.
 */
function render() {
  // Update progress bar & counters
  updateProgressBar();

  // Get current filtered list
  const filtered = getFilteredTasks();

  // Clear previous rendered items
  taskList.innerHTML = '';

  // Handle empty state messages according to active filter
  if (filtered.length === 0) {
    emptyState.style.display = 'block';
    if (currentFilter === 'active') {
      emptyMessage.textContent = 'All active tasks completed! 🎉';
    } else if (currentFilter === 'completed') {
      emptyMessage.textContent = 'No completed tasks yet.';
    } else {
      emptyMessage.textContent = 'No tasks yet. Add one above! 🌿';
    }
  } else {
    emptyState.style.display = 'none';

    // Build DOM elements for each task
    filtered.forEach(task => {
      const li = document.createElement('li');
      li.className = `task-item ${task.completed ? 'completed' : ''}`;

      li.innerHTML = `
        <div class="task-left">
          <label class="task-checkbox" title="Mark complete">
            <input type="checkbox" ${task.completed ? 'checked' : ''} aria-label="Toggle task completion">
            <span class="checkbox-circle">
              <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                <polyline points="2.5 6 4.8 9 9.5 3.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </label>
          <span class="task-text">${escapeHTML(task.text)}</span>
        </div>
        <button class="delete-btn" title="Delete task" aria-label="Delete task">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        </button>
      `;

      // Event: Toggle status
      const checkbox = li.querySelector('input[type="checkbox"]');
      checkbox.addEventListener('change', () => toggleTask(task.id));

      // Event: Delete task
      const deleteBtn = li.querySelector('.delete-btn');
      deleteBtn.addEventListener('click', () => deleteTask(task.id));

      taskList.appendChild(li);
    });
  }
}

// Security: Escape HTML helper to prevent XSS injection
function escapeHTML(str) {
  return str.replace(/[&<>'"]/g, 
    tag => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      "'": '&#39;',
      '"': '&quot;'
    }[tag] || tag)
  );
}

// ============================================================================
// 6. Theme Switcher (Light / Dark Mode)
// ============================================================================
function loadTheme() {
  const saved = localStorage.getItem(THEME_KEY) || 'light';
  document.documentElement.setAttribute('data-theme', saved);
}

function toggleTheme() {
  const current = document.documentElement.getAttribute('data-theme') || 'light';
  const next = current === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  localStorage.setItem(THEME_KEY, next);
}

// ============================================================================
// 7. Event Listeners Setup
// ============================================================================
function setupEvents() {
  // Form submission (Add task)
  todoForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const text = taskInput.value.trim();
    if (text) {
      addTask(text);
      taskInput.value = '';
    }
  });

  // Filter tabs click
  filterTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      setFilter(tab.dataset.filter);
    });
  });

  // Clear completed tasks button
  clearBtn.addEventListener('click', clearCompleted);

  // Theme toggle button
  themeBtn.addEventListener('click', toggleTheme);
}
