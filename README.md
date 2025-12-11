# 🎨 Arty-U | Art Gallery Management System

**Arty-U** is a dynamic web-based art gallery application built with **PHP** and **MySQL**. It features a public-facing gallery for users to explore and interact with artworks, and a secure admin panel for content management.

## 🔗 How to Run (Access Links)

Once you have installed the project in XAMPP (see instructions below), you can access the site here:

### 🖼️ Public Gallery (For Visitors)
> **[http://localhost/artyu/gallery.php](http://localhost/artyu/gallery.php)**
> *Use this link to view artworks, filter categories, and post comments.*

### 🔐 Admin Panel (For You)
> **[http://localhost/artyu/admin/](http://localhost/artyu/admin/)**
> *Use this link to log in, upload new art, and manage content.*

---

## 🚀 Features

### 👤 Public (User) Features
* **Art Gallery:** Browse artworks with a clean, responsive grid layout.
* **Category Filtering:** Filter artworks by specific categories (e.g., Oil, Digital, Sketch).
* **Artwork Details:** View high-quality images, descriptions, and upload dates.
* **Interaction:**
    * ❤️ **Like System:** Users can like artworks (IP-based restriction).
    * 💬 **Comment System:** Post comments with a built-in "bad word" profanity filter.
* **Related Artworks:** Automatically suggests similar art based on categories.

### 🛡️ Admin (Dashboard) Features
* **Secure Authentication:** Login system with session management and auto-logout after 30 minutes of inactivity.
* **Dashboard Overview:** Quick stats on total artworks, categories, and comments.
* **Artwork Management:** Add, Edit, and Delete artworks with secure image uploading (MIME type validation).
* **Category Management:** Create and manage art categories.
* **Comment Moderation:** Admins can delete inappropriate comments directly from the public view or the admin panel.
* **Profile Management:** Change admin password securely.

---

## 📂 Folder Structure

```text
artyu/
├── admin/                  # Admin Panel
│   ├── includes/           # Admin-specific logic (auth_check.php)
│   ├── artworks.php        # Manage Artworks
│   ├── categories.php      # Manage Categories
│   ├── comments.php        # Manage Comments
│   ├── dashboard.php       # Admin Home
│   ├── index.php           # Login Page
│   └── ...
├── assets/
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   ├── images/             # Static assets (placeholders)
│   └── uploads/            # Uploaded artwork images
├── includes/               # Global Logic
│   ├── db_connect.php      # Database Connection
│   ├── badwords.php        # Profanity Filter List
│   ├── header.php          # Navbar
│   └── footer.php          # Footer
├── sql/
│   └── artyu_db.sql        # Database Import File
├── artwork.php             # Single Artwork View
├── gallery.php             # Main Gallery Page
└── index.php               # Landing Page
