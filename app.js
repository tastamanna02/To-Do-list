/**
 * TaskFlow - Minimal & Clean To-Do List Application
 * Built with Pure Vanilla JavaScript (ES6+)
 */


// 1. DOM Elements Selection
const todoForm = document.getElementById('todo-form');
const taskInput = document.getElementById('task-input');
const taskList = document.getElementById('task-list');
const emptyState = document.getElementById('empty-state');
const emptyMessage = document.getElementById('empty-message');

const progressFill = document.getElementById('progress-fill');
const progressText = document.getElementById('progress-text');
const taskCounter = document.getElementById('task-counter');
const itemsLeft = document.getElementById('items-left');

const filterTabs = document.querySelectorAll('.filter-tab');
const clearBtn = document.getElementById('clear-btn');
const themeBtn = document.getElementById('theme-btn');
const dateDisplay = document.getElementById('date-display');

// 2. Application State
const STORAGE_KEY = 'minimal_todo_tasks';
const THEME_KEY = 'minimal_todo_theme';

let tasks = [];
let currentFilter = 'all'; // 'all' | 'active' | 'completed'

// 3. App Initialization
document.addEventListener('DOMContentLoaded', () => {
  displayDate();
  loadTheme();
  loadTasks();
  setupEvents();
});

// Display Current Date (e.g. "Saturday, Sep 26")
function displayDate() {
  const options = { weekday: 'long', month: 'short', day: 'numeric' };
  dateDisplay.textContent = new Date().toLocaleDateString('en-US', options);
}

// 4. LocalStorage Functions
function saveTasks() {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(tasks));
}

function loadTasks() {
  const saved = localStorage.getItem(STORAGE_KEY);
  if (saved) {
    try {
      tasks = JSON.parse(saved);
    } catch (e) {
      tasks = [];
    }
  } else {
    // Default starter tasks
    tasks = [
      { id: '1', text: 'Prepare project presentation', completed: false },
      { id: '2', text: 'Review JavaScript CRUD logic', completed: true },
      { id: '3', text: 'Complete weekly tasks', completed: false }
    ];
    saveTasks();
  }
  render();
}

// 5. CRUD Operations

// Add a Task
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

// Toggle Task Status (Complete / Incomplete)
function toggleTask(id) {
  const task = tasks.find(t => t.id === id);
  if (task) {
    task.completed = !task.completed;
    saveTasks();
    render();
  }
}

// Delete Single Task
function deleteTask(id) {
  tasks = tasks.filter(t => t.id !== id);
  saveTasks();
  render();
}

// Clear All Completed Tasks
function clearCompleted() {
  tasks = tasks.filter(t => !t.completed);
  saveTasks();
  render();
}

// 6. Render Logic
function render() {
  // 1. Calculate Progress
  const total = tasks.length;
  const completed = tasks.filter(t => t.completed).length;
  const active = total - completed;
  const percentage = total === 0 ? 0 : Math.round((completed / total) * 100);

  progressFill.style.width = `${percentage}%`;
  progressText.textContent = `${percentage}% completed`;
  taskCounter.textContent = `${completed} of ${total} done`;
  itemsLeft.textContent = `${active} item${active === 1 ? '' : 's'} left`;

  // 2. Filter Tasks
  let filtered = tasks;
  if (currentFilter === 'active') {
    filtered = tasks.filter(t => !t.completed);
  } else if (currentFilter === 'completed') {
    filtered = tasks.filter(t => t.completed);
  }

  // 3. Render List
  taskList.innerHTML = '';

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

    filtered.forEach(task => {
      const li = document.createElement('li');
      li.className = `task-item ${task.completed ? 'completed' : ''}`;

      li.innerHTML = `
        <div class="task-left">
          <label class="task-checkbox" title="Mark complete">
            <input type="checkbox" ${task.completed ? 'checked' : ''}>
            <span class="checkbox-circle">
              <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                <polyline points="2.5 6 4.8 9 9.5 3.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </label>
          <span class="task-text">${escapeHTML(task.text)}</span>
        </div>
        <button class="delete-btn" title="Delete">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        </button>
      `;

      // Event: Checkbox toggle
      const checkbox = li.querySelector('input[type="checkbox"]');
      checkbox.addEventListener('change', () => toggleTask(task.id));

      // Event: Delete click
      const deleteBtn = li.querySelector('.delete-btn');
      deleteBtn.addEventListener('click', () => deleteTask(task.id));

      taskList.appendChild(li);
    });
  }
}

// 7. Security: Escape HTML helper
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

// 8. Theme Switcher (Light / Dark)
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

// 9. Event Listeners
function setupEvents() {
  // Form submission
  todoForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const text = taskInput.value.trim();
    if (text) {
      addTask(text);
      taskInput.value = '';
    }
  });

  // Filter tabs
  filterTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      filterTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      currentFilter = tab.dataset.filter;
      render();
    });
  });

  // Clear completed button
  clearBtn.addEventListener('click', clearCompleted);

  // Theme toggle
  themeBtn.addEventListener('click', toggleTheme);
}
