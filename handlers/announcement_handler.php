<?php

session_start();

require_once("../connection.php");
require_once("../classes/Announcement.php");
require_once("../classes/ActivityLog.php");

$announcement = new Announcement($conn);
$activityLog = new ActivityLog($conn);

try {

    if (isset($_POST['add_announcement'])) {

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $date_posted = $_POST['date_posted'] ?? date('Y-m-d');

        if ($title != '' && $content != '' && $announcement->addAnnouncement(
            $_SESSION['user_id'],
            $title,
            $content,
            $date_posted
        )) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Posted announcement: " . $title
            );

            header("Location: ../announcements.php?status=added");
            exit();
        }
    }

    if (isset($_POST['delete_announcement'])) {

        $announcement_id = (int)($_POST['announcement_id'] ?? 0);

        if ($announcement_id > 0 && $announcement->deleteAnnouncement($announcement_id)) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Deleted announcement ID: " . $announcement_id
            );

            header("Location: ../announcements.php?status=deleted");
            exit();
        }
    }

    header("Location: ../announcements.php?status=error");
    exit();

} catch (PDOException $e) {

    header("Location: ../announcements.php?status=error");
    exit();
}

?>
