<?php

require_once("includes/auth.php");
require_once("connection.php");
require_once("classes/BarangayRequest.php");
require_once("classes/Resident.php");

$request = new BarangayRequest($conn);
$resident = new Resident($conn);

$requests = $request->getAllRequests();
$residents = $resident->getAllResidents();

$page_title = "Requests";
require_once("includes/header.php");

?>

<div class="page-heading">
    <div>
        <h1>Resident Requests</h1>
        <p>Track certificate and barangay service requests.</p>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h2>Add Request</h2>
    </div>

    <form action="handlers/request_handler.php" method="POST" class="form-grid">

        <div class="form-group">
            <label>Resident</label>
            <select name="resident_id">
                <option value="">Select resident</option>
                <?php foreach ($residents as $row): ?>
                    <option value="<?= $row['ResidentID']; ?>">
                        <?= htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Request Type</label>
            <select name="request_type">
                <option value="Barangay Clearance">Barangay Clearance</option>
                <option value="Certificate of Residency">Certificate of Residency</option>
                <option value="Certificate of Indigency">Certificate of Indigency</option>
                <option value="Other Service">Other Service</option>
            </select>
        </div>

        <div class="form-group">
            <label>Request Date</label>
            <input type="date" name="request_date" value="<?= date('Y-m-d'); ?>">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Pending">Pending</option>
                <option value="Processing">Processing</option>
                <option value="Completed">Completed</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" name="add_request" class="btn primary">
                Add Request
            </button>
        </div>
    </form>
</div>

<div class="panel">
    <div class="panel-heading">
        <h2>Request Records</h2>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Request Type</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Update Status</th>
                    <th>Delete</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($requests as $row): ?>
                    <tr>
                        <td><?= $row['RequestID']; ?></td>
                        <td><?= htmlspecialchars($row['ResidentName']); ?></td>
                        <td><?= htmlspecialchars($row['RequestType']); ?></td>
                        <td><?= htmlspecialchars($row['RequestDate']); ?></td>
                        <td>
                            <span class="badge <?= strtolower($row['Status']); ?>">
                                <?= htmlspecialchars($row['Status']); ?>
                            </span>
                        </td>
                        <td>
                            <form action="handlers/request_handler.php" method="POST" class="inline-form">
                                <input type="hidden" name="request_id" value="<?= $row['RequestID']; ?>">
                                <select name="status">
                                    <option value="Pending" <?= $row['Status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Processing" <?= $row['Status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="Completed" <?= $row['Status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                </select>
                                <button type="submit" name="update_request_status" class="btn small edit">
                                    Update
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="handlers/request_handler.php" method="POST"
                                  onsubmit="return confirm('Delete this request?');">
                                <input type="hidden" name="request_id" value="<?= $row['RequestID']; ?>">
                                <button type="submit" name="delete_request" class="btn small danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($requests) == 0): ?>
                    <tr>
                        <td colspan="7" class="empty-row">No request record found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>
