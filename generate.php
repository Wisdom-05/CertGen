<?php
require_once 'includes/auth.php';
require_login();

if (!isset($_POST['generate'])) {
    header("Location: index.php");
    exit();
}

require_once 'config/constants.php';
require_once 'includes/functions.php';
require_once 'includes/certificate_logic.php';

// Sanitize and prepare data
$data = [
    'region' => $_POST['region'] ?? SCHOOL_INFO['region'],
    'division' => $_POST['division'] ?? SCHOOL_INFO['division'],
    'school_name' => $_POST['school_name'] ?? SCHOOL_INFO['school_name'],
    'school_address' => $_POST['school_address'] ?? SCHOOL_INFO['school_address'],
    'date_issued' => $_POST['date_issued'] ?? date('F j, Y'),
    'student_name' => strtoupper($_POST['student_name']),
    'lrn' => $_POST['lrn'],
    'grade_level' => trim($_POST['grade_level']),
    'school_level' => $_POST['school_level'] ?? '',
    'section_track' => strtoupper($_POST['section_track']),
    'curriculum' => strtoupper($_POST['curriculum']),
    'school_year' => $_POST['school_year'],
    'purpose' => strtoupper($_POST['purpose']),
    'certificate_type' => strtoupper($_POST['certificate_type']),
    'principal_name' => $_POST['principal_name'],
    'principal_title' => strtoupper($_POST['principal_title']),
    'rcc_code' => $_POST['rcc_code'] ?? '',
];

// Process Grade text
$data['grade'] = is_numeric($data['grade_level']) ? "GRADE " . $data['grade_level'] : strtoupper($data['grade_level']);

// Get formatted date
$formatted_date = date('d F, Y', strtotime($data['date_issued']));

// Fetch Certificate Content
$cert_content = getCertificateContent($data['certificate_type'], $data);
$title_text = $cert_content['title'];
$body_content = $cert_content['body'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo str_replace('<br>', ' ', $title_text); ?></title>
    <link rel="stylesheet" href="assets/css/certificate.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
</head>
<body>
    <div class="watermark-bg"></div>

    <div class="no-print actions-toolbar">
        <a href="index.php" class="floating-home-btn" title="Go Home">
            <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" fill="white"/></svg>
        </a>
        <button class="action-btn print-btn" id="printBtn" onclick="window.print()">
            <i class="fas fa-print"></i> Print Certificate
        </button>
        <button class="action-btn edit-btn" onclick="history.back()">
            <i class="fas fa-edit"></i> Edit Data
        </button>
    </div>

    <div class="certificate-container">
        <!-- Header -->
            <div class="header">
                <img src="assets/img/deped_seal_new.png" class="header-logo" alt="DepEd Seal">
                <p class="republic-text old-english">Republic of the Philippines</p>
                <p class="dept-text old-english">Department of Education</p>
                <p class="region-text"><?php echo h($data['region']); ?></p>
                <p class="division-text"><?php echo h($data['division']); ?></p>
                <p class="school-name"><?php echo h($data['school_name']); ?></p>
                <p class="school-address"><?php echo h($data['school_address']); ?></p>
                
                <div class="header-line"></div>
                <div class="date-line"><?php echo $formatted_date; ?></div>
            </div>

            <!-- Title -->
            <div class="title-section">
                <span class="cert-label"><?php echo $title_text; ?></span>
            </div>

            <div class="salutation">TO WHOM IT MAY CONCERN:</div>

            <!-- Body -->
            <div class="content">
                <?php echo $body_content; ?>
            </div>

            <!-- Signatory -->
            <div class="signatory-section">
                <div class="dry-seal-box">
                    <?php if (!empty($data['rcc_code'])): ?>
                        <div class="rcc-code"><?php echo h($data['rcc_code']); ?></div>
                    <?php
endif; ?>
                    dry seal here
                </div>

                <div class="principal-box">
                    <div class="principal-name"><?php echo h($data['principal_name']); ?></div>
                    <div class="principal-title"><?php echo h($data['principal_title']); ?></div>
                </div>
            </div>
        
        <!-- Footer -->
        <div class="footer-section">
            <div class="footer-logos-left">
                <img src="assets/img/DepED MATATAG.png" alt="DepEd MATATAG" style="height: 70px; width: auto;">
                <div class="footer-logo"><img src="assets/img/OCNHS LOGO.png" alt="School Logo"></div>
            </div>

            <div class="footer-info-right">
                <div class="footer-text">
                    <div><strong>Address:</strong> <?php echo h($data['school_address']); ?></div>
                    <div><strong>Contact No.:</strong> (047) 223-3744</div>
                    <div><strong>Email Address:</strong> olongapocitynationalhighschool@gmail.com</div>
                    <div><strong>Official Website:</strong> https://ocnhs.edu.ph</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let hasLogged = false;
        
        function celebrate() {
            const duration = 3 * 1000;
            const animationEnd = Date.now() + duration;
            const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

            function randomInRange(min, max) { return Math.random() * (max - min) + min; }

            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();
                if (timeLeft <= 0) return clearInterval(interval);

                const particleCount = 50 * (timeLeft / duration);
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }, colors: ['#002D72', '#FFD700'] });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }, colors: ['#0056b3', '#ffffff'] });
            }, 250);
        }

        window.addEventListener('afterprint', function() {
            if (hasLogged) return;
            if (confirm('Was the certificate successfully printed or saved?\nClick OK to save to history.')) {
                hasLogged = true;
                celebrate();
                
                fetch('api/save_log.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(<?php echo json_encode(array_merge($data, ['date_issued' => $formatted_date])); ?>)
                })
                .then(response => response.json())
                .then(result => {
                    console.log('Log saved:', result);
                    if(result.status === 'error') alert('Error saving history: ' + result.message);
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('System error saving history.');
                });
            }
        });

        // Magnetic effects
        document.querySelectorAll('.floating-home-btn, .action-btn').forEach(btn => {
            btn.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = (e.clientX - rect.left) - (rect.width / 2);
                const y = (e.clientY - rect.top) - (rect.height / 2);
                this.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translate(0px, 0px)';
            });
        });
    </script>
</body>
</html>
