# Student Information Management System (SIMS)

A complete, professional, and responsive Student Information Management System built with HTML, CSS, JavaScript, PHP, and MySQL. 

## Features
- **Frontend Panel**: Home, About, Students & Courses, Contact, Feedback, Login, Sign Up.
- **Admin Panel**: Dashboard, Student Management, Course Management, Transactions History.
- **Modern UI**: Smooth animations, fully responsive layout, custom styled components.
- **Form Validations**: Advanced JavaScript validations and a 250-word limit feedback form.
- **Chatbot UI**: Interactive mock chatbot on the frontend.
- **Secure**: Password hashing (`password_hash`), SQL injection prevention using `real_escape_string` and prepared structures.

## Folder Structure
```
sims_project/
│
├── admin/                 # Admin Dashboard Pages
│   ├── index.php          # Redirect to dashboard / login
│   ├── dashboard.php      # Main Admin Stats
│   ├── students.php       # Manage Students
│   ├── courses.php        # Manage Courses
│   └── transactions.php   # Manage Fees / Transactions
│
├── config/                # Configuration Files
│   └── db.php             # Database connection settings
│
├── css/                   # Stylesheets
│   ├── style.css          # Frontend Styles
│   └── admin.css          # Admin Styles
│
├── frontend/              # Public Facing Pages
│   ├── index.php          # Home Page
│   ├── about.php          # About & Gallery
│   ├── students.php       # Students & Courses Listing
│   ├── contact.php        # Contact & Map
│   ├── login.php          # User & Admin Login
│   ├── signup.php         # Registration Form
│   └── feedback.php       # Feedback Form
│
├── images/                # Image Assets
│   └── (placeholder for local images, currently using Unsplash URLs)
│
├── js/                    # JavaScript Files
│   └── script.js          # Chatbot logic, Form Validations, Search Filters
│
├── index.php              # Root redirect to frontend
├── database.sql           # Database Dump File
└── README.md              # Documentation
```

## Setup & Deployment Instructions (Free Hosting)

This project is optimized to run on free hosting providers like **InfinityFree** and **000WebHost**.

### 1. Database Setup
1. Log into your hosting control panel (cPanel).
2. Go to **MySQL Databases** and create a new database (e.g., `sims_db`).
3. Create a Database User and link it to the database with all privileges.
4. Go to **phpMyAdmin**.
5. Select your newly created database.
6. Click the **Import** tab and upload the `database.sql` file provided in this folder.
7. Click **Go** to execute the SQL script.

### 2. Configuration Update
1. Open the file `config/db.php`.
2. Update the credentials provided by your hosting provider:
```php
$host = "localhost"; // Usually localhost, but check your hosting panel
$username = "your_db_username"; 
$password = "your_db_password";
$dbname = "your_db_name";
```

### 3. File Upload
1. Go to the **File Manager** in your hosting panel (or use FileZilla FTP).
2. Open the `htdocs` or `public_html` folder.
3. Upload all the files and folders from the `sims_project` directory directly into `htdocs` or `public_html`. (Do not upload the folder `sims_project` itself, upload its contents).

### 4. Admin Credentials
Once the site is live, you can log in as the administrator using the default credentials:
- **URL**: `http://yourdomain.com/frontend/login.php`
- **Email**: `admin@sims.com`
- **Password**: `password`

You can also test the student login by registering a new account via the Signup page.

## Technologies Used
- HTML5, CSS3, JavaScript (Vanilla)
- PHP 7.4 / 8.0+
- MySQL / MariaDB
- FontAwesome Icons
- Google Fonts (Inter)
