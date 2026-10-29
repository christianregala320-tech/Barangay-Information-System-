<?php

require_once("includes/auth.php");
require_once("connection.php");

$gender = trim($_GET['gender'] ?? '');
$purok = trim($_GET['purok'] ?? '');

$query = "SELECT residents.*,
                 households.Purok,
                 households.HouseNumber,
                 households.Street,
                 households.Barangay,
                 TIMESTAMPDIFF(YEAR, residents.BirthDate, CURDATE()) AS Age
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
$report_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Reports";
require_once("includes/header.php");

?>

<div class="page-heading no-print">
    <div>
        <h1>Resident Reports</h1>
        <p>Filter resident records and print a simple barangay report.</p>
    </div>
</div>

<div class="panel no-print">
    <form method="GET" class="form-grid">

        <div class="form-group">
            <label>Gender</label>
            <select name="gender">
                <option value="">All Gender</option>
                <option value="Male" <?= $gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?= $gender == 'Female' ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>

        <div class="form-group">
            <label>Purok</label>
            <input type="text" name="purok" value="<?= htmlspecialchars($purok); ?>"
                   placeholder="Example: Purok 1">
        </div>

        <div class="form-actions">
            <button class="btn primary">Filter Report</button>
            <a href="reports.php" class="btn light">Clear</a>
            <button type="button" class="btn edit" onclick="window.print()">Print</button>
            <a href="export_csv.php?gender=<?= urlencode($gender); ?>&purok=<?= urlencode($purok); ?>"
               class="btn edit">
                Export CSV
            </a>
        </div>
    </form>
</div>

<div class="print-title">
    <h2>Barangay Resident Report</h2>
    <p>Generated on <?= date('F d, Y'); ?></p>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Civil Status</th>
                    <th>Occupation</th>
                    <th>Purok</th>
                    <th>Contact</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($report_rows as $row): ?>
                    <tr>
                        <td><?= $row['ResidentID']; ?></td>
                        <td><?= htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></td>
                        <td><?= htmlspecialchars($row['Age']); ?></td>
                        <td><?= htmlspecialchars($row['Gender']); ?></td>
                        <td><?= htmlspecialchars($row['CivilStatus']); ?></td>
                        <td><?= htmlspecialchars($row['Occupation']); ?></td>
                        <td><?= htmlspecialchars($row['Purok']); ?></td>
                        <td><?= htmlspecialchars($row['ContactNumber']); ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($report_rows) == 0): ?>
                    <tr>
                        <td colspan="8" class="empty-row">No report data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>
