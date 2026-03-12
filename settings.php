<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
require_once 'includes/header.php';

require_login();

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
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
                $msg = "Password changed successfully!";
            } else {
                $error = "System error updating password.";
            }
        } else {
            $error = "Incorrect current password.";
        }
    }
}
?>

<div class="container" style="max-width: 600px; margin: 40px auto; padding: 20px;">
    <div class="card" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px);">
        <h2 style="margin-top: 0; color: #fff; font-size: 24px; font-weight: 700;">Account Settings</h2>
        <p style="color: #94a3b8; margin-bottom: 30px;">Manage your login credentials and personal information.</p>

        <?php if ($msg): ?>
            <div style="background: rgba(34, 197, 94, 0.1); border-left: 4px solid #22c55e; color: #86efac; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                <span style="font-size: 18px; margin-right: 10px;">✅</span> <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; color: #fca5a5; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                <span style="font-size: 18px; margin-right: 10px;">⚠️</span> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group floating" style="margin-bottom: 25px;">
                <input type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly style="background: rgba(0,0,0,0.2); cursor: not-allowed; opacity: 0.6;">
                <label>Username</label>
            </div>

            <h3 style="color: #cbd5e1; font-size: 16px; margin: 30px 0 15px; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 25px;">Change Password</h3>

            <div class="form-group floating" style="margin-bottom: 20px;">
                <input type="password" name="current_password" required placeholder=" ">
                <label>Current Password</label>
            </div>

            <div class="form-group floating" style="margin-bottom: 20px;">
                <input type="password" name="new_password" required placeholder=" ">
                <label>New Password</label>
            </div>

            <div class="form-group floating" style="margin-bottom: 30px;">
                <input type="password" name="confirm_password" required placeholder=" ">
                <label>Confirm New Password</label>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="submit-btn" style="flex: 1; padding: 15px; background: linear-gradient(to right, #4f46e5, #818cf8); border: none; border-radius: 12px; color: white; font-weight: 700; cursor: pointer;">Save Changes</button>
                <a href="index.php" style="flex: 1; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: #94a3b8; font-weight: 600;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php 
// Special CSS for settings page that mimics the floating labels of the main forms
?>
<style>
    .form-group.floating {
        position: relative;
    }
    .form-group.floating input {
        width: 100%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.2rem 1rem 0.5rem;
        border-radius: 12px;
        color: white;
        font-family: inherit;
        font-size: 1rem;
        transition: all 0.3s;
        box-sizing: border-box;
    }
    .form-group.floating label {
        position: absolute;
        top: 1rem;
        left: 1rem;
        color: #64748b;
        pointer-events: none;
        transition: all 0.3s;
    }
    .form-group.floating input:focus ~ label,
    .form-group.floating input:not(:placeholder-shown) ~ label {
        top: 0.4rem;
        font-size: 0.75rem;
        color: #818cf8;
    }
    .form-group.floating input:focus {
        border-color: #4f46e5;
        background: rgba(79, 70, 229, 0.05);
        outline: none;
    }
</style>

</body>
</html>
