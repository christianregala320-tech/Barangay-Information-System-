<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Barangay Information System'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo">B</div>
            <div>
                <h2>Barangay IS</h2>
                <small>Resident Information</small>
            </div>
        </div>

        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="residents.php">Residents</a>
            <a href="households.php">Households</a>
            <a href="certificates.php">Certificates</a>
            <a href="requests.php">Requests</a>
            <a href="announcements.php">Announcements</a>
            <a href="reports.php">Reports</a>
            <a href="activity_logs.php">Activity Logs</a>
            <a href="profile.php">Profile</a>
            <a href="backup_restore.php">Backup / Restore</a>
            <a href="logout.php" class="logout-link">Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <button class="menu-button" id="menuButton">☰</button>

            <div>
                <strong><?= htmlspecialchars($_SESSION['full_name'] ?? 'Barangay User'); ?></strong>
                <small><?= htmlspecialchars($_SESSION['role'] ?? 'Staff'); ?></small>
            </div>
        </header>

        <section class="page-content">
