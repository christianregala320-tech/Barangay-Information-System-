<?php

require_once("includes/auth.php");
require_once("connection.php");

if (isset($_POST['restore_database'])) {

    if (!isset($_FILES['sql_file']) || $_FILES['sql_file']['error'] != UPLOAD_ERR_OK) {
        header("Location: backup_restore.php?status=no_file");
        exit();
    }

    $extension = strtolower(pathinfo($_FILES['sql_file']['name'], PATHINFO_EXTENSION));

    if ($extension != 'sql') {
        header("Location: backup_restore.php?status=invalid_file");
        exit();
    }

    $sql = file_get_contents($_FILES['sql_file']['tmp_name']);

    try {
        $conn->exec($sql);

        header("Location: backup_restore.php?status=restored");
        exit();

    } catch (PDOException $e) {

        header("Location: backup_restore.php?status=restore_failed");
        exit();
    }
}

header("Location: backup_restore.php");
exit();

?>
