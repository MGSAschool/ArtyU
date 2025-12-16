# 🎨 Arty-U | Art Gallery Management System

![Project Status](https://img.shields.io/badge/Status-Completed-success)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)
![Bootstrap](https://img.shields.io/badge/Frontend-Bootstrap%205-purple)

**Arty-U** is a dynamic web-based art gallery application built with **PHP** and **MySQL**. It features a public-facing gallery for users to explore and interact with artworks, and a secure admin panel for content management.

do not open the ArtyU File in the repository, it contains nothing

---

## 📖 Table of Contents
1. [Project Overview](#-project-overview)
2. [Technology Stack](#-technology-stack)
3. [Team Members](#-team-members-and-contributions)
4. [System Features](#-system-features)
5. [Database Structure](#-database-structure)
6. [How to Run](#-how-to-run-the-program)

---

## 🌟 Project Overview

### Project Title
**Arty-U**

### System Description
Arty-U is a web-based Art Gallery Management System designed to showcase digital artworks in a responsive, user-friendly environment. It features a dual-interface design: a public-facing gallery for visitors to explore, filter, and interact with art, and a secure administration panel for curators to manage content, categories, and user feedback.

### Target Users
* **Public Visitors:** Art enthusiasts looking to browse portfolios, view artwork details, and interact via likes and comments.
* **Admin Users:** Gallery curators or administrators responsible for uploading content, managing categories, and moderating community interactions.

### Project Goals
* To provide a seamless platform for displaying high-quality digital art.
* To implement a secure content management system (CMS) for easy updates.
* To foster user engagement through commenting and liking features.
* To ensure data security through input sanitization and secure authentication.

---

## 💻 Technology Stack

* **Server Environment:** XAMPP (Apache HTTP Server, MySQL/MariaDB)
* **Backend Language:** PHP (Native, Version 7.4 or 8.x recommended)
* **Database:** MySQL / MariaDB
* **Frontend Framework:** Bootstrap 5.3.3 (CSS & JS)
* **Core Technologies:** HTML5, CSS3, Vanilla JavaScript

---

## 👥 Team Members and Contributions

| Name | Tasks / Role |
| :--- | :--- |
| **Mitzigrace Andaya** | Backend and frontend | 
| **Dannaline Baloy** | UI Design/Layout and Final Report |
| **Francis Red Villena** | Compilation of Portfolios |
| **John Andrew Santos** | UI Design/Layout and Final Report |
| **Hans Vincent Escoto** | Documentation and Web Tester |
| **Julyanna Silvestre** | UI Design/Layout and Documentation |

---

## 🚀 System Features

### 🌍 Public-Facing Features
* **Dynamic Art Gallery:** View all uploaded artworks in a responsive grid layout.
* **Category Filtering:** Filter displayed artworks by specific genres (e.g., Oil, Digital, Abstract).
* **Artwork Details:** Deep-dive view including high-resolution images, descriptions, upload dates, and category tags.
* **Interaction:**
    * ❤️ **Like System:** Users can like artworks (IP-restricted to prevent spam).
    * 💬 **Comment System:** Public commenting with a profanity filter to block inappropriate language.
* **Related Content:** Auto-suggestion of 3 random artworks from the same category.

### 🛡️ Admin Features
* **Secure Authentication:** Login system using password hashing (`password_verify`) and session handling.
* **Dashboard:** Real-time statistics counter (Total Artworks, Categories, Comments).
* **Artwork Management:** Add, Edit, and Delete artworks with secure image uploading (MIME type validation).
* **Category Management:** Create, Update, and Delete art categories.
* **Comment Moderation:** Admins can view and delete inappropriate comments.
* **Security:** Auto-logout after 30 minutes of inactivity.

---

## 🗄️ Database Structure

The system uses a relational database named **`artyu_db`**:

1. **`users`**: Stores admin credentials (hashed passwords).
2. **`categories`**: Defines art genres (Digital, Canvas, etc.).
3. **`artworks`**: Stores artwork metadata and image paths.
4. **`comments`**: Stores user feedback linked to artworks.
5. **`likes`**: Tracks likes per IP address to prevent duplicate votes.

---

## 🛠️ How to Run the Program

### Prerequisites
* **XAMPP** (or WAMP/MAMP) installed on your machine.
* A web browser (Chrome, Firefox, or Edge).

### Installation Steps

1.  **Clone/Download:**
    * Copy the project folder `artyu` into your XAMPP `htdocs` directory.
    * Path: `C:\xampp\htdocs\artyu`

2.  **Start Server:**
    * Open **XAMPP Control Panel**.
    * Start **Apache** and **MySQL**.

3.  **Setup Database:**
    * Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
    * Create a database named `artyu_db`.
    * Import `sql/artyu_db.sql` from the project folder.

4.  **Configure Connection:**
    * If your MySQL uses a password, edit `includes/db_connect.php`.
    * Default XAMPP settings (User: `root`, Pass: `[empty]`) work out of the box.
    * Admin LogIn
    * admin
    * 000000
  
5. **IF GIT DOESNT WORK GO TO THIS DRIVE AND DOWNLOAD THE WHOLE FOLDER**
   *https://drive.google.com/drive/folders/1MAsw39dxESb6guDg-2ePLBZErnCCpE0-?usp=sharing

### 🔗 Access Links

* **Public Gallery:** [http://localhost/artyu/gallery.php](http://localhost/artyu/gallery.php)
* **Admin Panel:** [http://localhost/artyu/admin/](http://localhost/artyu/admin/)

### Video presentation
Andaya - https://drive.google.com/file/d/1Ex3GTgfkweVZVzoQUWkCsLL5HykHE78S/view?usp=sharing

Santos - https://drive.google.com/drive/folders/1szHss9ylYYIbl9f3s6A9IKJyxoCgBWhQ
---

## 📜 License
This project is for educational purposes.
