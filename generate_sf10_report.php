<?php
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;
$logs = [];

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    
    if($student){
        // Fetch logs for this student
        $logStmt = $conn->prepare("SELECT * FROM certificate_logs WHERE student_name = ? OR (lrn != '' AND lrn = ?) ORDER BY created_at DESC");
        $logStmt->bind_param("ss", $student['student_name'], $student['lrn']);
        $logStmt->execute();
        $logsResult = $logStmt->get_result();
        while($row = $logsResult->fetch_assoc()) {
            $logs[] = $row;
        }
    }
}

if (!$student) {
    die("Student record not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SF10 Report - <?= htmlspecialchars($student['student_name']) ?></title>
    <link rel="stylesheet" href="../style/certificate.css">
    <style>
        .sf10-report { 
            font-family: "Bookman Old Style", serif; 
            padding: 0.5in !important; 
            height: auto !important;
            min-height: 11.23in;
            display: flex;
            flex-direction: column;
        }
        .sf10-report .header { 
            position: relative !important; 
            top: 0 !important; 
            left: 0 !important; 
            right: 0 !important; 
            margin-bottom: 30px;
        }
        .sf10-report .republic-text { font-size: 11pt; margin-bottom: 2px; }
        .sf10-report .dept-text { font-size: 14pt; margin-bottom: 5px; }
        .sf10-report .school-name { font-size: 12pt; margin-bottom: 5px; }
        .sf10-report .region-text, .sf10-report .division-text { font-size: 10pt; }
        
        .sf10-report h2 { 
            text-align: center; 
            text-decoration: underline; 
            margin-top: 10px;
            margin-bottom: 30px; 
            font-size: 18pt;
            font-weight: bold;
        }
        .student-info-section { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 15px; 
            border: 2px solid #000; 
            padding: 20px; 
            margin-bottom: 30px; 
            position: relative;
        }
        .student-info-section div { margin-bottom: 8px; font-size: 11pt; }
        .activity-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            position: relative;
        }
        .activity-table th, .activity-table td { border: 1px solid #000; padding: 12px; text-align: left; font-size: 10pt; }
        .activity-table th { background: #f8f8f8; font-weight: bold; }

        .report-footer {
            margin-top: auto;
            padding-top: 50px;
            text-align: right;
            padding-right: 50px;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white; padding: 0; }
            .sf10-report { 
                padding: 0.5in !important; 
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="padding: 20px; text-align: center; background: #f4f4f4; border-bottom: 1px solid #ddd;">
        <button class="action-btn print-btn" onclick="window.print()">Print SF10 Report</button>
        <button class="action-btn edit-btn" onclick="window.history.back()">Back</button>
    </div>

    <div class="certificate-container sf10-report">
    <div class="header">
        <img src="../resources/deped_seal_new.png" class="header-logo" alt="DepEd Seal" style="height: 60px;">
        <p class="republic-text">Republic of the Philippines</p>
        <p class="dept-text">Department of Education</p>
        <p class="school-name">OLONGAPO CITY NATIONAL HIGH SCHOOL</p>
        <div class="header-line"></div>
    </div>

    <h2>LEARNER'S PERMANENT RECORD (SF10)</h2>

    <div class="student-info-section">
        <div><strong>Name:</strong> <?= htmlspecialchars($student['student_name']) ?></div>
        <div><strong>LRN:</strong> <?= htmlspecialchars($student['lrn'] ?: 'Not Set') ?></div>
        <div><strong>Grade & Section:</strong> <?= htmlspecialchars($student['current_grade'] . " - " . $student['current_section']) ?></div>
        <div><strong>Previous School:</strong> <?= htmlspecialchars($student['prev_school'] ?: 'N/A') ?></div>
        <div><strong>Curriculum:</strong> <?= htmlspecialchars($student['curriculum'] ?? 'N/A') ?></div>
    </div>

    <h3 style="margin-top: 30px;">Activity Logs & Issued Certificates</h3>
    <table class="activity-table">
        <thead>
            <tr>
                <th>Date Issued</th>
                <th>Certificate Type</th>
                <th>School Year</th>
                <th>Purpose</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($logs): ?>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= date('M d, Y', strtotime($log['created_at'])) ?></td>
                    <td><?= htmlspecialchars($log['certificate_type']) ?></td>
                    <td><?= htmlspecialchars($log['school_year']) ?></td>
                    <td><?= htmlspecialchars($log['purpose']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No certificate history found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="report-footer">
        <p>_____________________________________</p>
        <p><strong>School Registrar / Head</strong></p>
    </div>
    </div>
</body>
</html>
