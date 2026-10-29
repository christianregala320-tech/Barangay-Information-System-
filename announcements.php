<?php

require_once("includes/auth.php");
require_once("connection.php");
require_once("classes/Announcement.php");

$announcement = new Announcement($conn);
$announcements = $announcement->getAllAnnouncements();

$page_title = "Announcements";
require_once("includes/header.php");

?>

<div class="page-heading">
    <div>
        <h1>Announcements</h1>
        <p>Post barangay announcements that will also appear on the dashboard.</p>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h2>Add Announcement</h2>
    </div>

    <form action="handlers/announcement_handler.php" method="POST" class="form-grid">

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" placeholder="Announcement title">
        </div>

        <div class="form-group">
            <label>Date Posted</label>
            <input type="date" name="date_posted" value="<?= date('Y-m-d'); ?>">
        </div>

        <div class="form-group full-width">
            <label>Content</label>
            <textarea name="content" rows="4" placeholder="Announcement details"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" name="add_announcement" class="btn primary">
                Post Announcement
            </button>
        </div>
    </form>
</div>

<div class="panel">
    <div class="panel-heading">
        <h2>Announcement List</h2>
    </div>

    <?php foreach ($announcements as $row): ?>
        <div class="announcement-item large">
            <div>
                <h3><?= htmlspecialchars($row['Title']); ?></h3>
                <p><?= nl2br(htmlspecialchars($row['Content'])); ?></p>
                <small>
                    <?= htmlspecialchars($row['DatePosted']); ?>
                    · <?= htmlspecialchars($row['PostedBy']); ?>
                </small>
            </div>

            <form action="handlers/announcement_handler.php" method="POST"
                  onsubmit="return confirm('Delete this announcement?');">
                <input type="hidden" name="announcement_id" value="<?= $row['AnnouncementID']; ?>">
                <button type="submit" name="delete_announcement" class="btn small danger">
                    Delete
                </button>
            </form>
        </div>
    <?php endforeach; ?>

    <?php if (count($announcements) == 0): ?>
        <p class="muted">No announcements yet.</p>
    <?php endif; ?>
</div>

<?php require_once("includes/footer.php"); ?>
