<?php

session_start();

require_once("../connection.php");
require_once("../classes/BarangayRequest.php");
require_once("../classes/ActivityLog.php");

$request = new BarangayRequest($conn);
$activityLog = new ActivityLog($conn);

try {

    if (isset($_POST['add_request'])) {

        $resident_id = (int)($_POST['resident_id'] ?? 0);
        $request_type = trim($_POST['request_type'] ?? '');
        $request_date = $_POST['request_date'] ?? date('Y-m-d');
        $status = trim($_POST['status'] ?? 'Pending');

        if ($resident_id > 0 && $request->addRequest(
            $resident_id,
            $request_type,
            $request_date,
            $status
        )) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Added request for resident ID: " . $resident_id
            );

            header("Location: ../requests.php?status=added");
            exit();
        }
    }

    if (isset($_POST['update_request_status'])) {

        $request_id = (int)($_POST['request_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        if ($request_id > 0 && $request->updateStatus($request_id, $status)) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Updated request ID " . $request_id . " to " . $status
            );

            header("Location: ../requests.php?status=updated");
            exit();
        }
    }

    if (isset($_POST['delete_request'])) {

        $request_id = (int)($_POST['request_id'] ?? 0);

        if ($request_id > 0 && $request->deleteRequest($request_id)) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Deleted request ID: " . $request_id
            );

            header("Location: ../requests.php?status=deleted");
            exit();
        }
    }

    header("Location: ../requests.php?status=error");
    exit();

} catch (PDOException $e) {

    header("Location: ../requests.php?status=error");
    exit();
}

?>
