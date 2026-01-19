# ThreeDOS Ushering System

![ThreeDOS Logo](https://via.placeholder.com/150) <!-- Replace with actual logo if available -->

A comprehensive applicant management system designed for the ThreeDOS organization to streamline the recruitment and selection process for ushering teams across various councils.

## 🚀 Overview

The ThreeDOS Ushering System provides a robust platform for potential ushers to apply and for organization leaders to review, rate, and manage these applications efficiently. It features a modern frontend interface and a secure PHP-powered backend with role-based access control.

## ✨ Key Features

### 📝 Applicant Registration
- **Public Form**: Easy-to-use registration form for prospective applicants.
- **Detailed Profiles**: Collects essential information including contact details, college, level, and council preferences.

### 🔐 Secure Authentication & RBAC
- **Multi-Level Roles**: 
  - **VP**: Full system access, including cross-council management and deletion rights.
  - **Head**: Full access to their respective council's applicants.
  - **Instructor**: Permission to view applicants and update ratings/notes only.
- **Token-Based Sessions**: Secure session management using unique tokens.

### 📊 Administrative Dashboard
- **Real-Time Data**: Instant access to all applicant registrations.
- **Advanced Filtering**: Filter by council, academic level, or current rating status (Pending, Acceptance, B, Rejection).
- **Search Functionality**: Quickly find applicants by name or email.
- **Pagination**: Optimized for handling large volumes of registrations.

### 📈 Statistics & Analytics
- **Visual Insights**: Quick statistics on total applications and a breakdown of recruitment statuses.
- **Council-Specific Stats**: View data relevant to specific department performance.

## 🛠️ Tech Stack

- **Frontend**: 
  - HTML5 & CSS3 (Modern, responsive design)
  - Vanilla JavaScript (Dynamic UI updates using Fetch API)
- **Backend**: 
  - PHP (RESTful API architecture)
- **Database**: 
  - MySQL (Relational data management)
- **Security**: 
  - Password Hashing (Bcrypt)
  - Token-Based Authentication

## 📂 Project Structure

```text
├── backend/
│   ├── api.php           # Main REST API endpoints
│   ├── auth.php          # Authentication logic (Login/Logout)
│   ├── signup.php        # Staff account creation
│   ├── connection.php     # Database connection configuration
│   └── schema.sql        # Database table definitions
├── frontend/
│   ├── css/              # Stylesheets
│   ├── js/               # Frontend logic & API integration
│   ├── login.html        # Admin login page
│   ├── signup.html       # Admin signup page
│   ├── dashboard.html    # Main administrative portal
│   ├── RegsitrationForm.html # Public applicant form
│   ├── applicant_details_page.html # Individual profile view
│   └── statsitics_page.html # Data analytics view
```

## ⚙️ Installation & Setup

1. **Database Setup**:
   - Create a new MySQL database.
   - Import the provided `backend/schema.sql` file to create the necessary tables and sample data.

2. **Backend Configuration**:
   - Update `backend/connection.php` with your database credentials (host, username, password, and database name).

3. **Deployment**:
   - Upload the files to your web server (compatible with PHP 7.4+ and MySQL).
   - Ensure the `backend` directory is accessible for API requests.

4. **Default Credentials (from Schema)**:
   - **Username**: `head_tech`
   - **Password**: `password123`
   - *Note: It is recommended to create your own accounts via `signup.html` for production.*

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request or open an issue for any bugs or feature requests.

---

**ThreeDOS Ushering System** - *Empowering our events with organized recruitment.*
