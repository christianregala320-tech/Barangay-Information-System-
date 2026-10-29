<?php

session_start();

require_once("../connection.php");
require_once("../classes/User.php");
require_once("../classes/ActivityLog.php");

$user = new User($conn);
$activityLog = new ActivityLog($conn);

if (isset($_POST['login'])) {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username == '' || $password == '') {
        header("Location: ../index.php?status=empty");
        exit();
    }

    $result = $user->login($username, $password);

    if ($result) {

        $_SESSION['user_id'] = $result['UserID'];
        $_SESSION['full_name'] = $result['FullName'];
        $_SESSION['username'] = $result['Username'];
        $_SESSION['role'] = $result['Role'];

        $activityLog->addLog($result['UserID'], "Logged in to the system");

        header("Location: ../dashboard.php");
        exit();
    }

    header("Location: ../index.php?status=invalid");
    exit();
}

header("Location: ../index.php");
exit();

?>
