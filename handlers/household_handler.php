<?php

session_start();

require_once("../connection.php");
require_once("../classes/Household.php");
require_once("../classes/ActivityLog.php");

$household = new Household($conn);
$activityLog = new ActivityLog($conn);

try {

    if (isset($_POST['add_household'])) {

        $house_number = trim($_POST['house_number'] ?? '');
        $street = trim($_POST['street'] ?? '');
        $purok = trim($_POST['purok'] ?? '');
        $barangay = trim($_POST['barangay'] ?? '');
        $head_of_family = trim($_POST['head_of_family'] ?? '');

        if ($household->addHousehold(
            $house_number,
            $street,
            $purok,
            $barangay,
            $head_of_family
        )) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Added household: " . $house_number . " " . $street
            );

            header("Location: ../households.php?status=added");
            exit();
        }
    }

    if (isset($_POST['update_household'])) {

        $household_id = (int)($_POST['household_id'] ?? 0);
        $house_number = trim($_POST['house_number'] ?? '');
        $street = trim($_POST['street'] ?? '');
        $purok = trim($_POST['purok'] ?? '');
        $barangay = trim($_POST['barangay'] ?? '');
        $head_of_family = trim($_POST['head_of_family'] ?? '');

        if ($household_id > 0 && $household->updateHousehold(
            $household_id,
            $house_number,
            $street,
            $purok,
            $barangay,
            $head_of_family
        )) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Updated household ID: " . $household_id
            );

            header("Location: ../households.php?status=updated");
            exit();
        }
    }

    if (isset($_POST['delete_household'])) {

        $household_id = (int)($_POST['household_id'] ?? 0);

        if ($household_id > 0 && $household->deleteHousehold($household_id)) {

            $activityLog->addLog(
                $_SESSION['user_id'],
                "Deleted household ID: " . $household_id
            );

            header("Location: ../households.php?status=deleted");
            exit();
        }
    }

    header("Location: ../households.php?status=error");
    exit();

} catch (PDOException $e) {

    header("Location: ../households.php?status=error");
    exit();
}

?>
