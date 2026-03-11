<?php
/**
 * welcome.php
 * 
 * This file serves two purposes:
 * 1. Shows a "Selection" landing page (when accessed directly).
 * 2. Displays a certificate (when POST data is received from a form).
 */

if (isset($_POST['generate'])) {

    $region = $_POST['region'];
    $division = $_POST['division'];
    $school_name = $_POST['school_name'];
    $school_address = $_POST['school_address'];
    $date = date('d F, Y', strtotime($_POST['date_issued']));
    $cert_type = strtoupper($_POST['certificate_type']);

    $student_name = strtoupper($_POST['student_name']);
    $lrn = trim($_POST['lrn']);
    $grade_raw = trim($_POST['grade_level']);    $grade = is_numeric($grade_raw) ? "GRADE " . $grade_raw : strtoupper($grade_raw);
    $school_level = isset($_POST['school_level']) ? strtoupper($_POST['school_level']) : "";
    $section = strtoupper($_POST['section_track']);
    $curriculum = strtoupper($_POST['curriculum']);
    $sy = $_POST['school_year'];
    $purpose = strtoupper($_POST['purpose']);

    $principal = strtoupper($_POST['principal_name']);
    $principal_title = strtoupper($_POST['principal_title']);
    $rcc = $_POST['rcc_code'];

    $contact = $_POST['contact_number'];
    $email = $_POST['email'];
    $website = $_POST['website'];

    $lrn_text = !empty($lrn) ? " with LRN# (<strong>$lrn</strong>)" : "";

    switch ($cert_type) {

        case 'GOOD MORAL CHARACTER':
            $title_text = "CERTIFICATE OF<br>GOOD MORAL CHARACTER";
            $body_content = "<p>This is to certify that <strong>$student_name</strong> was a bona fide student of this school under <strong>$curriculum</strong> during the school year <strong>$sy</strong>.</p>";
            $body_content .= "<p>The student has no derogatory records filed in this office as of this date.</p>";
            $body_content .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong>.</p>";
            $certificate_body = $body_content;
            break;

        case 'GOOD MORAL CHARACTER (SCHOOL TRANSFER)':
        case 'GOOD MORAL CHARACTER (COLLEGE ADMISSION)':
        case 'GOOD MORAL CHARACTER (SHS ENROLLMENT)':
            $title_text = "CERTIFICATE OF<br>GOOD MORAL CHARACTER";

            if ($cert_type === 'GOOD MORAL CHARACTER (SCHOOL TRANSFER)') {
                $body_content = "<p>This is to certify that <strong>$student_name</strong> with <strong>LRN# ($lrn)</strong> was a bona fide <strong>$grade &ndash; $section</strong> student of this school under <strong>$curriculum</strong> during the school year <strong>$sy</strong>.</p>";
                $body_content .= "<p>The student has no derogatory records filed in this office as of this date.</p>";
                $body_content .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purposes.</p>";
                $certificate_body = $body_content;
                break;
            }
            elseif ($cert_type === 'GOOD MORAL CHARACTER (SHS ENROLLMENT)') {
                $body_content = "<p>This is to certify that <strong>$student_name (LRN# $lrn)</strong> is a bona fide <strong>$grade &ndash; $section</strong> student of this school under <strong>$curriculum</strong> . He/She has not violated any act or omission punishable by the school rules and regulation, and he/she is in the list of those completing <strong>JUNIOR HIGH SCHOOL</strong> this school year <strong>$sy</strong>.</p>";
                $body_content .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purpose only.</p>";
                $certificate_body = $body_content;
                break;
            }
            else {
                // College Admission
                $body_content = "<p>This is to certify that <strong>$student_name (LRN $lrn)</strong> is a bona fide <strong>$grade &ndash; $section</strong> student of this school under <strong>$curriculum</strong> . He/She has not violated any act or omission punishable by the school rules and regulation, and he/she is in the list of those completing <strong>SENIOR HIGH SCHOOL</strong> this school year <strong>$sy</strong>.</p>";
                $body_content .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purpose only.</p>";
                $certificate_body = $body_content;
                break;
            }

        case 'CERTIFICATE OF ENROLLMENT':
            $title_text = "CERTIFICATION";
            $certificate_body = "
            <p>This is to certify that <strong>$student_name</strong>$lrn_text is currently enrolled as 
            <strong>$grade" . (!empty($section) ? " - $section" : "") . "</strong> under 
            <strong>$curriculum</strong> during the school year <strong>$sy</strong>.</p>
            <p>This certification is issued upon the request of the above-named student as a requirement for 
            <strong>$purpose</strong>.</p>";
            break;

        case 'CERTIFICATE OF GRADUATION':
            $certificate_body = "
            <p>This is to certify that <strong>$student_name</strong>$lrn_text graduated under 
            <strong>$curriculum</strong> during the school year <strong>$sy</strong>.</p>
            <p>This certification is issued upon the request of the above-named person as a requirement for 
            <strong>$purpose</strong>.</p>";
            break;

        case 'CERTIFICATE OF COMPLETION':
            $certificate_body = "
            <p>This is to certify that <strong>$student_name</strong>$lrn_text was a 
            <strong>$grade COMPLETER</strong> of this school under <strong>$curriculum</strong> during the 
            school year <strong>$sy</strong>.</p>
            <p>This certification is issued upon the request of the above-named person as a requirement for 
            <strong>$purpose</strong>.</p>";
            break;

        case 'CERTIFICATE OF NON-ISSUANCE OF YEARBOOK':
            $certificate_body = "
            <p>This is to certify that <strong>$student_name</strong> graduated in this school under 
            <strong>$curriculum</strong> during the school year <strong>$sy</strong>.</p>
            <p>This certifies further that there was no Annual Yearbook issued during that year.</p>
            <p>This certification is issued upon the request of the above-named person for 
            <strong>$purpose</strong>.</p>";
            break;

        case 'CERTIFICATE OF NON-ISSUANCE OF ID':
            $certificate_body = "
            <p>This is to certify that <strong>$student_name</strong> was not issued a school identification 
            card during the school year <strong>$sy</strong>.</p>
            <p>This certification is issued upon the request of the above-named person for 
            <strong>$purpose</strong>.</p>";
            break;

        case 'CERTIFICATE OF RANKING':
            $certificate_body = "
            <p>This is to certify that <strong>$student_name</strong> is officially ranked among the students 
            of <strong>$grade</strong> under <strong>$curriculum</strong> during the school year 
            <strong>$sy</strong>.</p>
            <p>This certification is issued for <strong>$purpose</strong>.</p>";
            break;

        case 'SCHOLARSHIP RECOMMENDATION':
            $certificate_body = "
            <p>This is to recommend <strong>$student_name</strong>, a student of <strong>$grade</strong> under 
            <strong>$curriculum</strong>, for scholarship consideration.</p>
            <p>This certification is issued for <strong>$purpose</strong>.</p>";
            break;

        default:
            $certificate_body = "<p>This is to certify that <strong>$student_name</strong>.</p>";
    }
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title><?php echo $cert_type; ?></title>
    <link rel="stylesheet" href="../assets/css/certificate.css">
    </head>
    <body>
    <div class="watermark-bg"></div>

    <div class="no-print">
        <button class="action-btn print-btn" onclick="window.print()">
            Print Certificate
        </button>
        <button class="action-btn edit-btn" onclick="window.history.back()">
            Edit Data
        </button>
    </div>

    <div class="certificate-container">
        <div class="certificate-content">
            <div class="header">
                <img src="../assets/img/deped_seal_new.png" alt="DepEd Seal">
                <div>Republic of the Philippines</div>
                <div>Department of Education</div>
                <div><?php echo $region; ?></div>
                <div><?php echo $division; ?></div>
                <div class="school"><?php echo $school_name; ?></div>
                <div><?php echo $school_address; ?></div>
            </div>

            <div class="date"><?php echo $date; ?></div>

            <div class="title">
                <?php if (isset($title_text)): ?>
                    <span><?php echo $title_text; ?></span>
                <?php
    else: ?>
                    <span><?php echo explode(' OF ', $cert_type)[0]; ?> OF</span>
                    <span><?php echo explode(' OF ', $cert_type)[1] ?? ''; ?></span>
                <?php
    endif; ?>
            </div>

            <div class="salutation">TO WHOM IT MAY CONCERN:</div>
            <div class="content"><?php echo $certificate_body; ?></div>

            <div class="signatory">
                <div class="dry-seal-box">
                    <?php if (!empty($rcc))
        echo "<div>$rcc</div>"; ?>
                    <div>dry seal here</div>
                </div>
                <div class="principal-box">
                    <div style="font-weight:bold; text-transform:uppercase; font-size:12pt;"><?php echo $principal; ?></div>
                    <div style="font-size:11pt;"><?php echo $principal_title; ?></div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-logos-left">
                <img src="../assets/img/DepED MATATAG.png" alt="DepEd MATATAG" style="height: 100px; width: auto;">
                <div class="footer-logo"><img src="../assets/img/OCNHS LOGO.png" alt="School Logo"></div>
            </div>
            <div class="footer-text">
                <div><strong>Address:</strong> <?php echo $school_address; ?></div>
                <div><strong>Contact No.:</strong> <?php echo $contact; ?></div>
                <div><strong>Email Address:</strong> <?php echo $email; ?></div>
                <div><strong>Official Website:</strong> <?php echo $website; ?></div>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
}
else {
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome - OCNHS Certificate Generator</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body class="landing-page">
        <div class="watermark-bg"></div>

        <div class="top-nav">
            <a href="../registry.php" class="nav-btn form137-btn">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                <span>Go to Form 137</span>
            </a>
        </div>

        <div class="hero-section">
            <img src="../assets/img/OCNHS LOGO.png" class="hero-logo" alt="OCNHS LOGO">
            <div class="hero-content">
                <h1>OCNHS Certificate Generator</h1>
                <p>Select the type of certificate you wish to generate to proceed with the form.</p>
                <a href="history.php" class="hero-history-btn">View Generator History</a>
            </div>
        </div>

        <div class="selection-container">
            <div class="card-grid">
                <a href="../request_form.php?type=CERTIFICATE OF ENROLLMENT" class="card fade-in">
                    <h3>Enrollment</h3>
                    <p>Certificate of Enrollment for current students.</p>
                </a>
                <div class="card card-interactive fade-in" onclick="this.classList.toggle('active')">
                    <h3>Good Moral Character</h3>
                    <p>Click to select specific purpose</p>
                    <div class="card-options">
                        <a href="../request_form.php?type=GOOD MORAL CHARACTER">Good Moral Character</a>
                        <a href="../request_form.php?type=GOOD MORAL CHARACTER (COLLEGE/SHS ADMISSION)">College/SHS Admission</a>
                        <a href="../request_form.php?type=GOOD MORAL CHARACTER (SCHOOL TRANSFER)">School Transfer</a>
                    </div>
                </div>
                <a href="../request_form.php?type=CERTIFICATE OF GRADUATION" class="card fade-in">
                    <h3>Graduation</h3>
                    <p>Diploma/Certificate of Graduation proof.</p>
                </a>
                <a href="../request_form.php?type=CERTIFICATE OF COMPLETION" class="card fade-in">
                    <h3>Completion</h3>
                    <p>Standard completion proof for JHS/Grade 10.</p>
                </a>
                <a href="../request_form.php?type=CERTIFICATE OF RANKING" class="card fade-in">
                    <h3>Ranking</h3>
                    <p>Official Academic Ranking certification.</p>
                </a>
                <a href="../request_form.php?type=TRANSFER CERTIFICATION" class="card fade-in">
                    <h3>Transfer</h3>
                    <p>Official Certification for School Transfer.</p>
                </a>
                <a href="../request_form.php?type=SCHOLARSHIP RECOMMENDATION" class="card fade-in">
                    <h3>Scholarship</h3>
                    <p>Recommendation for financial aid programs.</p>
                </a>
                <a href="../request_form.php?type=LOST ID CERTIFICATION" class="card fade-in">
                    <h3>Lost ID</h3>
                    <p>Requirement for lost school IDs.</p>
                </a>
                <a href="../request_form.php?type=CERTIFICATE OF NON-ISSUANCE OF YEARBOOK" class="card fade-in">
                    <h3>No Yearbook</h3>
                    <p>Verifies no yearbook was issued for that year.</p>
                </a>
                <a href="../request_form.php?type=CERTIFICATE OF NON-ISSUANCE OF ID" class="card fade-in">
                    <h3>No ID</h3>
                    <p>Verifies no school ID was issued.</p>
                </a>
                <a href="../request_form.php?type=RECORD DAMAGE CERTIFICATION" class="card fade-in">
                    <h3>Damaged Record</h3>
                    <p>Certification for damaged school records.</p>
                </a>
                <a href="../request_form.php?type=SCHOOL ACCREDITATION CERTIFICATE" class="card fade-in">
                    <h3>Accreditation</h3>
                    <p>Official School Accreditation status.</p>
                </a>
            </div>
        </div>

        <div class="footer-simple">
            &copy; <?php echo date('Y'); ?> Olongapo City National High School. All rights reserved.
        </div>

    </body>
    </html>
    <?php
}
?>
