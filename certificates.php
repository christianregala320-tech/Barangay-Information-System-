<?php

require_once("includes/auth.php");
require_once("connection.php");
require_once("classes/Certificate.php");
require_once("classes/Resident.php");

$certificate = new Certificate($conn);
$resident = new Resident($conn);

$certificates = $certificate->getAllCertificates();
$residents = $resident->getAllResidents();

$page_title = "Certificates";
require_once("includes/header.php");

?>

<div class="page-heading">
    <div>
        <h1>Certificate Management</h1>
        <p>Record barangay certificates and clearances issued to residents.</p>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h2>Issue Certificate</h2>
    </div>

    <form action="handlers/certificate_handler.php" method="POST" class="form-grid">

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
            <label>Certificate Type</label>
            <select name="certificate_type">
                <option value="Barangay Clearance">Barangay Clearance</option>
                <option value="Certificate of Residency">Certificate of Residency</option>
                <option value="Certificate of Indigency">Certificate of Indigency</option>
            </select>
        </div>

        <div class="form-group">
            <label>Date Issued</label>
            <input type="date" name="date_issued" value="<?= date('Y-m-d'); ?>">
        </div>

        <div class="form-group full-width">
            <label>Purpose</label>
            <textarea name="purpose" rows="3" placeholder="Purpose of certificate"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" name="add_certificate" class="btn primary">
                Save Certificate
            </button>
        </div>
    </form>
</div>

<div class="panel">
    <div class="panel-heading">
        <h2>Certificate Records</h2>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Type</th>
                    <th>Date Issued</th>
                    <th>Purpose</th>
                    <th>Issued By</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($certificates as $row): ?>
                    <tr>
                        <td><?= $row['CertificateID']; ?></td>
                        <td><?= htmlspecialchars($row['ResidentName']); ?></td>
                        <td><?= htmlspecialchars($row['CertificateType']); ?></td>
                        <td><?= htmlspecialchars($row['DateIssued']); ?></td>
                        <td><?= htmlspecialchars($row['Purpose']); ?></td>
                        <td><?= htmlspecialchars($row['IssuedBy']); ?></td>
                        <td>
                            <form action="handlers/certificate_handler.php" method="POST"
                                  onsubmit="return confirm('Delete this certificate record?');">
                                <input type="hidden" name="certificate_id" value="<?= $row['CertificateID']; ?>">
                                <button type="submit" name="delete_certificate" class="btn small danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($certificates) == 0): ?>
                    <tr>
                        <td colspan="7" class="empty-row">No certificate record found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>
