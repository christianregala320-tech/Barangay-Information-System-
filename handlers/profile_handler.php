<?php

session_start();

require_once("../connection.php");
require_once("../classes/User.php");
require_once("../classes/ActivityLog.php");

$user = new User($conn);
$activityLog = new ActivityLog($conn);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['update_profile'])) {

    $full_name = trim($_POST['full_name'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');

    if ($full_name != '' && $user->updateProfile(
        $_SESSION['user_id'],
        $full_name,
        $contact_number
    )) {

        $_SESSION['full_name'] = $full_name;

        $activityLog->addLog(
            $_SESSION['user_id'],
            "Updated profile information"
        );

        header("Location: ../profile.php?status=updated");
        exit();
    }
}

if (isset($_POST['change_password'])) {

    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($new_password) < 6) {
        header("Location: ../profile.php?status=short_password");
        exit();
    }

    if ($new_password !== $confirm_password) {
        header("Location: ../profile.php?status=password_mismatch");
        exit();
    }

    if ($user->changePassword($_SESSION['user_id'], $new_password)) {

        $activityLog->addLog(
            $_SESSION['user_id'],
            "Changed account password"
        );

        header("Location: ../profile.php?status=password_changed");
        exit();
    }
}

header("Location: ../profile.php?status=error");
exit();

?>
