<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
require_login();

// Handle CSV Download
if (isset($_GET['download']) && $_GET['download'] === 'csv') {
    $where = "";
    $params = [];
    $types = "";

    $is_filtered = !empty($_GET['date_from']) || !empty($_GET['date_to']) || !empty($_GET['cert_type']);
    
    if ($is_filtered) {
        if (!empty($_GET['date_from'])) {
            $where .= " AND cl.created_at >= ?";
            $params[] = $_GET['date_from'] . " 00:00:00";
            $types .= "s";
        }
        if (!empty($_GET['date_to'])) {
            $where .= " AND cl.created_at <= ?";
            $params[] = $_GET['date_to'] . " 23:59:59";
            $types .= "s";
        }
        if (!empty($_GET['cert_type'])) {
            $where .= " AND cl.certificate_type = ?";
            $params[] = $_GET['cert_type'];
            $types .= "s";
        }
    } else {
        $where .= " AND YEARWEEK(cl.created_at, 1) = YEARWEEK(CURDATE(), 1)";
    }

    $sql = "SELECT cl.*, u.full_name as generated_by_name FROM certificate_logs cl LEFT JOIN users u ON cl.generated_by = u.id WHERE 1=1 $where ORDER BY cl.created_at DESC";
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all records for CSV
    $all_records = [];
    $summary = [];
    $total_count = 0;
    while ($row = $result->fetch_assoc()) {
        $all_records[] = $row;
        $type = $row['certificate_type'];
        if (!isset($summary[$type])) {
            $summary[$type] = 0;
        }
        $summary[$type]++;
        $total_count++;
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="issuance_report_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    // BOM for Excel UTF-8
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
    
    // 1. Report Header
    fputcsv($output, ['CERTIFICATE ISSUANCE REPORT']);
    fputcsv($output, ['Generated on:', date('F j, Y h:i A')]);
    if ($is_filtered) {
        $filters = [];
        if (!empty($_GET['date_from'])) $filters[] = "From: " . $_GET['date_from'];
        if (!empty($_GET['date_to'])) $filters[] = "To: " . $_GET['date_to'];
        if (!empty($_GET['cert_type'])) $filters[] = "Type: " . $_GET['cert_type'];
        fputcsv($output, ['Filters Applied:', implode(' | ', $filters)]);
    }
    fputcsv($output, []); // Empty line

    // 2. Summary Table
    fputcsv($output, ['SUMMARY STATS']);
    fputcsv($output, ['Certificate Type', 'Count', 'Percentage (%)']);
    foreach ($summary as $type => $count) {
        $percentage = ($total_count > 0) ? round(($count / $total_count) * 100, 2) : 0;
        fputcsv($output, [$type, $count, $percentage . '%']);
    }
    fputcsv($output, ['TOTAL ISSUED', $total_count, '100%']);
    fputcsv($output, []); // Spacer
    fputcsv($output, []); // Spacer

    // 3. Raw Data Header
    $csv_header = ['#', 'Certificate Type', 'Student Name', 'LRN', 'Grade Level', 'Section/Track', 'Curriculum', 'School Year', 'Purpose', 'Date Issued', 'Principal', 'Generated At'];
    if (is_super_admin()) {
        $csv_header[] = 'Issued By';
    }
    fputcsv($output, $csv_header);

    $counter = 1;
    foreach ($all_records as $row) {
        $export_row = [
            $counter++,
            $row['certificate_type'],
            $row['student_name'],
            $row['lrn'],
            $row['grade_level'],
            $row['section_track'],
            $row['curriculum'],
            $row['school_year'],
            $row['purpose'],
            $row['date_issued'],
            $row['principal_name'],
            $row['created_at']
        ];
        if (is_super_admin()) {
            $export_row[] = $row['generated_by_name'] ?: 'Unknown';
        }
        fputcsv($output, $export_row);
    }
    
    fclose($output);
    $stmt->close();
    exit();
}

// Build query for display
$is_filtered = !empty($_GET['date_from']) || !empty($_GET['date_to']) || !empty($_GET['cert_type']);
$where = "";
$params = [];
$types = "";

if (!empty($_GET['date_from'])) {
    $where .= " AND cl.created_at >= ?";
    $params[] = $_GET['date_from'] . " 00:00:00";
    $types .= "s";
}
if (!empty($_GET['date_to'])) {
    $where .= " AND cl.created_at <= ?";
    $params[] = $_GET['date_to'] . " 23:59:59";
    $types .= "s";
}
if (!empty($_GET['cert_type'])) {
    $where .= " AND cl.certificate_type = ?";
    $params[] = $_GET['cert_type'];
    $types .= "s";
}

// Optimization: If NOT filtered, only show records from the current calendar week
if (!$is_filtered) {
    $where .= " AND YEARWEEK(cl.created_at, 1) = YEARWEEK(CURDATE(), 1)";
}

$sql = "SELECT cl.*, u.full_name as generated_by_name FROM certificate_logs cl LEFT JOIN users u ON cl.generated_by = u.id WHERE 1=1 $where ORDER BY cl.created_at DESC";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get distinct certificate types for filter dropdown
$type_result = $conn->query("SELECT DISTINCT certificate_type FROM certificate_logs ORDER BY certificate_type ASC");

// Get chart data: most requested certificate types
$chart_result = $conn->query("SELECT certificate_type, COUNT(*) as total FROM certificate_logs GROUP BY certificate_type ORDER BY total DESC LIMIT 8");
$chart_labels = [];
$chart_data = [];
while ($crow = $chart_result->fetch_assoc()) {
    // Shorten long labels for chart display
    $label = $crow['certificate_type'];
    if (strlen($label) > 30) $label = substr($label, 0, 28) . '…';
    $chart_labels[] = $label;
    $chart_data[] = (int)$crow['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate History - OCNHS CertGen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/history.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="watermark-bg"></div>
<!-- Skeleton Loading Overlay -->
<div id="skeletonOverlay" class="skeleton-overlay">
    <div class="skeleton-item skeleton-header"></div>
    <div class="skeleton-item skeleton-chart"></div>
    <div class="skeleton-item skeleton-table"></div>
</div>

<script>
    // Hide skeleton when page is fully loaded
    window.addEventListener('load', () => {
        const overlay = document.getElementById('skeletonOverlay');
        overlay.classList.add('hidden');
    });
</script>

<div class="history-wrapper">
    <!-- Header Bar -->
    <div class="history-header">
        <div class="history-header-inner">
            <a href="index.php" class="back-link">&larr; Back to Selection</a>
            <h1 class="history-title">Certificate Generator History</h1>
            <p class="history-subtitle">View and manage all previously generated certificates</p>
        </div>
    </div>

    <!-- Stats Chart -->
    <?php if (!empty($chart_data)): ?>
    <div class="chart-section">
        <div class="chart-card">
            <h3 class="chart-title">Most Requested Certificates</h3>
            <div class="chart-container">
                <canvas id="certChart"></canvas>
            </div>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('certChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [{
                    label: 'Certificates Generated',
                    data: <?= json_encode($chart_data) ?>,
                    backgroundColor: [
                        'rgba(0, 45, 114, 0.85)',
                        'rgba(0, 86, 179, 0.80)',
                        'rgba(0, 119, 204, 0.75)',
                        'rgba(0, 153, 230, 0.70)',
                        'rgba(0, 184, 148, 0.75)',
                        'rgba(255, 159, 64, 0.75)',
                        'rgba(108, 92, 231, 0.75)',
                        'rgba(214, 48, 49, 0.70)'
                    ],
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 28
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#002D72',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { family: 'Outfit', size: 13 },
                        bodyFont: { family: 'Outfit', size: 12 }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { family: 'Outfit', size: 12 } },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    y: {
                        ticks: { font: { family: 'Outfit', size: 11, weight: 600 }, color: '#2d3436' },
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
    <?php endif; ?>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="history.php" class="filter-form" id="filterForm">
            <div class="filter-group">
                <label for="date_from">From Date</label>
                <input type="date" name="date_from" id="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label for="date_to">To Date</label>
                <input type="date" name="date_to" id="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label for="cert_type">Certificate Type</label>
                <select name="cert_type" id="cert_type">
                    <option value="">All Types</option>
                    <?php while ($type_row = $type_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($type_row['certificate_type']) ?>"
                            <?= (isset($_GET['cert_type']) && $_GET['cert_type'] === $type_row['certificate_type']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type_row['certificate_type']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    Filter
                </button>
                <a href="history.php" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <!-- Results Summary & Download -->
    <div class="results-bar">
        <div class="results-count">
            <span class="count-number"><?= $result->num_rows ?></span> record<?= $result->num_rows !== 1 ? 's' : '' ?> found
            <?php if (!$is_filtered): ?>
                <span class="history-notice">(Showing this week only)</span>
            <?php endif; ?>
        </div>
        <?php if ($result->num_rows > 0): ?>
        <a href="?download=csv&<?= http_build_query(array_filter($_GET, function($k) { return $k !== 'download'; }, ARRAY_FILTER_USE_KEY)) ?>"
           class="btn btn-download">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download CSV
        </a>
        <?php endif; ?>
    </div>

    <!-- Table Section -->
    <?php if ($result->num_rows > 0): ?>
    <div class="table-container">
        <table class="history-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Certificate Type</th>
                    <th>Student Name</th>
                    <th>LRN</th>
                    <th>Grade</th>
                    <th>School Year</th>
                    <th>Purpose</th>
                    <th>Principal</th>
                    <?php if (is_super_admin()): ?>
                        <th>Issued By</th>
                    <?php endif; ?>
                    <th>Generated At</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="cell-num"><?= $counter++ ?></td>
                    <td><span class="badge"><?= htmlspecialchars($row['certificate_type']) ?></span></td>
                    <td class="cell-name"><?= htmlspecialchars($row['student_name']) ?></td>
                    <td class="cell-mono"><?= htmlspecialchars($row['lrn']) ?></td>
                    <td><?= htmlspecialchars($row['grade_level']) ?></td>
                    <td><?= htmlspecialchars($row['school_year']) ?></td>
                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                    <td><?php echo htmlspecialchars($row['principal_name']); ?></td>
                    <?php if (is_super_admin()): ?>
                        <td class="cell-generator">
                            <span class="admin-badge"><?php echo htmlspecialchars($row['generated_by_name'] ?: 'System'); ?></span>
                        </td>
                    <?php endif; ?>
                    <td class="cell-timestamp"><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">📋</div>
        <h3>No Certificates Found</h3>
        <p>No certificate records match your filters. Try adjusting the date range or generate a new certificate.</p>
        <a href="index.php" class="btn btn-primary">Generate a Certificate</a>
    </div>
    <?php endif; ?>
</div>

<div class="footer-simple">
    &copy; <?= date('Y') ?> Olongapo City National High School. All rights reserved.
</div>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
