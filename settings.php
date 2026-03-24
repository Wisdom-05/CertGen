<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
require_once 'includes/header.php';

require_login();

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 1. Update Profile Info (Full Name)
    if (!empty($full_name)) {
        $stmt = $conn->prepare("UPDATE users SET full_name = ? WHERE id = ?");
        $stmt->bind_param("si", $full_name, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $full_name;
            $msg = "Profile updated successfully!";
        } else {
            $error = "System error updating profile.";
        }
    }

    // 2. Password change logic (only if fields are filled)
    if (!empty($current_password) || !empty($new_password)) {
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "All password fields are required to change password.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $error = "New password must be at least 6 characters.";
        } else {
            // Verify current password
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (password_verify($current_password, $user['password'])) {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
                
                if ($update_stmt->execute()) {
                    $msg = ($msg ? $msg . " and " : "") . "Password changed successfully!";
                } else {
                    $error = "System error updating password.";
                }
            } else {
                $error = "Incorrect current password.";
            }
        }
    }
}

// Fetch current user data from database to ensure fresh values
$stmt = $conn->prepare("SELECT username, full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$current_user = $stmt->get_result()->fetch_assoc();
?>

<div class="container" style="max-width: 600px; margin: 40px auto; padding: 20px;">
    <div class="card" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 40px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h2 style="margin-top: 0; color: #1e293b; font-size: 24px; font-weight: 700;">Account Settings</h2>
        <p style="color: #64748b; margin-bottom: 30px;">Manage your login credentials and personal information.</p>

        <?php if ($msg): ?>
            <div style="background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                <span style="font-size: 18px; margin-right: 10px;">✅</span> <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                <span style="font-size: 18px; margin-right: 10px;">⚠️</span> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group floating" style="margin-bottom: 25px;">
                <input type="text" value="<?php echo htmlspecialchars($current_user['username']); ?>" readonly style="background: #f8fafc; color: #64748b; cursor: not-allowed; border-color: #e2e8f0;">
                <label>Username (Fixed)</label>
            </div>

            <div class="form-group floating" style="margin-bottom: 25px;">
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($current_user['full_name']); ?>" required placeholder=" ">
                <label>Display Name / Full Name</label>
            </div>

            <h3 style="color: #475569; font-size: 16px; margin: 30px 0 15px; border-top: 1px solid #f1f5f9; padding-top: 25px;">Change Password</h3>

            <div class="form-group floating" style="margin-bottom: 20px;">
                <input type="password" name="current_password" placeholder=" ">
                <label>Current Password</label>
            </div>

            <div class="form-group floating" style="margin-bottom: 20px;">
                <input type="password" name="new_password" placeholder=" ">
                <label>New Password</label>
            </div>

            <div class="form-group floating" style="margin-bottom: 30px;">
                <input type="password" name="confirm_password" placeholder=" ">
                <label>Confirm New Password</label>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="submit-btn" style="flex: 1; padding: 15px; background: linear-gradient(to right, #4f46e5, #818cf8); border: none; border-radius: 12px; color: white; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);">Save Changes</button>
                <a href="index.php" style="flex: 1; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; font-weight: 600; transition: background 0.3s;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php 
// Updated CSS for high contrast
?>
<style>
    .form-group.floating {
        position: relative;
    }
    .form-group.floating input {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 1.5rem 1rem 0.6rem;
        border-radius: 12px;
        color: #1e293b;
        font-family: inherit;
        font-size: 1rem;
        transition: all 0.3s;
        box-sizing: border-box;
    }
    .form-group.floating label {
        position: absolute;
        top: 1rem;
        left: 1rem;
        color: #94a3b8;
        pointer-events: none;
        transition: all 0.3s;
    }
    .form-group.floating input:focus ~ label,
    .form-group.floating input:not(:placeholder-shown) ~ label {
        top: 0.4rem;
        font-size: 0.75rem;
        color: #4f46e5;
        font-weight: 600;
    }
    .form-group.floating input:focus {
        border-color: #4f46e5;
        background: white;
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
    }
</style>

</body>
</html>
