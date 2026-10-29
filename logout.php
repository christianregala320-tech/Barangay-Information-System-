<?php

session_start();

if (isset($_SESSION['user_id'])) {

    require_once("connection.php");
    require_once("classes/ActivityLog.php");

    $activityLog = new ActivityLog($conn);
    $activityLog->addLog($_SESSION['user_id'], "Logged out of the system");
}

session_unset();
session_destroy();

header("Location: index.php");
exit();

?>
