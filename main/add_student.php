<?php
require_once 'db.php';

// Handle Add
if (isset($_POST['add'])) {
    $lrn = trim($_POST['lrn']);
    $name = trim($_POST['student_name']);
    $grade = trim($_POST['current_grade']);
    $section = trim($_POST['current_section']);
    $prev_school = trim($_POST['prev_school']);

    $stmt = $conn->prepare("INSERT INTO students (lrn, student_name, current_grade, current_section, prev_school) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $lrn, $name, $grade, $section, $prev_school);
    
    if ($stmt->execute()) {
        header("Location: sf10.php?search=" . urlencode($name));
        exit();
    } else {
        $error = "Add failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - OCNHS</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/sf10.css">
</head>
<body>
    <div class="watermark-bg"></div>
    <div class="sf10-wrapper">
        <div class="sf10-header">
            <div class="header-left">
                <a href="sf10.php" class="back-link">&larr; Back to Dashboard</a>
                <h1>New Student Record</h1>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-msg" style="color:red; margin-bottom:20px; font-weight:bold;"><?= $error ?></div>
        <?php endif; ?>

        <div class="student-profile-card fade-in">
            <form action="add_student.php" method="POST" style="display:flex; flex-direction:column; gap:20px;">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" name="student_name" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <label>LRN</label>
                        <input type="text" name="lrn" placeholder="12-digit LRN">
                    </div>
                    <div class="form-group">
                        <label>Current Grade</label>
                        <input type="text" name="current_grade" placeholder="e.g., Grade 10">
                    </div>
                    <div class="form-group">
                        <label>Section / Track</label>
                        <input type="text" name="current_section" placeholder="e.g., Einstein / STEM">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Previous School / Origin</label>
                    <input type="text" name="prev_school" placeholder="School Name">
                </div>

                <div class="profile-actions" style="margin-top:20px;">
                    <button type="submit" name="add" class="action-btn-primary">Save New Record</button>
                    <a href="sf10.php" class="action-btn-outline" style="text-decoration:none; text-align:center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
