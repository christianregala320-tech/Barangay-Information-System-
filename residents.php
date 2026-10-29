<?php

require_once("includes/auth.php");
require_once("connection.php");
require_once("classes/Resident.php");
require_once("classes/Household.php");

$resident = new Resident($conn);
$household = new Household($conn);

$keyword = trim($_GET['search'] ?? '');
$edit_id = (int)($_GET['edit'] ?? 0);

if ($keyword != '') {
    $residents = $resident->searchResidents($keyword);
} else {
    $residents = $resident->getAllResidents();
}

$households = $household->getAllHouseholds();
$edit_resident = $edit_id > 0 ? $resident->getResidentById($edit_id) : null;

$page_title = "Residents";
require_once("includes/header.php");

$status = $_GET['status'] ?? '';
?>

<div class="page-heading">
    <div>
        <h1>Resident Records</h1>
        <p>Add, search, update, and delete barangay residents.</p>
    </div>
</div>

<?php if ($status != ''): ?>
    <div class="alert success">
        <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $status))); ?>
    </div>
<?php endif; ?>

<div class="panel">
    <div class="panel-heading">
        <h2><?= $edit_resident ? 'Update Resident' : 'Add Resident'; ?></h2>
    </div>

    <form action="handlers/resident_handler.php" method="POST" class="form-grid">

        <?php if ($edit_resident): ?>
            <input type="hidden" name="resident_id" value="<?= $edit_resident['ResidentID']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Household</label>
            <select name="household_id">
                <option value="0">No household selected</option>

                <?php foreach ($households as $row): ?>
                    <option value="<?= $row['HouseholdID']; ?>"
                        <?= $edit_resident && $edit_resident['HouseholdID'] == $row['HouseholdID'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars(
                            $row['HouseNumber'] . ' ' .
                            $row['Street'] . ' - ' .
                            $row['Purok']
                        ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name"
                value="<?= htmlspecialchars($edit_resident['FirstName'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name"
                value="<?= htmlspecialchars($edit_resident['LastName'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Middle Name</label>
            <input type="text" name="middle_name"
                value="<?= htmlspecialchars($edit_resident['MiddleName'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Birth Date</label>
            <input type="date" name="birth_date"
                value="<?= htmlspecialchars($edit_resident['BirthDate'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender">
                <option value="">Select gender</option>
                <option value="Male" <?= ($edit_resident['Gender'] ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?= ($edit_resident['Gender'] ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>

        <div class="form-group">
            <label>Civil Status</label>
            <select name="civil_status">
                <option value="">Select civil status</option>
                <?php
                    $statuses = ['Single', 'Married', 'Widowed', 'Separated'];
                    foreach ($statuses as $civil):
                ?>
                    <option value="<?= $civil; ?>"
                        <?= ($edit_resident['CivilStatus'] ?? '') == $civil ? 'selected' : ''; ?>>
                        <?= $civil; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Occupation</label>
            <input type="text" name="occupation"
                value="<?= htmlspecialchars($edit_resident['Occupation'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact_number"
                value="<?= htmlspecialchars($edit_resident['ContactNumber'] ?? ''); ?>">
        </div>

        <div class="form-actions">
            <?php if ($edit_resident): ?>
                <button type="submit" name="update_resident" class="btn primary">Update Resident</button>
                <a href="residents.php" class="btn light">Cancel</a>
            <?php else: ?>
                <button type="submit" name="add_resident" class="btn primary">Add Resident</button>
            <?php endif; ?>
        </div>

    </form>
</div>

<div class="panel">
    <div class="panel-heading table-heading">
        <h2>Resident List</h2>

        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search resident..."
                value="<?= htmlspecialchars($keyword); ?>">
            <button class="btn primary">Search</button>

            <?php if ($keyword != ''): ?>
                <a href="residents.php" class="btn light">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Birth Date</th>
                    <th>Gender</th>
                    <th>Civil Status</th>
                    <th>Occupation</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($residents as $row): ?>
                    <tr>
                        <td><?= $row['ResidentID']; ?></td>
                        <td>
                            <?= htmlspecialchars(
                                $row['FirstName'] . ' ' .
                                $row['MiddleName'] . ' ' .
                                $row['LastName']
                            ); ?>
                        </td>
                        <td><?= htmlspecialchars($row['BirthDate']); ?></td>
                        <td><?= htmlspecialchars($row['Gender']); ?></td>
                        <td><?= htmlspecialchars($row['CivilStatus']); ?></td>
                        <td><?= htmlspecialchars($row['Occupation']); ?></td>
                        <td><?= htmlspecialchars($row['ContactNumber']); ?></td>
                        <td>
                            <?= htmlspecialchars(
                                trim(
                                    ($row['HouseNumber'] ?? '') . ' ' .
                                    ($row['Street'] ?? '') . ' ' .
                                    ($row['Purok'] ?? '') . ' ' .
                                    ($row['Barangay'] ?? '')
                                )
                            ); ?>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="residents.php?edit=<?= $row['ResidentID']; ?>" class="btn small edit">
                                    Edit
                                </a>

                                <form action="handlers/resident_handler.php" method="POST"
                                      onsubmit="return confirm('Delete this resident?');">
                                    <input type="hidden" name="resident_id" value="<?= $row['ResidentID']; ?>">
                                    <button type="submit" name="delete_resident" class="btn small danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($residents) == 0): ?>
                    <tr>
                        <td colspan="9" class="empty-row">No resident record found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>
