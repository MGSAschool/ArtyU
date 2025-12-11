🎨 Arty-U | Art Gallery Management System
1. Project Overview
Project Title
Arty-U

System Description
Arty-U is a web-based Art Gallery Management System designed to showcase digital artworks in a responsive, user-friendly environment. It features a dual-interface design: a public-facing gallery for visitors to explore, filter, and interact with art, and a secure administration panel for curators to manage content, categories, and user feedback.

Target Users
Public Visitors: Art enthusiasts looking to browse portfolios, view artwork details, and interact via likes and comments.

Admin Users: Gallery curators or administrators responsible for uploading content, managing categories, and moderating community interactions.

Project Goals and Objectives
To provide a seamless platform for displaying high-quality digital art.

To implement a secure content management system (CMS) for easy updates.

To foster user engagement through commenting and liking features without requiring user registration.

To ensure data security through input sanitization, secure authentication, and session management.

2. Technology Stack
Server Environment: XAMPP (Apache HTTP Server, MySQL/MariaDB)

Backend Language: PHP (Native, Version 7.4 or 8.x recommended)

Database: MySQL / MariaDB

Frontend Framework: Bootstrap 5.3.3 (CSS & JS)

Core Technologies: HTML5, CSS3, Vanilla JavaScript

3. Team Members and Contributions
Mitzigrace Andaya - [Backend and Frontend]

Developed core PHP logic for gallery and admin functionality.

Implemented database connections, session management, and CRUD operations.

Integrated frontend designs with backend logic.

Dannaline Baloy - [UI Design/Layout and Final Report]

Designed the user interface and layout structure.

Ensured responsive design across different screen sizes.

Compiled and finalized the project report.

Francis Red Villena - [Compilation of Portfolios]

Gathered and organized artwork assets for the gallery.

Managed digital content preparation for the system.

John Andrew Santos - [UI Design/Layout and Final Report]

Collaborated on UI/UX design and layout implementation.

Assisted in styling and visual consistency.

Contributed to the final project documentation and report.

Hans Vincent Escoto - [Documentation and Web Tester]

Conducted system testing to identify bugs and usability issues.

Verified functionality of admin and public features.

Managed technical documentation.

Julyanna Silvestre - [UI Design/Layout and Documentation]

Assisted in designing the visual layout of the application.

Contributed to system documentation and user guides.

4. System Features
🌍 Public-Facing Features
Dynamic Art Gallery: View all uploaded artworks in a responsive grid layout.

Category Filtering: Filter displayed artworks by specific genres (e.g., Oil, Digital, Abstract).

Artwork Details: Deep-dive view including high-resolution images, descriptions, upload dates, and category tags.

Interaction:

Like System: Allows users to "Heart" artworks (IP-restricted to prevent spam).

Comment System: Public commenting with a profanity filter to block inappropriate language.

Related Content: Auto-suggestion of 3 random artworks from the same category.

🛡️ Admin Features
Secure Authentication: Login system using password hashing (password_verify) and session handling.

Dashboard: Real-time statistics counter (Total Artworks, Categories, Comments).

Artwork Management:

Add new artworks with image file upload.

Edit existing artwork details (Title, Description, Category, Image).

Delete artworks (removes data from database).

Category Management: Create, Update, and Delete art categories.

Comment Moderation: Admins can view all comments and delete inappropriate ones via the dashboard or directly on the artwork page.

Security: Auto-logout after 30 minutes of inactivity.

5. Database Structure
The system uses a relational database named artyu_db containing the following tables:

users

Purpose: Stores admin credentials.

Columns: user_id, username, password (hashed), last_login.

categories

Purpose: Defines the genres/types of art (e.g., Digital, Canvas).

Columns: category_id, category_name.

artworks

Purpose: Stores the main content for the gallery.

Columns: artwork_id, title, description, image_path, category_id (FK), upload_date.

Relationship: Linked to categories table.

comments

Purpose: Stores user feedback on artworks.

Columns: comment_id, artwork_id (FK), name, message, date_posted.

Relationship: Linked to artworks table.

likes

Purpose: Tracks likes to prevent duplicate likes from the same user.

Columns: like_id, artwork_id (FK), ip_address.

6. How to Run the Program
Prerequisites
XAMPP (or WAMP/MAMP) installed on your machine.

A web browser (Chrome, Firefox, or Edge).

Installation Steps
Download the Source Code:

Copy the folder named artyu (or the repository name) into your XAMPP htdocs directory.

Path: C:\xampp\htdocs\artyu

Start the Server:

Open XAMPP Control Panel.

Click "Start" next to Apache and MySQL.

Database Setup:

Open your browser and navigate to: http://localhost/phpmyadmin

Create a new database named artyu_db.

Click on the Import tab.

Click Choose File and select the file sql/artyu_db.sql provided in the project folder.

Click Import at the bottom.

Configuration:

If you have a password set for your local SQL root user, open includes/db_connect.php and update the $password variable. (Default for XAMPP is empty/blank).

Accessing the System:

Public Gallery: http://localhost/artyu/gallery.php

Admin Panel: http://localhost/artyu/admin/

Default Admin Credentials (For Testing)
(Ensure a user exists in your database with these credentials)

Username: admin

Password: admin123 (Or whatever password you hashed during setup)
