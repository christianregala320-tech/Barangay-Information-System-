<?php

session_start();

require_once("../connection.php");
require_once("../classes/Resident.php");
require_once("../classes/ActivityLog.php");

$resident = new Resident($conn);
$activityLog = new ActivityLog($conn);

try {

    if (isset($_POST['add_resident'])) {

        $household_id = (int)($_POST['household_id'] ?? 0);
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $middle_name = trim($_POST['middle_name'] ?? '');
        $birth_date = $_POST['birth_date'] ?? '';
        $gender = trim($_POST['gender'] ?? '');
        $civil_status = trim($_POST['civil_status'] ?? '');
        $occupation = trim($_POST['occupation'] ?? '');
        $contact_number = trim($_POST['contact_number'] ?? '');

        if ($first_name == '' || $last_name == '' || $birth_date == '' || $gender == '') {
            header("Location: ../residents.php?status=empty");
            exit();
        }

        if ($resident->addResident(
            $household_id,
            $first_name,
            $last_name,
            $middle_name,
            $birth_date,
            $gender,
            $civil_status,
            $occupation,
            $contact_number
        )) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Added resident: " . $first_name . " " . $last_name
            );

            header("Location: ../residents.php?status=added");
            exit();
        }
    }

    if (isset($_POST['update_resident'])) {

        $resident_id = (int)($_POST['resident_id'] ?? 0);
        $household_id = (int)($_POST['household_id'] ?? 0);
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $middle_name = trim($_POST['middle_name'] ?? '');
        $birth_date = $_POST['birth_date'] ?? '';
        $gender = trim($_POST['gender'] ?? '');
        $civil_status = trim($_POST['civil_status'] ?? '');
        $occupation = trim($_POST['occupation'] ?? '');
        $contact_number = trim($_POST['contact_number'] ?? '');

        if ($resident_id > 0 && $resident->updateResident(
            $resident_id,
            $household_id,
            $first_name,
            $last_name,
            $middle_name,
            $birth_date,
            $gender,
            $civil_status,
            $occupation,
            $contact_number
        )) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Updated resident ID: " . $resident_id
            );

            header("Location: ../residents.php?status=updated");
            exit();
        }
    }

    if (isset($_POST['delete_resident'])) {

        $resident_id = (int)($_POST['resident_id'] ?? 0);

        if ($resident_id > 0 && $resident->deleteResident($resident_id)) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Deleted resident ID: " . $resident_id
            );

            header("Location: ../residents.php?status=deleted");
            exit();
        }
    }

    header("Location: ../residents.php?status=error");
    exit();

} catch (PDOException $e) {

    header("Location: ../residents.php?status=error");
    exit();
}

?>
