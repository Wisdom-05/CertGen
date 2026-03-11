<?php
if (!isset($_POST['generate'])) {
    header("Location: index.php");
    exit();
}

$region = $_POST['region'];
$division = $_POST['division'];
$school_name = $_POST['school_name'];
$school_address = $_POST['school_address'];

$date = date('d F, Y', strtotime($_POST['date_issued']));
$student_name = strtoupper($_POST['student_name']);
$lrn = $_POST['lrn'];
$grade_raw = trim($_POST['grade_level']);
$grade = is_numeric($grade_raw) ? "GRADE " . $grade_raw : strtoupper($grade_raw);
$school_level = isset($_POST['school_level']) ? strtoupper($_POST['school_level']) : "";
$section = strtoupper($_POST['section_track']);
$curriculum = strtoupper($_POST['curriculum']);
$sy = $_POST['school_year'];
$purpose = strtoupper($_POST['purpose']);
$cert_type = strtoupper($_POST['certificate_type']);

$principal = $_POST['principal_name'];
$principal_title = strtoupper($_POST['principal_title']);
$rcc = $_POST['rcc_code'];

$contact = $_POST['contact_number'];
$email = $_POST['email'];
$website = $_POST['website'];

// Logic to determine Body Text based on Certificate Type
$pronoun = "he/she"; // Simplified default
$student_info = "<strong>$student_name</strong> with (<strong>LRN $lrn</strong>)";

switch ($cert_type) {
    case 'CERTIFICATE OF ENROLLMENT':
        $title_text = "CERTIFICATION";
        $body_content = "<p>This is to certify that $student_info is currently enrolled in <strong>$grade - $section</strong> under <strong>$curriculum</strong> this School Year <strong>$sy</strong>.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-mentioned student for <strong>$purpose</strong> purpose only.</p>";
        break;
 
    case 'CERTIFICATE OF GRADUATION':
        $title_text = "CERTIFICATE OF<br>GRADUATION";
        $body_content = "<p>This is to certify that $student_info has satisfactorily completed the requirements for graduation from the Secondary Graduate Curriculum at this school during the School Year <strong>$sy</strong>.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purpose only.</p>";
        break;

    case 'CERTIFICATE OF COMPLETION':
        $title_text = "CERTIFICATE OF<br>COMPLETION";
        $body_content = "<p>This is to certify that $student_info has satisfactorily completed the requirements for Junior High School at this school during the School Year <strong>$sy</strong>.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purpose only.</p>";
        break;

    case 'GOOD MORAL CHARACTER':
        $title_text = "CERTIFICATE OF<br>GOOD MORAL CHARACTER";
        $body_content = "<p>This is to certify that <strong>$student_name</strong> was a bona fide student of this school under <strong>$curriculum</strong> during the school year <strong>$sy</strong>.</p>";
        $body_content .= "<p>The student has no derogatory records filed in this office as of this date.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong>.</p>";
        break;

    case 'GOOD MORAL CHARACTER (SCHOOL TRANSFER)':
    case 'GOOD MORAL CHARACTER (COLLEGE/SHS ADMISSION)':
        $title_text = "CERTIFICATE OF<br>GOOD MORAL CHARACTER";
        
        if ($cert_type === 'GOOD MORAL CHARACTER (SCHOOL TRANSFER)') {
            $body_content = "<p>This is to certify that <strong>$student_name</strong> with <strong> (LRN $lrn)</strong> was a bona fide <strong>$grade &ndash; $section</strong> student of this school under <strong>$curriculum</strong> during the school year <strong>$sy</strong>.</p>";
            $body_content .= "<p>The student has no derogatory records filed in this office as of this date.</p>";
            $body_content .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purposes.</p>";
            break;
        } else {
            
            $level_wording = ($school_level === 'SENIOR HIGH SCHOOL') ? 'SENIOR HIGH SCHOOL' : 'JUNIOR HIGH SCHOOL';
            
            $body_content = "<p>This is to certify that <strong>$student_name (LRN $lrn)</strong> is a bona fide <strong>$grade &ndash; $section</strong> student of this school under <strong>$curriculum</strong> . He/She has not violated any act or omission punishable by the school rules and regulation, and he/she is in the list of those completing <strong>$level_wording</strong> this school year <strong>$sy</strong>.</p>";
            $body_content .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purpose only.</p>";
            break;
        }

    case 'CERTIFICATE OF RANKING':
        $title_text = "CERTIFICATE OF<br>RANKING";
        $body_content = "<p>This is to certify that <strong>$student_name</strong> (<strong>LRN $lrn</strong>) is officially ranked among the students of <strong>$grade - $section</strong> under <strong>$curriculum</strong> during the School Year <strong>$sy</strong>.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-named student for <strong>$purpose</strong> purposes.</p>";
        break;

    case 'CERTIFICATE OF NON-ISSUANCE OF YEARBOOK':
        $title_text = "CERTIFICATE OF<br>NON-ISSUANCE OF YEARBOOK";
        $body_content = "<p>This is to certify that <strong>$student_name</strong> graduated from this school under <strong>$curriculum</strong> during the School Year <strong>$sy</strong>.</p>";
        $body_content .= "<p>This certifies further that there was no Annual Yearbook issued during that year.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-named person for <strong>$purpose</strong> purposes.</p>";
        break;

    case 'CERTIFICATE OF NON-ISSUANCE OF ID':
        $title_text = "CERTIFICATE OF<br>NON-ISSUANCE OF SCHOOL ID";
        $body_content = "<p>This is to certify that <strong>$student_name</strong> was a bona fide <strong>$grade - $section</strong> student of this school during the School Year <strong>$sy</strong>.</p>";
        $body_content .= "<p>This certifies further that <strong>NO SCHOOL IDENTIFICATION CARD</strong> was issued to the above-named student during that school year.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-named person for <strong>$purpose</strong> purposes.</p>";
        break;

    case 'LOST ID CERTIFICATION':
        $title_text = "CERTIFICATION OF<br>LOST SCHOOL ID";
        $body_content = "<p>This is to certify that <strong>$student_name</strong> (<strong>LRN $lrn</strong>) is a bona fide <strong>$grade - $section</strong> student of this school under <strong>$curriculum</strong> for the School Year <strong>$sy</strong>.</p>";
        $body_content .= "<p>This certifies further that the student has reported the loss of their School Identification Card issued by this institution.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-named student for <strong>$purpose</strong> purposes.</p>";
        break;

    case 'RECORD DAMAGE CERTIFICATION':
        $title_text = "CERTIFICATION OF<br>DAMAGED SCHOOL RECORDS";
        $body_content = "<p>This is to certify that certain school records of <strong>$student_name</strong> (<strong>LRN $lrn</strong>), a <strong>$grade - $section</strong> student of this school, have been damaged or rendered partially illegible.</p>";
        $body_content .= "<p>This certification is issued to acknowledge the condition of the records and to facilitate any necessary verification or replacement procedures.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-named student for <strong>$purpose</strong> purposes.</p>";
        break;

    case 'SCHOLARSHIP RECOMMENDATION':
        $title_text = "SCHOLARSHIP<br>RECOMMENDATION";
        $body_content = "<p>This is to recommend <strong>$student_name</strong> (<strong>LRN $lrn</strong>), a <strong>$grade - $section</strong> student of this school under <strong>$curriculum</strong> during the School Year <strong>$sy</strong>.</p>";
        $body_content .= "<p>Based on academic performance and conduct, the student is hereby recommended for scholarship consideration.</p>";
        $body_content .= "<p>This recommendation is issued to support the student's application for <strong>$purpose</strong>.</p>";
        break;

    case 'TRANSFER CERTIFICATION':
        $title_text = "TRANSFER<br>CERTIFICATION";
        $body_content = "<p>This is to certify that $student_info was a bona fide student of this school under <strong>$curriculum</strong> during the School Year <strong>$sy</strong>.</p>";
        $body_content .= "<p>This certifies further that the student is cleared of all property and financial responsibilities from this institution and is hereby granted honorable dismissal to transfer to another school.</p>";
        $body_content .= "<p>This certification is issued upon the request of the above-named student for <strong>$purpose</strong> purposes.</p>";
        break;

    case 'SCHOOL ACCREDITATION CERTIFICATE':
        $title_text = "SCHOOL<br>ACCREDITATION CERTIFICATE";
        $body_content = "<p>This is to certify that <strong>$school_name</strong>, located at <strong>$school_address</strong>, is a duly recognized and accredited public secondary school under the <strong>Department of Education - $division</strong>.</p>";
        $body_content .= "<p>The school operates under the <strong>K TO 12 BASIC EDUCATION PROGRAM</strong> and is authorized to issue official certifications and academic credentials.</p>";
        $body_content .= "<p>This certification is issued for <strong>$purpose</strong> purposes.</p>";
        break;

    default:
        $title_text = "CERTIFICATE";
        $body_content = "<p>This is to certify that <strong>$student_name</strong>.</p>";
        break;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo str_replace('<br>', ' ', $title_text); ?></title>

<link rel="stylesheet" href="../style/certificate.css">
</head>
<body>
    <div class="watermark-bg"></div>

<div class="no-print">
    <a href="welcome.php" class="floating-home-btn" title="Go Home">
        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" fill="white"/></svg>
    </a>
    <button class="action-btn print-btn" id="printBtn" onclick="printCert()">
        Print Certificate
    </button>
    <button class="action-btn edit-btn" onclick="history.back()">
        Edit Data
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
<script>
    let hasLogged = false;
    
    // Confetti Celebration Function
    function celebrate() {
        const duration = 3 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);
            // OCNHS Blue and Gold
            confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }, colors: ['#002D72', '#FFD700'] });
            confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }, colors: ['#0056b3', '#ffffff'] });
        }, 250);
    }

    function printCert() {
        window.print();
    }

    window.addEventListener('afterprint', function() {
        if (hasLogged) return;
        if (confirm('Was the certificate successfully printed or saved as PDF?\n\nClick OK to save to history, or Cancel if not.')) {
            hasLogged = true;
            celebrate(); // Trigger the reward!
            
            fetch('save_log.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    certificate_type: <?= json_encode($cert_type) ?>,
                    student_name: <?= json_encode($student_name) ?>,
                    lrn: <?= json_encode($lrn) ?>,
                    grade_level: <?= json_encode($grade) ?>,
                    section_track: <?= json_encode($section) ?>,
                    curriculum: <?= json_encode($curriculum) ?>,
                    school_year: <?= json_encode($sy) ?>,
                    purpose: <?= json_encode($purpose) ?>,
                    date_issued: <?= json_encode($date) ?>,
                    principal_name: <?= json_encode($principal) ?>
                })
            });
        }
    });

    // Magnetic Button Effect - High Precision Fix
    const magneticBtns = document.querySelectorAll('.floating-home-btn, .action-btn');
    magneticBtns.forEach(btn => {
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

<div class="certificate-container">
    
    <div class="certificate-content">
    <!-- Header -->
    <div class="header">
        <img src="../resources/deped_seal_new.png" class="header-logo" alt="DepEd Seal">
        
        <p class="republic-text old-english">Republic of the Philippines</p>
        <p class="dept-text old-english">Department of Education</p>
        <p class="region-text"><?php echo $region; ?></p>
        <p class="division-text"><?php echo $division; ?></p>
        <p class="school-name"><?php echo $school_name; ?></p>
        <p class="school-address"><?php echo $school_address; ?></p>
        
        <div class="header-line"></div>
        <div class="date-line"><?php echo $date; ?></div>
    </div>

    <!-- Title -->
    <div class="title-section">
        <span class="cert-label"><?php echo $title_text; ?></span>
    </div>

    <!-- Salutation -->
    <div class="salutation">
        TO WHOM IT MAY CONCERN:
    </div>

    <!-- Body -->
    <?php 
        // Only count alphanumeric characters (letters and numbers) as per user request
        $pure_text = preg_replace('/[^a-zA-Z0-9]/', '', strip_tags($body_content));
        $content_length = strlen($pure_text);
        $content_class = "content";
    ?>
    <div class="<?= $content_class ?>">
        <?php echo $body_content; ?>
    </div>

    <!-- Signatory -->
    <div class="signatory-section">
        <div class="dry-seal-box">
            <?php if(!empty($rcc)): ?>
                <div class="rcc-code"><?php echo $rcc; ?></div>
            <?php endif; ?>
            dry seal here
        </div>

        <div class="principal-box">
            <div class="principal-name"><?php echo $principal; ?></div>
            <div class="principal-title"><?php echo $principal_title; ?></div>
        </div>
    </div>
    </div>

    <!-- Footer -->
    <div class="footer-section">
        <div class="footer-logos-left">
            <!-- New Combined Footer Logo from User -->
            <img src="../resources/DepED MATATAG.png" alt="DepEd MATATAG and Bagong Pilipinas" style="height: 80px; width: auto;">
            
            <!-- School Logo -->
            <div class="footer-logo"><img src="../resources/OCNHS LOGO.png" alt="School Logo"></div>
        </div>

        <div class="footer-info-right">
            <div class="footer-text">
                <div><strong>Address:</strong> <?php echo $school_address; ?></div>
                <div><strong>Contact No.:</strong> <?php echo $contact; ?></div>
                <div><strong>Email Address:</strong> <?php echo $email; ?></div>
                <div><strong>Official Website:</strong> <?php echo $website; ?></div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
