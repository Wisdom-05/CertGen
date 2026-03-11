<?php
/**
 * sf10.php - School Form 10 (Student Permanent Record) Dashboard
 */
require_once 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$view_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;
$students = [];
$logs = [];
$allStudents = [];

if ($view_id > 0) {
    // 1. Direct fetch by ID (from "View Record" button)
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $view_id);
    $stmt->execute();
    $studentResult = $stmt->get_result();
    if ($studentResult->num_rows > 0) {
        $student = $studentResult->fetch_assoc();
        
        // Fetch history for this student
        $logStmt = $conn->prepare("SELECT * FROM certificate_logs WHERE student_name = ? OR (lrn != '' AND lrn = ?) ORDER BY created_at DESC");
        $logStmt->bind_param("ss", $student['student_name'], $student['lrn']);
        $logStmt->execute();
        $logsResult = $logStmt->get_result();
        while($row = $logsResult->fetch_assoc()) {
            $logs[] = $row;
        }
    }
} elseif ($search) {
    // 2. Search
    $stmt = $conn->prepare("
        SELECT * FROM students 
        WHERE lrn = ? OR student_name LIKE ? 
        ORDER BY 
            (CASE WHEN student_name LIKE ? THEN 1 ELSE 2 END), 
            student_name ASC
    ");
    $searchWild = "%$search%";
    $searchStart = "$search%";
    $stmt->bind_param("sss", $search, $searchWild, $searchStart);
    $stmt->execute();
    $studentResult = $stmt->get_result();
    
    while ($row = $studentResult->fetch_assoc()) {
        $students[] = $row;
    }

    if (count($students) === 1) {
        $student = $students[0];
        $logStmt = $conn->prepare("SELECT * FROM certificate_logs WHERE student_name = ? OR (lrn != '' AND lrn = ?) ORDER BY created_at DESC");
        $logStmt->bind_param("ss", $student['student_name'], $student['lrn']);
        $logStmt->execute();
        $logsResult = $logStmt->get_result();
        while($row = $logsResult->fetch_assoc()) {
            $logs[] = $row;
        }
    }
} else {
    // 3. Fetch default list
    $listStmt = $conn->query("SELECT * FROM students ORDER BY student_name ASC LIMIT 50");
    if ($listStmt) {
        while($row = $listStmt->fetch_assoc()) {
            $allStudents[] = $row;
        }
    }
}

// Function to render the SF10 table row
function renderStudentRows($dataList) {
    $no = 1;
    if (empty($dataList)) return "<tr><td colspan='10' style='text-align:center;'>No records found.</td></tr>";
    
    $html = "";
    foreach ($dataList as $row) {
        $id = $row['id'] ?? 0;
        $date = htmlspecialchars($row['date_issued'] ?: '---');
        $name = htmlspecialchars($row['student_name']);
        $school = htmlspecialchars($row['prev_school'] ?: '---');
        $grade = htmlspecialchars($row['current_grade'] ?: '---');
        $receiver = htmlspecialchars($row['receiver'] ?: '---');
        $date_received = htmlspecialchars($row['date_received'] ?: '---');
        $remarks = htmlspecialchars($row['remarks'] ?: '---');
        
        $html .= "<tr>
            <td style='text-align:center;'>$no</td>
            <td>$date</td>
            <td class='student-name-cell'>$name</td>
            <td>$school</td>
            <td style='text-align:center;'>$grade</td>
            <td>$receiver</td>
            <td style='border-right: 1px solid #ddd;'></td>
            <td>$date_received</td>
            <td style='font-size:0.85em;'>$remarks</td>
            <td class='no-print'>
                <a href='sf10.php?id=$id' class='view-btn'>View</a>
            </td>
        </tr>";
        $no++;
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SF10 Student Record - OCNHS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/sf10.css">
</head>
<body>
    <div class="watermark-bg"></div>

    <div class="sf10-wrapper">
        <div class="sf10-header">
            <div class="header-left">
                <?php if ($search || $view_id): ?>
                    <a href="sf10.php" class="back-link no-print">&larr; Back to Student List</a>
                <?php else: ?>
                    <a href="welcome.php" class="back-link no-print">&larr; Back to Dashboard</a>
                <?php endif; ?>
                <h1>SF10 Student Permanent Record</h1>
            </div>
            
            <div class="header-right no-print">
                <div style="display:flex; flex-direction:column; gap:12px; align-items:flex-end;">
                    <!-- Search Bar -->
                    <form action="sf10.php" method="GET" class="sf10-search-bar">
                        <input type="text" name="search" placeholder="Search Name..." value="<?php echo htmlspecialchars($search); ?>" required>
                        <button type="submit" style="background:#002d72; color:white; border-radius:30px; padding:12px 25px; border:none; cursor:pointer; font-weight:700;">Search</button>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['import_success'])): ?>
            <div class="success-msg fade-in" style="background: #e7f5ff; border-left: 5px solid #002d72; padding: 15px; margin: 20px 0; border-radius: 4px; color: #002d72; font-weight: 600;">
                ✅ Successfully imported <?php echo number_format($_GET['import_success']); ?> student records from Excel!
            </div>
        <?php endif; ?>

        <?php if ($search && count($students) === 0 && !$student): ?>
            <div class="no-record-found fade-in">
                <div class="empty-state">
                    <div class="empty-icon">📂</div>
                    <h2>No record found for "<?php echo htmlspecialchars($search); ?>"</h2>
                    <p>Try searching with a different Name, or create a new SF10 entry.</p>
                    <a href="add_student.php" class="view-btn" style="background:#ffd700; color:#002d72 !important; margin-top:20px;">Create New SF10 Entry</a>
                </div>
            </div>
        <?php else: ?>
            <div class="sf10-list-section fade-in">
                <div class="section-header" style="margin-bottom:25px;">
                    <!-- Unified Title & Action Container -->
                    <div style="display:flex; flex-direction:column; gap:8px; border-top:1px solid #eee; padding-top:15px; width:100%;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <?php if ($view_id && $student): ?>
                                    <h3 style="margin:0; color:#002d72; font-weight:800;">Single Student Record View</h3>
                                <?php elseif ($search): ?>
                                    <h3 style="margin:0; color:#002d72; font-weight:800;">Search Results for "<?php echo htmlspecialchars($search); ?>"</h3>
                                <?php else: ?>
                                    <h3 style="margin:0; color:#002d72; font-weight:800;">All Student Records</h3>
                                <?php endif; ?>
                            </div>

                            <!-- Integrated Action Buttons Inside the Div -->
                            <div class="header-actions no-print" style="display:flex; gap:10px; align-items:center;">
                                <?php if ($view_id && $student): ?>
                                    <a href="edit_student.php?id=<?= $student['id'] ?>" class="action-btn-outline" style="text-decoration:none;">Edit Profile</a>
                                    <a href="generate_sf10_report.php?id=<?= $student['id'] ?>" class="action-btn-primary" style="text-decoration:none;">Generate SF10 Report</a>
                                    <button onclick="window.print()" class="action-btn-primary" style="background:#28a745; border-color:#28a745; margin-left:10px;">Print This Record</button>
                                <?php else: ?>
                                    <form action="import_excel.php" method="POST" enctype="multipart/form-data" id="importForm" style="display:none;">
                                        <input type="file" name="excel_file" id="excel_file" accept=".xlsx" onchange="document.getElementById('importForm').submit()">
                                    </form>
                                    <button type="button" onclick="document.getElementById('excel_file').click()" style="background:#002d72; color:white; border:none; padding:10px 20px; border-radius:8px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                        Import Excel
                                    </button>
                                    <a href="add_student.php" style="background:#28a745; color:white !important; text-decoration:none; padding:10px 20px; border-radius:8px; font-weight:600; display:flex; align-items:center; gap:8px;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                        Add New Student
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Subtitle (Showing records) -->
                        <?php if (!$view_id || !$student): ?>
                            <?php if ($search): ?>
                                <p style="margin:0; font-size:0.9rem; color:#666;">Found <strong><?php echo count($students); ?></strong> matching students</p>
                            <?php else: ?>
                                <p style="margin:0; font-size:0.9rem; color:#666;">Showing first 50 records</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="sf10-table-container">
                    <table class="sf10-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Date</th>
                                <th style="min-width: 200px;">Name</th>
                                <th style="min-width: 200px;">School</th>
                                <th>Grade</th>
                                <th style="min-width: 180px;">Name of Receiver</th>
                                <th style="min-width: 120px;">Signature</th>
                                <th>Date received</th>
                                <th style="min-width: 250px;">REMARKS (SF10 JH/ELEM/BC/ECCD)</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($view_id && $student) {
                                echo renderStudentRows([$student]);
                            } elseif ($search) {
                                echo renderStudentRows($students);
                            } else {
                                echo renderStudentRows($allStudents);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($view_id && $student && $logs): ?>
                    <div class="academic-history-section no-print" style="margin-top:40px;">
                        <h3>Certificate History (Logged Activity)</h3>
                        <div class="history-grid">
                            <?php foreach ($logs as $log): ?>
                                <div class="history-card">
                                    <div class="card-date"><?php echo date('M d, Y', strtotime($log['created_at'])); ?></div>
                                    <h4><?php echo htmlspecialchars($log['certificate_type']); ?></h4>
                                    <p><strong>Purpose:</strong> <?php echo htmlspecialchars($log['purpose']); ?></p>
                                    <p><strong>SY:</strong> <?php echo htmlspecialchars($log['school_year']); ?></p>
                                    <span class="status-badge">Logged Activity</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Magnetic button effects
        document.querySelectorAll('.action-btn-primary, .action-btn-outline, .view-btn').forEach(btn => {
            btn.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = (e.clientX - rect.left) - (rect.width / 2);
                const y = (e.clientY - rect.top) - (rect.height / 2);
                this.style.transform = 'translate(' + (x * 0.1) + 'px, ' + (y * 0.1) + 'px)';
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translate(0px, 0px)';
            });
        });
    </script>
</body>
</html>
