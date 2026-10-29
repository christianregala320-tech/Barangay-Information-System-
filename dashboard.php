<?php

require_once("includes/auth.php");
require_once("connection.php");
require_once("classes/Resident.php");
require_once("classes/Household.php");
require_once("classes/Certificate.php");
require_once("classes/BarangayRequest.php");
require_once("classes/Announcement.php");

$resident = new Resident($conn);
$household = new Household($conn);
$certificate = new Certificate($conn);
$request = new BarangayRequest($conn);
$announcement = new Announcement($conn);

$total_residents = $resident->countResidents();
$total_households = $household->countHouseholds();
$total_certificates = $certificate->countCertificates();
$pending_requests = $request->countPendingRequests();
$male = $resident->countByGender("Male");
$female = $resident->countByGender("Female");
$latest_announcements = $announcement->getLatestAnnouncements(5);

$page_title = "Dashboard";
require_once("includes/header.php");

?>

<div class="page-heading">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome to the Barangay Resident Information System.</p>
    </div>
</div>

<div class="stats-grid">

    <div class="stat-card">
        <span>Total Residents</span>
        <strong><?= $total_residents; ?></strong>
    </div>

    <div class="stat-card">
        <span>Total Households</span>
        <strong><?= $total_households; ?></strong>
    </div>

    <div class="stat-card">
        <span>Certificates Issued</span>
        <strong><?= $total_certificates; ?></strong>
    </div>

    <div class="stat-card">
        <span>Pending Requests</span>
        <strong><?= $pending_requests; ?></strong>
    </div>

</div>

<div class="two-column">

    <div class="panel">
        <div class="panel-heading">
            <h2>Resident Summary</h2>
        </div>

        <div class="summary-row">
            <span>Male Residents</span>
            <strong><?= $male; ?></strong>
        </div>

        <div class="summary-row">
            <span>Female Residents</span>
            <strong><?= $female; ?></strong>
        </div>

        <div class="summary-row">
            <span>All Residents</span>
            <strong><?= $total_residents; ?></strong>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <h2>Latest Announcements</h2>
        </div>

        <?php if (count($latest_announcements) == 0): ?>
            <p class="muted">No announcements yet.</p>
        <?php endif; ?>

        <?php foreach ($latest_announcements as $item): ?>
            <div class="announcement-item">
                <h3><?= htmlspecialchars($item['Title']); ?></h3>
                <p><?= nl2br(htmlspecialchars($item['Content'])); ?></p>
                <small>
                    <?= htmlspecialchars($item['DatePosted']); ?>
                    · <?= htmlspecialchars($item['PostedBy']); ?>
                </small>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php require_once("includes/footer.php"); ?>
