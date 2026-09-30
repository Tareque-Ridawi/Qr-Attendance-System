# QR Code Based Attendance System

An innovative, anti-proxy attendance tracking solution using QR codes. This is an open-source web application built on the LAMP stack (Linux, Apache, MySQL, PHP) designed to revolutionize how educational institutions manage student attendance.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Project Structure](#project-structure)
- [How It Works](#how-it-works)
- [Anti-Proxy Technology](#anti-proxy-technology)
- [User Roles](#user-roles)
- [Team](#team)
- [License](#license)

## 🎯 Overview

The QR Code Based Attendance System is a university project that simplifies attendance management through QR code technology. Teachers generate dynamic QR codes for their classes, students scan them to mark attendance, and the system automatically logs attendance records with timestamps and IP tracking to prevent proxy attendance.

This project is built with security, ease of use, and data integrity as core principles.

## ✨ Features

### For Students
- **Easy Check-in**: Simply scan the QR code provided by the instructor
- **Attendance History**: View personal attendance records and history
- **Real-time Updates**: See attendance status immediately after scanning
- **Secure Access**: Use login credentials to access only your attendance data

### For Instructors
- **Dynamic QR Generation**: Generate QR codes that refresh every 10 seconds
- **Attendance Management**: View and manage student attendance records
- **Class Management**: Create and manage multiple courses
- **Data Export**: Access attendance data in an organized list format

### System-Wide
- **Anti-Proxy Technology**: QR codes refresh every 10 seconds to prevent code reuse
- **IP Tracking**: System logs IP addresses to detect suspicious patterns
- **Data Integrity**: Comprehensive database with foreign key constraints
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Secure Authentication**: Password hashing for user accounts

## 🛠 Technology Stack

| Component | Technology |
|-----------|------------|
| **Backend** | PHP 8.0.30 |
| **Database** | MySQL (MariaDB 10.4.32) |
| **Frontend** | HTML5, CSS3, JavaScript |
| **Server** | Apache |
| **OS** | Linux |
| **Animations** | Animate.css 4.1.1 |
| **jQuery** | 3.7.1 |
| **License** | MIT License |

## 💻 Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL/MariaDB 10.4 or higher
- Apache Web Server
- Git (optional)

### Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/Tareque-Ridawi/Qr-Attendance-System.git
   cd Qr-Attendance-System
   ```

2. **Configure Apache**
   - Place the project in your web root (typically `/var/www/html/`)
   - Ensure Apache has read permissions on the project directory

3. **Set Up Database**
   - Import the database schema (see Database Setup section)
   - Update database connection details in your PHP configuration

4. **Access the Application**
   - Open your browser and navigate to `http://localhost/Qr-Attendance-System/`
   - The homepage will load with the main navigation

## 🗄 Database Setup

### Import Database Schema

1. **Using phpMyAdmin**:
   - Go to phpMyAdmin interface
   - Click "Import"
   - Select `qr-code-based-attendance-system.sql`
   - Click "Go"

2. **Using MySQL Command Line**:
   ```bash
   mysql -u root -p < qr-code-based-attendance-system.sql
   ```

### Database Structure

The system uses 4 main tables:

#### `users`
- Stores user credentials (students and instructors)
- Fields: `id`, `user_name`, `password`, `instructor`, `name`, `email`

#### `courses`
- Contains course information
- Fields: `id`, `course_id`, `course_title`, `instructor_id`

#### `enrollments`
- Links students to courses
- Fields: `id`, `course_id`, `student_id`

#### `attendance`
- Tracks attendance records
- Fields: `id`, `course_id`, `student_id`, `status`, `date`, `time`

### Sample Data
The database includes sample users, courses, enrollments, and attendance records for testing purposes.

## 📁 Project Structure

```
Qr-Attendance-System/
├── index.php                    # Homepage
├── about-us.php                # Team and project information
├── docs.php                     # Documentation page
├── faq.php                      # Frequently asked questions
├── style.css                    # Main stylesheet
├── script.js                    # JavaScript utilities
├── animation.js                 # Animation effects
├── includes/                    # PHP includes
│   ├── header.php              # Navigation header
│   └── footer.php              # Page footer
├── access-pages/               # Access control pages
├── client-pages/               # Client-facing pages
├── assets/                      # Images and static files
│   ├── logo.png
│   ├── dev-*.png               # Team member photos
│   └── f-*.png                 # Feature images
├── qr-code-based-attendance-system.sql  # Database schema
├── password-hashing-migration.sql       # Password update script
├── terms-and-conditions.html   # Legal terms
└── README.md                    # This file
```

## 🔍 How It Works

### Attendance Process

1. **Teacher Generates QR Code**
   - Teacher logs in to their instructor account
   - Generates a dynamic QR code for the class
   - The code refreshes every 10 seconds

2. **Student Scans QR Code**
   - Student opens the attendance page
   - Points device camera at QR code
   - Scans the code using the system

3. **System Verifies and Records**
   - System validates the QR code
   - Logs student ID, course ID, timestamp, and IP address
   - Stores data in attendance table
   - Displays confirmation to student

4. **Data Preservation**
   - Date and time of attendance are recorded
   - All data is stored centrally in the database
   - Can be viewed as organized attendance lists

## 🛡 Anti-Proxy Technology

### How We Prevent Proxy Attendance

1. **Dynamic QR Codes**: QR codes refresh every 10 seconds, making it impossible for students to share and reuse codes later

2. **IP Tracking**: The system logs the IP address of every attendance scan
   - Suspicious patterns (multiple scans from different IPs in quick succession) can be detected
   - Administrators can review IP logs to identify proxy attempts

3. **Timestamp Logging**: Exact timestamp of each scan is recorded
   - Allows detection of physically impossible attendance patterns
   - Multiple scans for the same student at different locations simultaneously are flagged

4. **Single Scan Per Session**: System validates that each student can only scan once per class session

## 👥 User Roles

### Student
- View personal attendance history
- Scan QR codes to mark attendance
- Access own attendance records
- Cannot modify attendance or access other students' data

### Instructor
- Create and manage courses
- Generate QR codes for classes
- View attendance records for all courses
- Manage course enrollments
- Export attendance data

### Administrator
- Full system access
- User management
- Course management
- Attendance data oversight
- IP tracking and suspicious activity monitoring

## 👨‍💼 Team

**Red Hat Developer Team**

- **Tareque Ridawi** - Backend Developer
  - Core PHP backend and server-side logic
  - Database structure and API integrations

- **Ataullah Gani Al Hossaini** - Front-End Developer
  - Dynamic PHP-driven interface
  - Usability improvements

- **Abidur Rahaman** - Front-End Developer
  - Original front-end design
  - Visual style and layout foundation

- **Dipta Chowdhury** - Database Developer
  - Database design and structure
  - Data integrity and query logic

- **Hamidur Rahman** - API Integration Developer
  - External API integration
  - Login and signup page design with validation

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

MIT License gives you the freedom to:
- Use the software commercially
- Modify the software
- Distribute the software
- Use the software privately

With the conditions:
- Include a copy of the license and copyright notice

## 🤝 Contributing

We welcome contributions from the community! Whether you're fixing bugs, adding features, or improving documentation:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Support

For questions, issues, or suggestions:
- Open an issue on GitHub
- Check the [FAQ](faq.php) page
- Review [Documentation](docs.php)
- Visit the [About Us](about-us.php) page

## 🚀 Future Enhancements

Planned features for future releases:
- Mobile app (iOS/Android) for QR scanning
- Advanced analytics and reporting
- Integration with student management systems
- Multi-language support
- Real-time notification system
- Biometric authentication options

## 📊 Project Statistics

- **Created**: 38 days ago
- **Last Updated**: Recently
- **Repository Size**: ~170 KB
- **Language**: PHP
- **License**: MIT

---

**Made with ❤️ by the Red Hat Developer Team**

*A university project revolutionizing attendance management through QR technology*
