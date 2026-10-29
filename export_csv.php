<?php

require_once("includes/auth.php");
require_once("connection.php");

$gender = trim($_GET['gender'] ?? '');
$purok = trim($_GET['purok'] ?? '');

$query = "SELECT residents.ResidentID,
                 residents.FirstName,
                 residents.MiddleName,
                 residents.LastName,
                 residents.BirthDate,
                 TIMESTAMPDIFF(YEAR, residents.BirthDate, CURDATE()) AS Age,
                 residents.Gender,
                 residents.CivilStatus,
                 residents.Occupation,
                 residents.ContactNumber,
                 households.HouseNumber,
                 households.Street,
                 households.Purok,
                 households.Barangay
          FROM residents
          LEFT JOIN households
          ON residents.HouseholdID = households.HouseholdID
          WHERE 1 = 1";

$params = [];

if ($gender != '') {
    $query .= " AND residents.Gender = ?";
    $params[] = $gender;
}

if ($purok != '') {
    $query .= " AND households.Purok LIKE ?";
    $params[] = "%" . $purok . "%";
}

$query .= " ORDER BY residents.LastName ASC";

$stmt = $conn->prepare($query);
$stmt->execute($params);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="resident_report.csv"');

$output = fopen("php://output", "w");

fputcsv($output, [
    'Resident ID',
    'First Name',
    'Middle Name',
    'Last Name',
    'Birth Date',
    'Age',
    'Gender',
    'Civil Status',
    'Occupation',
    'Contact Number',
    'House Number',
    'Street',
    'Purok',
    'Barangay'
]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit();

?>
