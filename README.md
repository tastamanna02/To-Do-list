# Minimal To-Do List Web Application (PHP & MySQL) 📝

A sleek, modern, and distraction-free To-Do List web application built using **PHP, MySQL (MySQLi), HTML5, and CSS3**. Designed with an Apple / Things 3 minimalist aesthetic, real-time dynamic progress calculation, light/dark mode support, and full database CRUD capabilities.

---

## 📁 4-File Project Structure

The entire project is structured into **4 clean and organized files**:

```text
To-Do-list/
│
├── index.php         # Main application interface + PHP CRUD logic (Add, Toggle, Delete, Filter)
├── db_connect.php    # MySQLi database connection script (Database: web_class)
├── style.css         # Modern design system, CSS variables, dark/light themes & animations
└── README.md         # Setup and usage guide
```

---

## 🗄️ Database Configuration (`db_connect.php`)

The application connects to MySQL using the standard **MySQLi** library:

```php
<?php
$servername = "localhost";
$username = "root";     // XAMPP default username
$password = "";         // XAMPP default password (empty)
$dbname = "web_class";  // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check directly via browser
if (basename($_SERVER['PHP_SELF']) === 'db_connect.php') {
  echo "Connected successfully";
}
?>
```

---

## ✨ Features & Functionality

1. **Full Database CRUD Operations:**
   - **Create (Add Task):** Add new tasks through the input form.
   - **Read (Display Tasks):** Automatically fetches all tasks from MySQL ordered by most recent.
   - **Update (Toggle Status):** Click checkbox to mark tasks as completed or active.
   - **Delete (Delete Button):** Each task item includes a dedicated delete button (trash icon) with confirmation alert.
   - **Batch Action (Clear Completed):** One-click button to delete all completed tasks.

2. **Automatic Table Creation:**
   - On the first load of `index.php`, it automatically checks and creates the `tasks` table in `web_class` if it doesn't already exist.

3. **Real-time Progress Tracking:**
   - Calculates task completion percentage directly from database records.
   - Smooth gradient progress bar that dynamically updates.

4. **Task Filtering (Instant Tabs):**
   - **All:** View all tasks.
   - **Active:** View only pending tasks.
   - **Completed:** View only finished tasks.

5. **Apple / Things-3 Aesthetic & Themes:**
   - Light & Dark mode switch with preference saved in `localStorage`.
   - Crisp modern typography using Google Font `Plus Jakarta Sans`.

---

## 🚀 How to Run Locally with XAMPP

### Step 1: Place Files in XAMPP `htdocs`
Copy the project folder `To-Do-list` to your XAMPP web root directory:
```text
C:\xampp\htdocs\To-Do-list\
```

### Step 2: Start Apache and MySQL
1. Open **XAMPP Control Panel**.
2. Click **Start** for **Apache**.
3. Click **Start** for **MySQL**.

### Step 3: Create the Database in phpMyAdmin
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on **New** (or SQL tab) and run:
```sql
CREATE DATABASE IF NOT EXISTS web_class;
```
*(The `tasks` table will be automatically created by `index.php` upon visiting the page, or you can run the schema below manually:)*
```sql
USE web_class;

CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `text` VARCHAR(255) NOT NULL,
    `completed` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Step 4: Open the Application
Navigate to the following URL in your web browser:
```text
http://localhost/To-Do-list/index.php
```

You can also test your database connection directly:
```text
http://localhost/To-Do-list/db_connect.php
```
*(Displays: `Connected successfully`)*

---

## 📄 License
This project is open-source and created for educational and class purposes (`web_class`).
