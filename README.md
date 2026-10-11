# 📝 Minimal To-Do List Web Application (PHP Version)

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

A sleek, modern, and distraction-free To-Do List web application built strictly with **PHP**, **HTML5**, and **Vanilla CSS3** (0% JavaScript, 100% server-side processing). Designed with an Apple / Things 3 minimalist aesthetic, smooth transitions, server-side progress calculation, light/dark mode cookie persistence, and zero-configuration database storage.

---

## ✨ Key Features & Highlights

- **Pure PHP, HTML5 & CSS3 Stack**:
  - **Zero JavaScript**: কোনো `.js` ফাইল বা ব্রাউজার স্ক্রিপ্ট ছাড়াই সম্পূর্ণ প্রজেক্টটি পরিচালিত।
  - **Server-Side Architecture**: টাস্ক অ্যাড, ফিল্টারিং, প্রগ্রেস বার ক্যালকুলেশন এবং থিম পরিবর্তন সবই পিএইচপি সার্ভার-সাইডে সম্পন্ন হয়।
  - **Things 3 / Apple Minimalist Aesthetic**: Plus Jakarta Sans ফন্ট, সফট শ্যাডো, রাউন্ডেড কার্ড এবং কাস্টম সার্কুলার চেকবক্স।
- **Task Management (Full CRUD Operations)**:
  - **Add Task**: ইনপুট ফিল্ডে টাস্ক লিখে সাবমিট করলে পিএইচপি ডাটাবেজে স্টোর করে।
  - **Toggle Complete**: সার্কুলার চেকবক্সে ক্লিক করলে সাথে সাথে স্ট্যাটাস সম্পন্ন/অসম্পূর্ণ টগল হয়।
  - **Delete Task**: ট্র্যাশ আইকনে ক্লিক করে যেকোনো টাস্ক মুছে ফেলা যায়।
  - **Clear Completed**: সমাপ্ত হওয়া সকল টাস্ক এক ক্লিকে ডিলিট করার সুবিধা।
- **Task Filtering (All / Active / Completed)**:
  - `?filter=all`, `?filter=active`, এবং `?filter=completed` এর মাধ্যমে সার্ভার-সাইডে ফিল্টারকৃত টাস্ক প্রদর্শন।
  - খালি তালিকার জন্য প্রাসঙ্গিক এম্পটি স্টেট মেসেজ (যেমন: সব কাজ শেষ হলে *All active tasks completed! 🎉*)।
- **Dynamic Visual Progress Bar**:
  - পিএইচপি দিয়ে রিয়েল-টাইমে মোট টাস্ক এবং সমাপ্ত টাস্কের শতকরা হার হিসাব করা হয় (`(completed / total) * 100%`)।
  - প্রগ্রেস বার এবং কাউন্টার ব্যাজ (`1 of 3 done`, `2 items left`) স্বয়ংক্রিয়ভাবে আপডেট হয়।
- **Light & Dark Theme Switcher**:
  - হেডার আইকনে ক্লিক করে এক ক্লিকে লাইট ও ডার্ক মোডের মধ্যে সুইচ করা যায়।
  - ইউজারের পছন্দ ৩০ দিনের ব্রাউজার কুকিতে (`$_COOKIE['theme']`) সেভ থাকে।
- **Zero-Configuration Database (Dual Engine Support)**:
  - **SQLite PDO (Default)**: কোনো কনফিগারেশন ছাড়াই স্বয়ংক্রিয়ভাবে `tasks.db` ফাইল তৈরি করে কাজ করে।
  - **MySQL Support**: চাইলে [db.php](file:///c:/Users/ASUS/Downloads/To-Do-list-2/db.php)-তে `DB_DRIVER = 'mysql'` দিয়ে [database.sql](file:///c:/Users/ASUS/Downloads/To-Do-list-2/database.sql) ফাইলটি phpMyAdmin-এ ইমপোর্ট করে চালানো যাবে।

---

## 📁 File Structure

```text
To-Do-list-2/
├── index.php     # Main To-Do application (HTML5 markup & PHP server logic)
├── db.php        # Database connection & CRUD helper functions (PDO SQLite/MySQL)
├── database.sql  # MySQL schema and initial seed data for lab submission
├── style.css     # Complete standalone design system (Light/Dark themes & animations)
├── .gitignore    # Excludes runtime database files (*.db) from git
└── README.md     # Project documentation & execution guide
```

---

## 🚀 How to Run the Project (চালানোর নিয়ম)

### পদ্ধতি ১: PHP Built-in Server দিয়ে (সবচেয়ে দ্রুত ও সহজ)

টার্মিনালে বা PowerShell-এ প্রজেক্ট ফোল্ডারে গিয়ে কমান্ড দিন:
```bash
# XAMPP এর PHP দিয়ে:
C:\xampp\php\php.exe -S localhost:8080

# অথবা যদি PHP এনভায়রনমেন্ট ভেরিয়েবলে থাকে:
php -S localhost:8080
```
এরপর ব্রাউজারে প্রবেশ করুন:
👉 **[http://localhost:8080](http://localhost:8080)**

---

### পদ্ধতি ২: XAMPP Apache সার্ভার দিয়ে

1. পুরো ফোল্ডারটি `C:\xampp\htdocs\To-Do-list-2` ডিরেক্টরিতে কপি করুন।
2. **XAMPP Control Panel** ওপেন করে **Apache** মডিউলটি **Start** করুন।
3. আপনার ব্রাউজারে নিচের ঠিকানায় প্রবেশ করুন:
   ```text
   http://localhost/To-Do-list-2/
   ```

---

## 🧑‍🏫 Teacher / Lab Examiner Explanation Guide (Viva Q&A)

শিক্ষক বা এক্সামিনার প্রশ্ন করলে যেভাবে উত্তর দেবেন:

1. **জাভাস্ক্রিপ্ট ছাড়া ফর্ম সাবমিশন এবং রিলোড কীভাবে সামলানো হয়েছে?**
   - আমরা **Post-Redirect-Get (PRG)** প্যাটার্ন ব্যবহার করেছি। যখন ইউজার টাস্ক যোগ, ডিলিট বা টগল করে, তখন ফর্ম `POST` রিকোয়েস্ট পাঠায়। পিএইচপি ডাটাবেজ আপডেট করার পর `header("Location: index.php?filter=...")` দিয়ে রিডাইরেক্ট করে। ফলে ব্রাউজার রিফ্রেশ দিলে ফর্ম ডুপ্লিকেট সাবমিট হয় না।

2. **ডাটাবেজ ইনজেকশন (SQL Injection) রোধে কী করা হয়েছে?**
   - সকল ডাটাবেজ কোয়েরিতে **PDO Prepared Statements** (`$stmt->prepare()` এবং প্যারামিটার বাইন্ডিং) ব্যবহার করা হয়েছে, যা সম্পূর্ণ নিরাপদ।

3. **ক্রস-সাইট স্ক্রিপ্টিং (XSS) কীভাবে প্রতিরোধ করা হয়েছে?**
   - ইউজার ইনপুট ব্রাউজারে প্রিন্ট করার সময় পিএইচপির `htmlspecialchars($text, ENT_QUOTES, 'UTF-8')` ফাংশন ব্যবহার করে স্ক্রিপ্ট এক্সিকিউশন বন্ধ করা হয়েছে।

4. **ডার্ক মোড কীভাবে সংরক্ষণ হয়?**
   - লাইট/ডার্ক মোডের স্টেট পিএইচপির `setcookie('theme', ...)` দিয়ে ব্রাউজার কুকিতে সংরক্ষণ করা হয় এবং HTML ট্যাগে `data-theme="dark"` অথবা `data-theme="light"` এট্রিবিউট ডায়নামিকালি ইনজেক্ট করা হয়।

---

## 📄 License
Open source and created for academic & productivity purposes.
