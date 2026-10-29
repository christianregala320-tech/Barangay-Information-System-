<?php

require_once("includes/auth.php");
require_once("connection.php");
require_once("classes/User.php");

$user = new User($conn);
$user_data = $user->getUserById($_SESSION['user_id']);

$page_title = "Profile";
require_once("includes/header.php");

$status = $_GET['status'] ?? '';

?>

<div class="page-heading">
    <div>
        <h1>Profile Management</h1>
        <p>Update your barangay staff account information.</p>
    </div>
</div>

<?php if ($status != ''): ?>
    <div class="alert success">
        <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $status))); ?>
    </div>
<?php endif; ?>

<div class="two-column">

    <div class="panel">
        <div class="panel-heading">
            <h2>Account Information</h2>
        </div>

        <form action="handlers/profile_handler.php" method="POST">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name"
                    value="<?= htmlspecialchars($user_data['FullName']); ?>">
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" value="<?= htmlspecialchars($user_data['Username']); ?>" disabled>
            </div>

            <div class="form-group">
                <label>Role</label>
                <input type="text" value="<?= htmlspecialchars($user_data['Role']); ?>" disabled>
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact_number"
                    value="<?= htmlspecialchars($user_data['ContactNumber']); ?>">
            </div>

            <button type="submit" name="update_profile" class="btn primary">
                Save Changes
            </button>
        </form>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <h2>Change Password</h2>
        </div>

        <form action="handlers/profile_handler.php" method="POST">

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password">
            </div>

            <button type="submit" name="change_password" class="btn primary">
                Change Password
            </button>
        </form>
    </div>

</div>

<?php require_once("includes/footer.php"); ?>
