School ERP System - Installation Guide
====================================

This is a comprehensive School ERP System built with PHP and MySQL. Follow these instructions to set up the system.

Prerequisites:
-------------
1. XAMPP (or equivalent) with:
   - PHP 7.4+
   - MySQL 5.7+
   - Apache 2.4+

Installation Steps:
-----------------
1. Database Setup:
   - Start MySQL server
   - Create a new database named 'school_erp'
   - Import the 'school_erp.sql' file into the database

2. Project Setup:
   - Place the entire 'school_erp' folder in your XAMPP htdocs directory
   - Configure database connection in includes/config.php

3. Server Configuration:
   - Start Apache server
   - Access the system at: http://localhost/school_erp

Default Login Credentials:
------------------------
Admin:
- Username: admin
- Password: admin123

Teacher:
- Username: teacher
- Password: teacher123

Student:
- Username: student
- Password: student123

Parent:
- Username: parent
- Password: parent123

Security Notes:
-------------
1. Change default passwords immediately after first login
2. Keep config.php secure and update database credentials
3. Regular backup of the database is recommended

For support or issues, please contact the system administrator.
