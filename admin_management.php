<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

// Only super admins can access this page
require_super_admin();

$msg = '';
$error = '';

// Handle password change for an admin
if (isset($_POST['change_password'])) {
    $admin_id = $_POST['admin_id'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($new_password) || empty($confirm_password)) {
        $error = "Password fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ? AND role = 'admin'");
        $stmt->bind_param("si", $hashed_password, $admin_id);
        if ($stmt->execute()) {
            $msg = "Admin password updated successfully!";
        } else {
            $error = "Failed to update admin password.";
        }
    }
}

// Handle admin creation
if (isset($_POST['create_admin'])) {
    $username = trim($_POST['username'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($full_name) || empty($password)) {
        $error = "All fields are required for creating an admin.";
    } else {
        // Check if username exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, full_name, password, role) VALUES (?, ?, ?, 'admin')");
            $stmt->bind_param("sss", $username, $full_name, $hashed_password);
            if ($stmt->execute()) {
                $msg = "Admin account created successfully!";
            } else {
                $error = "Failed to create admin account.";
            }
        }
    }
}

// Handle admin deletion
if (isset($_POST['delete_admin'])) {
    $admin_id = $_POST['admin_id'] ?? '';
    
    // Safety check: ensure we are not deleting a super_admin
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'admin'");
    $stmt->bind_param("i", $admin_id);
    if ($stmt->execute()) {
        $msg = "Admin account deleted successfully!";
    } else {
        $error = "Failed to delete admin account.";
    }
}

// Fetch all admins
$admins = $conn->query("SELECT id, username, full_name, last_login FROM users WHERE role = 'admin' ORDER BY created_at DESC");

$page_title = "Admin Management";
require_once 'includes/header.php';
?>

<div class="container" style="max-width: 1000px; margin: 40px auto; padding: 20px;">
    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="color: white; margin: 0; font-size: 2.5rem; font-weight: 800;">Admin Management</h1>
            <p style="color: #94a3b8; margin-top: 5px;">Create and manage system administrators.</p>
        </div>
        <button onclick="document.getElementById('createAdminModal').style.display='flex'" style="background: linear-gradient(to right, #4f46e5, #818cf8); color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);">
            <span>➕</span> Create New Admin
        </button>
    </div>

    <?php if ($msg): ?>
        <div style="background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 25px; animation: slideIn 0.3s ease-out;">
            ✅ <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 25px; animation: slideIn 0.3s ease-out;">
            ⚠️ <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="admin-list" style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.05);">
                    <th style="padding: 20px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Admin Name</th>
                    <th style="padding: 20px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Username</th>
                    <th style="padding: 20px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Last Login</th>
                    <th style="padding: 20px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($admins->num_rows > 0): ?>
                    <?php while ($admin = $admins->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 20px; color: white;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 35px; height: 35px; background: rgba(79, 70, 229, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #818cf8; font-weight: 700;">
                                        <?php echo strtoupper(substr($admin['full_name'], 0, 1)); ?>
                                    </div>
                                    <?php echo htmlspecialchars($admin['full_name']); ?>
                                </div>
                            </td>
                            <td style="padding: 20px; color: #cbd5e1;"><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td style="padding: 20px; color: #94a3b8;">
                                <?php echo $admin['last_login'] ? date('M j, Y, g:i a', strtotime($admin['last_login'])) : '<span style="color: #64748b; font-style: italic;">Never logged in</span>'; ?>
                            </td>
                            <td style="padding: 20px;">
                                <div style="display: flex; gap: 8px;">
                                    <button onclick="openChangePasswordModal(<?php echo $admin['id']; ?>, '<?php echo htmlspecialchars($admin['username']); ?>')" style="background: rgba(255, 255, 255, 0.05); color: #cbd5e1; border: 1px solid rgba(255, 255, 255, 0.1); padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 0.85rem; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white';" onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.color='#cbd5e1';">
                                        🔑 Password
                                    </button>
                                    <button onclick="openDeleteModal(<?php echo $admin['id']; ?>, '<?php echo htmlspecialchars($admin['username']); ?>')" style="background: rgba(239, 68, 68, 0.1); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.2); padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 0.85rem; transition: all 0.3s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.2)'; this.style.color='white';" onmouseout="this.style.background='rgba(239, 68, 68, 0.1)'; this.style.color='#fca5a5';">
                                        🗑️ Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 60px; text-align: center; color: #64748b;">
                            <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;">👤</div>
                            <p>No admin accounts found other than Super Admin.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Admin Modal -->
<div id="createAdminModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); z-index: 2000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #1e293b; width: 100%; max-width: 450px; border-radius: 24px; padding: 40px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); animation: modalFade 0.3s ease-out;">
        <h2 style="color: white; margin-top: 0; margin-bottom: 30px;">Create Admin Account</h2>
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 8px;">Full Name</label>
                <input type="text" name="full_name" required style="width: 100%; background: #0f172a; border: 1px solid #334155; padding: 12px 16px; border-radius: 12px; color: white; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 8px;">Username</label>
                <input type="text" name="username" required style="width: 100%; background: #0f172a; border: 1px solid #334155; padding: 12px 16px; border-radius: 12px; color: white; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 30px;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 8px;">Password</label>
                <input type="password" name="password" required style="width: 100%; background: #0f172a; border: 1px solid #334155; padding: 12px 16px; border-radius: 12px; color: white; box-sizing: border-box;">
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" name="create_admin" style="flex: 2; background: linear-gradient(to right, #4f46e5, #818cf8); color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; cursor: pointer;">Create Admin</button>
                <button type="button" onclick="document.getElementById('createAdminModal').style.display='none'" style="flex: 1; background: transparent; border: 1px solid #334155; color: #cbd5e1; padding: 14px; border-radius: 12px; cursor: pointer;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); z-index: 2000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #1e293b; width: 100%; max-width: 450px; border-radius: 24px; padding: 40px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); animation: modalFade 0.3s ease-out;">
        <h2 style="color: white; margin-top: 0; margin-bottom: 10px;">Change Password</h2>
        <p style="color: #94a3b8; margin-bottom: 30px;">Reset password for admin: <strong id="adminUsername" style="color: white;"></strong></p>
        <form method="POST">
            <input type="hidden" name="admin_id" id="modal_admin_id">
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 8px;">New Password</label>
                <input type="password" name="new_password" required style="width: 100%; background: #0f172a; border: 1px solid #334155; padding: 12px 16px; border-radius: 12px; color: white; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 30px;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 8px;">Confirm New Password</label>
                <input type="password" name="confirm_password" required style="width: 100%; background: #0f172a; border: 1px solid #334155; padding: 12px 16px; border-radius: 12px; color: white; box-sizing: border-box;">
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" name="change_password" style="flex: 2; background: linear-gradient(to right, #4f46e5, #818cf8); color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; cursor: pointer;">Update Password</button>
                <button type="button" onclick="document.getElementById('passwordModal').style.display='none'" style="flex: 1; background: transparent; border: 1px solid #334155; color: #cbd5e1; padding: 14px; border-radius: 12px; cursor: pointer;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Admin Modal -->
<div id="deleteModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); z-index: 2000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #1e293b; width: 100%; max-width: 400px; border-radius: 24px; padding: 40px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); text-align: center; animation: modalFade 0.3s ease-out;">
        <div style="width: 70px; height: 70px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 2rem; color: #ef4444;">⚠️</div>
        <h2 style="color: white; margin-top: 0; margin-bottom: 10px;">Confirm Deletion</h2>
        <p style="color: #94a3b8; margin-bottom: 30px;">Are you sure you want to delete admin <strong id="deleteAdminUsername" style="color: white;"></strong>? This action cannot be undone.</p>
        <form method="POST">
            <input type="hidden" name="admin_id" id="delete_admin_id">
            <div style="display: flex; gap: 12px;">
                <button type="submit" name="delete_admin" style="flex: 2; background: #ef4444; color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.3s;">Delete Account</button>
                <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" style="flex: 1; background: transparent; border: 1px solid #334155; color: #cbd5e1; padding: 14px; border-radius: 12px; cursor: pointer;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openChangePasswordModal(id, username) {
    document.getElementById('modal_admin_id').value = id;
    document.getElementById('adminUsername').innerText = username;
    document.getElementById('passwordModal').style.display = 'flex';
}

function openDeleteModal(id, username) {
    document.getElementById('delete_admin_id').value = id;
    document.getElementById('deleteAdminUsername').innerText = username;
    document.getElementById('deleteModal').style.display = 'flex';
}

// Close modals if clicked outside
window.onclick = function(event) {
    if (event.target == document.getElementById('createAdminModal')) {
        document.getElementById('createAdminModal').style.display = 'none';
    }
    if (event.target == document.getElementById('passwordModal')) {
        document.getElementById('passwordModal').style.display = 'none';
    }
    if (event.target == document.getElementById('deleteModal')) {
        document.getElementById('deleteModal').style.display = 'none';
    }
}
</script>

<style>
@keyframes slideIn {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
@keyframes modalFade {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<?php require_once 'includes/footer.php'; ?>
