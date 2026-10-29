CREATE DATABASE IF NOT EXISTS barangay_information_system;
USE barangay_information_system;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS announcements;
DROP TABLE IF EXISTS requests;
DROP TABLE IF EXISTS certificates;
DROP TABLE IF EXISTS residents;
DROP TABLE IF EXISTS households;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    FullName VARCHAR(100) NOT NULL,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Role VARCHAR(30) NOT NULL DEFAULT 'Staff',
    ContactNumber VARCHAR(30),
    Status VARCHAR(20) NOT NULL DEFAULT 'Active'
);

CREATE TABLE households (
    HouseholdID INT AUTO_INCREMENT PRIMARY KEY,
    HouseNumber VARCHAR(30),
    Street VARCHAR(100),
    Purok VARCHAR(50),
    Barangay VARCHAR(100),
    HeadOfFamily VARCHAR(100)
);

CREATE TABLE residents (
    ResidentID INT AUTO_INCREMENT PRIMARY KEY,
    HouseholdID INT NULL,
    FirstName VARCHAR(60) NOT NULL,
    LastName VARCHAR(60) NOT NULL,
    MiddleName VARCHAR(60),
    BirthDate DATE NOT NULL,
    Gender VARCHAR(20) NOT NULL,
    CivilStatus VARCHAR(30),
    Occupation VARCHAR(100),
    ContactNumber VARCHAR(30),
    CONSTRAINT fk_resident_household
        FOREIGN KEY (HouseholdID)
        REFERENCES households(HouseholdID)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE certificates (
    CertificateID INT AUTO_INCREMENT PRIMARY KEY,
    ResidentID INT NOT NULL,
    UserID INT NOT NULL,
    CertificateType VARCHAR(100) NOT NULL,
    DateIssued DATE NOT NULL,
    Purpose VARCHAR(255),
    CONSTRAINT fk_certificate_resident
        FOREIGN KEY (ResidentID)
        REFERENCES residents(ResidentID)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_certificate_user
        FOREIGN KEY (UserID)
        REFERENCES users(UserID)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE requests (
    RequestID INT AUTO_INCREMENT PRIMARY KEY,
    ResidentID INT NOT NULL,
    RequestType VARCHAR(100) NOT NULL,
    RequestDate DATE NOT NULL,
    Status VARCHAR(30) NOT NULL DEFAULT 'Pending',
    CONSTRAINT fk_request_resident
        FOREIGN KEY (ResidentID)
        REFERENCES residents(ResidentID)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE announcements (
    AnnouncementID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    Title VARCHAR(150) NOT NULL,
    Content TEXT NOT NULL,
    DatePosted DATE NOT NULL,
    CONSTRAINT fk_announcement_user
        FOREIGN KEY (UserID)
        REFERENCES users(UserID)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE activity_logs (
    LogID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    Activity VARCHAR(255) NOT NULL,
    DateTime DATETIME NOT NULL,
    CONSTRAINT fk_log_user
        FOREIGN KEY (UserID)
        REFERENCES users(UserID)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

INSERT INTO users
(FullName, Username, Password, Role, ContactNumber, Status)
VALUES
('Barangay Administrator', 'admin', '$2y$12$AXOzehvQwaTdF5Y.CsO9G.lV8EEwm5kXYu2IhKZNanjfXbgEz7uqq', 'Admin', '09123456789', 'Active');

INSERT INTO households
(HouseNumber, Street, Purok, Barangay, HeadOfFamily)
VALUES
('101', 'Rizal Street', 'Purok 1', 'Sample Barangay', 'Juan Dela Cruz'),
('202', 'Mabini Street', 'Purok 2', 'Sample Barangay', 'Maria Santos');

INSERT INTO residents
(HouseholdID, FirstName, LastName, MiddleName, BirthDate, Gender, CivilStatus, Occupation, ContactNumber)
VALUES
(1, 'Pedro', 'Dela Cruz', 'Reyes', '1995-04-10', 'Male', 'Single', 'Driver', '09111111111'),
(2, 'Ana', 'Santos', 'Lopez', '1992-08-22', 'Female', 'Married', 'Teacher', '09222222222');

INSERT INTO announcements
(UserID, Title, Content, DatePosted)
VALUES
(1, 'Welcome', 'Welcome to the Barangay Resident Information System.', CURDATE());
