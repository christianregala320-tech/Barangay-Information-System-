<?php

session_start();

require_once("../connection.php");
require_once("../classes/Certificate.php");
require_once("../classes/ActivityLog.php");

$certificate = new Certificate($conn);
$activityLog = new ActivityLog($conn);

try {

    if (isset($_POST['add_certificate'])) {

        $resident_id = (int)($_POST['resident_id'] ?? 0);
        $certificate_type = trim($_POST['certificate_type'] ?? '');
        $date_issued = $_POST['date_issued'] ?? date('Y-m-d');
        $purpose = trim($_POST['purpose'] ?? '');

        if ($resident_id > 0 && $certificate->addCertificate(
            $resident_id,
            $_SESSION['user_id'],
            $certificate_type,
            $date_issued,
            $purpose
        )) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Issued " . $certificate_type . " for resident ID: " . $resident_id
            );

            header("Location: ../certificates.php?status=added");
            exit();
        }
    }

    if (isset($_POST['delete_certificate'])) {

        $certificate_id = (int)($_POST['certificate_id'] ?? 0);

        if ($certificate_id > 0 && $certificate->deleteCertificate($certificate_id)) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Deleted certificate ID: " . $certificate_id
            );

            header("Location: ../certificates.php?status=deleted");
            exit();
        }
    }

    header("Location: ../certificates.php?status=error");
    exit();

} catch (PDOException $e) {

    header("Location: ../certificates.php?status=error");
    exit();
}

?>
