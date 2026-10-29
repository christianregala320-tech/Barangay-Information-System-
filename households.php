<?php

require_once("includes/auth.php");
require_once("connection.php");
require_once("classes/Household.php");

$household = new Household($conn);

$edit_id = (int)($_GET['edit'] ?? 0);
$edit_household = $edit_id > 0 ? $household->getHouseholdById($edit_id) : null;
$households = $household->getAllHouseholds();

$page_title = "Households";
require_once("includes/header.php");

?>

<div class="page-heading">
    <div>
        <h1>Household Management</h1>
        <p>Organize residents according to their household.</p>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h2><?= $edit_household ? 'Update Household' : 'Add Household'; ?></h2>
    </div>

    <form action="handlers/household_handler.php" method="POST" class="form-grid">

        <?php if ($edit_household): ?>
            <input type="hidden" name="household_id" value="<?= $edit_household['HouseholdID']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>House Number</label>
            <input type="text" name="house_number"
                value="<?= htmlspecialchars($edit_household['HouseNumber'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Street</label>
            <input type="text" name="street"
                value="<?= htmlspecialchars($edit_household['Street'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Purok</label>
            <input type="text" name="purok"
                value="<?= htmlspecialchars($edit_household['Purok'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Barangay</label>
            <input type="text" name="barangay"
                value="<?= htmlspecialchars($edit_household['Barangay'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Head of Family</label>
            <input type="text" name="head_of_family"
                value="<?= htmlspecialchars($edit_household['HeadOfFamily'] ?? ''); ?>">
        </div>

        <div class="form-actions">
            <?php if ($edit_household): ?>
                <button type="submit" name="update_household" class="btn primary">Update Household</button>
                <a href="households.php" class="btn light">Cancel</a>
            <?php else: ?>
                <button type="submit" name="add_household" class="btn primary">Add Household</button>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="panel">
    <div class="panel-heading">
        <h2>Household List</h2>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>House No.</th>
                    <th>Street</th>
                    <th>Purok</th>
                    <th>Barangay</th>
                    <th>Head of Family</th>
                    <th>Members</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($households as $row): ?>
                    <tr>
                        <td><?= $row['HouseholdID']; ?></td>
                        <td><?= htmlspecialchars($row['HouseNumber']); ?></td>
                        <td><?= htmlspecialchars($row['Street']); ?></td>
                        <td><?= htmlspecialchars($row['Purok']); ?></td>
                        <td><?= htmlspecialchars($row['Barangay']); ?></td>
                        <td><?= htmlspecialchars($row['HeadOfFamily']); ?></td>
                        <td><?= $row['TotalMembers']; ?></td>
                        <td>
                            <div class="actions">
                                <a href="households.php?edit=<?= $row['HouseholdID']; ?>" class="btn small edit">
                                    Edit
                                </a>

                                <form action="handlers/household_handler.php" method="POST"
                                      onsubmit="return confirm('Delete this household? Residents connected to this household must be reassigned first.');">
                                    <input type="hidden" name="household_id" value="<?= $row['HouseholdID']; ?>">
                                    <button type="submit" name="delete_household" class="btn small danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($households) == 0): ?>
                    <tr>
                        <td colspan="8" class="empty-row">No household record found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>
