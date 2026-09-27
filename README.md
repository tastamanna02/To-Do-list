# Minimal To-Do List Web Application 📝

A sleek, modern, and distraction-free To-Do List web application built using **pure HTML5, CSS3, and Vanilla JavaScript (ES6+)**. Designed with an Apple / Things 3 minimalist aesthetic, fast interactions, and offline persistence.

---

## ✨ Features

- **Minimalist & Clean UI:** Clean typography, soft shadows, and responsive layout without visual clutter.
- **Dynamic Progress Tracking:** Automatically calculates and displays completion percentage and progress bar in real-time.
- **Full Task Management (CRUD):**
  - **Create:** Quickly add tasks via input field or `Enter` key.
  - **Read:** Clear list rendering with remaining items counter.
  - **Update:** Smooth circular checkboxes with custom check animations and text strike-through.
  - **Delete:** Remove individual items or clear all completed tasks with one click.
- **Task Filtering:** Instant switching between **All**, **Active**, and **Done** tabs.
- **Light & Dark Mode:** Toggle between sleek dark slate and crisp light themes with automatic preference saving.
- **Local Persistence:** Uses browser `localStorage` so data is preserved across page refreshes and browser restarts.
- **Zero Dependencies:** Pure vanilla web technologies—no frameworks, npm packages, or build tools required.

---

## 🚀 Getting Started

### How to Run

1. Open the project folder:
   ```text
   C:\Users\USER\Desktop\To-Do list
   ```
2. Double-click on **`index.html`** to launch the application directly in your default web browser (Chrome, Edge, Firefox, or Safari).

Alternatively, if running a local development server:
```bash
python -m http.server 3000
```
Then navigate to `http://localhost:3000` in your browser.

---

## 📁 Project Structure

```text
To-Do list/
│
├── index.html      # Semantic HTML5 markup and layout
├── style.css       # Design tokens, CSS variables, themes, and animations
├── app.js          # Application state, CRUD logic, and LocalStorage handlers
└── README.md       # Project documentation
```

---

## 🛠️ Technical Details

### 1. HTML5 (`index.html`)
- Semantic structure using `<header>`, `<main>`, `<form>`, `<input>`, and `<ul>`.
- Accessible elements with clean IDs for DOM manipulation.

### 2. CSS3 (`style.css`)
- **CSS Custom Properties (Variables):** Easily manageable color tokens for light and dark modes.
- **Modern Layouts:** Flexbox-driven centering and responsive container with maximum width constraint.
- **Micro-interactions:** Custom circular checkbox animations, button hover states, and smooth theme transitions.

### 3. JavaScript (`app.js`)
- **State Management:** A centralized `tasks` array storing task objects:
  ```javascript
  {
    id: "1727371234567",
    text: "Complete assignment",
    completed: false
  }
  ```
- **CRUD Operations:**
  - `addTask(text)` - Adds a new task object to the beginning of the list.
  - `render()` - Dynamically renders filtered tasks to the DOM and updates the progress indicator.
  - `toggleTask(id)` - Inverts completion status and re-renders.
  - `deleteTask(id)` - Filters out the deleted item from the state.
  - `clearCompleted()` - Cleans up finished items.
- **LocalStorage API:** Automatically syncs state via `localStorage.setItem()` and `localStorage.getItem()`.

---

## 📄 License
This project is open-source and free to use for educational and personal purposes.
