# Minimal To-Do List Web Application 📝

A sleek, modern, and distraction-free To-Do List web application built using **pure HTML5, CSS3, and Vanilla JavaScript (ES6+)**. Designed with an Apple / Things 3 minimalist aesthetic, smooth micro-interactions, responsive progress tracking, and offline persistence.

🌐 **Live Demo:** [https://tastamanna02.github.io/To-Do-list/](https://tastamanna02.github.io/To-Do-list/)  
💻 **Repository:** [https://github.com/tastamanna02/To-Do-list](https://github.com/tastamanna02/To-Do-list)

---

## ✨ Key Features

### 1. 💾 Local Storage Persistence
- Automatically saves tasks to the browser's `localStorage` whenever a task is added, checked, or deleted.
- Data persists across browser refreshes, tab closures, and system restarts.
- Robust error handling with `try...catch` and defensive checks to preserve empty lists and prevent data loss.

### 2. 🔍 Task Filtering (All / Active / Completed)
- Filter between **All**, **Active (Pending)**, and **Completed** tasks with one click.
- Dynamic tab states with keyboard and screen reader accessibility (`role="tablist"`, `role="tab"`, `aria-selected`).
- Context-aware empty state messages tailored to the active filter (e.g., celebrating when all active tasks are finished).

### 3. 📊 Dynamic Visual Progress Bar
- Real-time calculation of completed vs. total tasks: `(completed / total) * 100%`.
- Smooth animated progress bar fill with subtle gradients.
- Live progress indicator (`% completed`) and counters (`X of Y done`, `Z items left`).
- Full accessibility compliance using ARIA progressbar attributes (`role="progressbar"`, `aria-valuenow`).

### 4. 🎨 Design & User Experience
- **Light & Dark Mode:** One-click theme toggle with automatic preference saving in `localStorage`.
- **Minimalist Aesthetic:** Inspired by Things 3 and Apple design guidelines, with clean typography (`Plus Jakarta Sans`), subtle shadows, and crisp border styling.
- **Micro-interactions:** Custom circular SVG animated checkboxes, smooth strike-through effects, and hover transitions.
- **Zero Dependencies:** Pure vanilla web technologies—no frameworks, npm packages, or build tools required.

---

## 🚀 Getting Started

### Option 1: Direct File Launch
Simply double-click on **`index.html`** or open it in any modern browser (Chrome, Edge, Firefox, Safari).

### Option 2: Local Development Server
Run a lightweight local server using Python:
```bash
# Using Python
python -m http.server 3000
# or on Windows:
py -m http.server 3000
```
Then visit **`http://localhost:3000`** in your browser.

---

## 📁 Project Structure

```text
To-Do-list/
│
├── index.html      # Semantic HTML5 markup, progress bar, & accessible filter tabs
├── style.css       # Design tokens, CSS variables, dark/light themes, & animations
├── app.js          # Modular JavaScript logic (State, CRUD, Storage, Filtering, Progress)
└── README.md       # Project documentation
```

---

## 🛠️ Technical Implementation

### 1. HTML5 (`index.html`)
- Semantic layout using `<header>`, `<form>`, `<input>`, `<ul>`, and `<footer>`.
- Progress bar container with `role="progressbar"`, `aria-valuenow`, `aria-valuemin="0"`, `aria-valuemax="100"`.
- Tabbed navigation with `role="tablist"` and `data-filter` dataset attributes.

### 2. CSS3 (`style.css`)
- **CSS Custom Properties (Variables):** Theme tokens (`--bg-page`, `--card-bg`, `--accent-color`, `--progress-gradient`, etc.) for seamless light/dark mode transitions.
- **Progress Bar Animation:** Smooth width interpolation using `transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1)`.
- **Responsive Design:** Mobile-first layout with smooth fluid padding and container constraints for all screen sizes.

### 3. Vanilla JavaScript (`app.js`)
- **State Management:**
  ```javascript
  let tasks = [];
  let currentFilter = 'all'; // 'all' | 'active' | 'completed'
  ```
- **Local Storage API:**
  - `saveTasks()`: Persists tasks array via `localStorage.setItem()`.
  - `loadTasks()`: Reads data with `localStorage.getItem()` and parses with `JSON.parse()`.
- **Filtering Logic:**
  - `getFilteredTasks()`: Pure function returning filtered tasks based on `currentFilter`.
  - `setFilter(filter)`: Updates active filter state and toggles active tab classes and ARIA states.
- **Progress Calculation:**
  - `updateProgressBar()`: Computes percentage `Math.round((completed / total) * 100)` and updates DOM elements.
- **CRUD Operations:**
  - `addTask(text)`: Creates task with unique timestamp ID and unshifts to state.
  - `toggleTask(id)`: Inverts completion status.
  - `deleteTask(id)`: Removes task by ID.
  - `clearCompleted()`: Batch removes all finished tasks.

---

## 📄 License
This project is open-source and free to use for educational and personal purposes.
