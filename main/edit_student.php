<?php
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
}

if (!$student) {
    die("Student record not found.");
}

// Handle Update
if (isset($_POST['update'])) {
    $lrn = trim($_POST['lrn']);
    $name = trim($_POST['student_name']);
    $grade = trim($_POST['current_grade']);
    $section = trim($_POST['current_section']);
    $prev_school = trim($_POST['prev_school']);

    $updateStmt = $conn->prepare("UPDATE students SET lrn = ?, student_name = ?, current_grade = ?, current_section = ?, prev_school = ? WHERE id = ?");
    $updateStmt->bind_param("sssssi", $lrn, $name, $grade, $section, $prev_school, $id);
    
    if ($updateStmt->execute()) {
        header("Location: sf10.php?search=" . urlencode($name));
        exit();
    } else {
        $error = "Update failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - OCNHS</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/sf10.css">
</head>
<body>
    <div class="watermark-bg"></div>
    <div class="sf10-wrapper">
        <div class="sf10-header">
            <div class="header-left">
                <a href="sf10.php?search=<?= urlencode($student['student_name']) ?>" class="back-link">&larr; Back to Student</a>
                <h1>Edit Student Record</h1>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-msg" style="color:red; margin-bottom:20px; font-weight:bold;"><?= $error ?></div>
        <?php endif; ?>

        <div class="student-profile-card fade-in">
            <form action="edit_student.php?id=<?= $id ?>" method="POST" style="display:flex; flex-direction:column; gap:20px;">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" name="student_name" value="<?= htmlspecialchars($student['student_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>LRN</label>
                        <input type="text" name="lrn" value="<?= htmlspecialchars($student['lrn']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Current Grade</label>
                        <input type="text" name="current_grade" value="<?= htmlspecialchars($student['current_grade']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Section / Track</label>
                        <input type="text" name="current_section" value="<?= htmlspecialchars($student['current_section']) ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Previous School / Origin</label>
                    <input type="text" name="prev_school" value="<?= htmlspecialchars($student['prev_school']) ?>">
                </div>

                <div class="profile-actions" style="margin-top:20px;">
                    <button type="submit" name="update" class="action-btn-primary">Update Profile</button>
                    <a href="sf10.php?search=<?= urlencode($student['student_name']) ?>" class="action-btn-outline" style="text-decoration:none; text-align:center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
