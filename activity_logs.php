<?php

require_once("includes/auth.php");
require_once("connection.php");
require_once("classes/ActivityLog.php");

$activityLog = new ActivityLog($conn);
$logs = $activityLog->getAllLogs();

$page_title = "Activity Logs";
require_once("includes/header.php");

?>

<div class="page-heading">
    <div>
        <h1>Activity Logs</h1>
        <p>View actions performed by system users.</p>
    </div>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>User</th>
                    <th>Activity</th>
                    <th>Date and Time</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($logs as $row): ?>
                    <tr>
                        <td><?= $row['LogID']; ?></td>
                        <td><?= htmlspecialchars($row['FullName']); ?></td>
                        <td><?= htmlspecialchars($row['Activity']); ?></td>
                        <td><?= htmlspecialchars($row['DateTime']); ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($logs) == 0): ?>
                    <tr>
                        <td colspan="4" class="empty-row">No activity logs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>
