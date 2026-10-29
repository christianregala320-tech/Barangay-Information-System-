BARANGAY RESIDENT INFORMATION SYSTEM
PHP + PDO + OOP + MySQL
=====================================

PROJECT STYLE
-------------
This project uses a simple student-style structure:
1. Database connection is in connection.php
2. Classes contain the database methods
3. Handler files receive POST data and call class methods
4. PHP pages display forms and tables
5. PDO prepared statements are used for INSERT, UPDATE, DELETE, login, and search

MAIN FEATURES
-------------
- User login
- Dashboard
- Resident CRUD
- Resident search
- Household management
- Certificate management
- Resident requests
- Announcements
- Reports
- CSV export
- Activity logs
- Profile management
- Database backup and restore

XAMPP SETUP
-----------
1. Install and open XAMPP.
2. Start Apache and MySQL.
3. Copy the whole "barangay_information_system" folder to:
   C:\xampp\htdocs\
4. Open phpMyAdmin:
   http://localhost/phpmyadmin
5. Click Import.
6. Choose:
   database/barangay_information_system.sql
7. Click Go.
8. Open the project:
   http://localhost/barangay_information_system/

DEFAULT LOGIN
-------------
Username: admin
Password: admin123

IMPORTANT
---------
- The database name used by connection.php is:
  barangay_information_system
- XAMPP default MySQL username is:
  root
- XAMPP default MySQL password is blank.
- If your MySQL account has a password, edit connection.php.
- Delete restrictions are intentional because the ERD has foreign key relationships.
  Example: a resident with an existing certificate cannot be deleted until the
  related certificate is deleted first.

FOLDER GUIDE
------------
classes/
    Contains PHP OOP classes.

handlers/
    Receives form submissions and calls class methods.

includes/
    Reusable login check, header, and footer.

assets/
    CSS and JavaScript.

database/
    MySQL database file for phpMyAdmin.

HOW THE RESIDENT CRUD WORKS
---------------------------
ADD:
residents.php form
-> handlers/resident_handler.php
-> Resident->addResident()
-> INSERT query
-> redirect back to residents.php

READ:
residents.php
-> Resident->getAllResidents()
-> SELECT query
-> display table

UPDATE:
residents.php?edit=ID
-> loads record
-> submits update form
-> handlers/resident_handler.php
-> Resident->updateResident()
-> UPDATE query

DELETE:
Delete button
-> handlers/resident_handler.php
-> Resident->deleteResident()
-> DELETE query

SEARCH:
Search form
-> residents.php?search=keyword
-> Resident->searchResidents()
-> SELECT with LIKE
-> display matching records
