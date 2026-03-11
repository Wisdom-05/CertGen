<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCNHS Certificate Generator</title>
    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div class="watermark-bg"></div>

<div class="container">
    <div class="back-link-container">
        <a href="welcome.php" class="back-link">&larr; Back to Selection</a>
    </div>
    <h1 class="form-title">OCNHS Certificate Generator</h1>

    <div class="progress-container">
        <div class="progress-bar" id="formProgress"></div>
    </div>

    <form action="generate.php" method="POST">

        <!-- COLLAPSIBLE SCHOOL DETAILS -->


        <div id="schoolDetails" style="display: none; grid-column: 1 / -1; background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="margin-top: 0; font-size: 1rem; color: #666;">School Information (Fixed)</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group readonly-group">
                    <label>Region</label>
                    <input type="text" name="region" value="Region III" readonly style="background: #e9ecef; cursor: not-allowed;" required>
                </div>

                <div class="form-group readonly-group">
                    <label>Division</label>
                    <input type="text" name="division" value="SCHOOLS DIVISION OFFICE OF OLONGAPO CITY" readonly style="background: #e9ecef; cursor: not-allowed;" required>
                </div>

                <div class="form-group readonly-group">
                    <label>School Name</label>
                    <input type="text" name="school_name" value="OLONGAPO CITY NATIONAL HIGH SCHOOL" readonly style="background: #e9ecef; cursor: not-allowed;" required>
                </div>

                <div class="form-group readonly-group">
                    <label>School Address</label>
                    <input type="text" name="school_address"
                           value="14th Corner St. Rizal Ave., East Tapinac, Olongapo City, Zambales" readonly style="background: #e9ecef; cursor: not-allowed;">
                </div>
            </div>
        </div>

        <script>
            function toggleSchoolDetails() {
                const details = document.getElementById('schoolDetails');
                details.style.display = details.style.display === 'none' ? 'block' : 'none';
            }
        </script>

        <div class="form-group floating readonly-group">
            <input type="text" name="date_issued" id="date_issued" placeholder=" " readonly style="background: #e9ecef; cursor: not-allowed;" required>
            <label>Date Issued</label>
        </div>

        <script>
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('date_issued').value =
                new Date().toLocaleDateString('en-US', options);
        </script>

        <!-- CERTIFICATE TYPE -->
        <div class="form-group floating">
            <select name="certificate_type" id="certificate_type" required>
                <option value="" disabled selected hidden></option>
                <?php
                $selected_type = $_GET['type'] ?? '';
                $types = [
                    'CERTIFICATE OF ENROLLMENT',
                    'GOOD MORAL CHARACTER',
                    'GOOD MORAL CHARACTER (COLLEGE/SHS ADMISSION)',
                    'GOOD MORAL CHARACTER (SCHOOL TRANSFER)',
                    'CERTIFICATE OF GRADUATION',
                    'CERTIFICATE OF COMPLETION',
                    'CERTIFICATE OF RANKING',
                    'TRANSFER CERTIFICATION',
                    'CERTIFICATE OF NON-ISSUANCE OF YEARBOOK',
                    'CERTIFICATE OF NON-ISSUANCE OF ID',
                    'LOST ID CERTIFICATION',
                    'RECORD DAMAGE CERTIFICATION',
                    'SCHOLARSHIP RECOMMENDATION',
                    'SCHOOL ACCREDITATION CERTIFICATE'
                ];
                foreach ($types as $type):
                ?>
                    <option value="<?= $type ?>" <?= $selected_type === $type ? 'selected' : '' ?>>
                        <?= $type ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Certificate Type</label>
        </div>

        <h3>Student Details</h3>

        <div class="form-group floating">
            <input type="text" name="student_name" placeholder=" " required>
            <label>Student Full Name</label>
        </div>

        <div class="form-group floating" id="lrn-group">
            <input type="text" name="lrn" id="lrn_input" placeholder=" " pattern="\d{12}" minlength="12" maxlength="12" title="LRN must be exactly 12 digits" required>
            <label>LRN (if applicable)</label>
        </div>



        <div class="form-group floating" id="grade-group">
            <input type="text" name="grade_level" id="grade_level" placeholder=" " required>
            <label>Grade / Year Level</label>
        </div>

        <script>
            document.getElementById('grade_level').addEventListener('blur', function() {
                let val = this.value.trim();
                if (val && !isNaN(val)) {
                    this.value = "Grade " + val;
                }
            });
        </script>

        <div class="form-group floating" id="school-level-group" style="display: none;">
            <select name="school_level">
                <option value="JUNIOR HIGH SCHOOL">JUNIOR HIGH SCHOOL</option>
                <option value="SENIOR HIGH SCHOOL">SENIOR HIGH SCHOOL</option>
            </select>
            <label>School Level</label>
        </div>

        <div class="form-group floating" id="section-group">
            <input type="text" name="section_track" id="section_track_input" placeholder=" ">
            <label>Section / Strand / Track</label>
        </div>

        <div class="form-group floating" id="curriculum-group">
            <label style="top: 14px; left: 16px; font-size: 0.75rem; font-weight: 800; color: var(--primary-light);">Curriculum / Program</label>
            <div class="custom-dropdown" id="curriculum_dropdown">
                <div class="dropdown-trigger" id="curriculum_trigger">Select Curriculum</div>
                <div class="dropdown-menu" id="curriculum_menu">
                    <div class="dropdown-search">
                        <input type="text" id="curriculum_search" placeholder="Search curriculum...">
                    </div>
                    <div class="dropdown-options" id="curriculum_options_list">
                        <!-- HISTORICAL CURRICULUM OPTIONS (GRADUATION ONLY) -->
                        <div class="optgroup-label historical-curriculum">2-2 Plan (College Preparatory)</div>
                        <div class="option-item historical-curriculum" data-value="1969 - 1970 2-2 PLAN (COLEGE PREPARATORY)">1969 - 1970 2-2 PLAN (COLEGE PREPARATORY)</div>
                        <div class="option-item historical-curriculum" data-value="1970 - 1971 2-2 PLAN (COLEGE PREPARATORY)">1970 - 1971 2-2 PLAN (COLEGE PREPARATORY)</div>
                        <div class="option-item historical-curriculum" data-value="1971 - 1972 2-2 PLAN (COLEGE PREPARATORY)">1971 - 1972 2-2 PLAN (COLEGE PREPARATORY)</div>
                        <div class="option-item historical-curriculum" data-value="1972 - 1973 2-2 PLAN (COLEGE PREPARATORY)">1972 - 1973 2-2 PLAN (COLEGE PREPARATORY)</div>
                        <div class="option-item historical-curriculum" data-value="1973 - 1974 2-2 PLAN (COLEGE PREPARATORY)">1973 - 1974 2-2 PLAN (COLEGE PREPARATORY)</div>
                        <div class="option-item historical-curriculum" data-value="1974 - 1975 2-2 PLAN (COLEGE PREPARATORY)">1974 - 1975 2-2 PLAN (COLEGE PREPARATORY)</div>
                        <div class="option-item historical-curriculum" data-value="1975 - 1976 2-2 PLAN (COLEGE PREPARATORY)">1975 - 1976 2-2 PLAN (COLEGE PREPARATORY)</div>

                        <div class="optgroup-label historical-curriculum">Revised Secondary Education Program (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1976 - 1977 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1976 - 1977 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1977 - 1978 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1977 - 1978 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1978 - 1979 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1978 - 1979 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1979 - 1980 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1979 - 1980 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1980 - 1981 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1980 - 1981 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1981 - 1982 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1981 - 1982 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1982 - 1983 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1982 - 1983 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1983 - 1984 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1983 - 1984 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1984 - 1985 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1984 - 1985 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1985 - 1986 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1985 - 1986 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1986 - 1987 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1986 - 1987 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1987 - 1988 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1987 - 1988 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1988 - 1989 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1988 - 1989 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1989 - 1990 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1989 - 1990 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1990 - 1991 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1990 - 1991 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>
                        <div class="option-item historical-curriculum" data-value="1991 - 1992 REVISED SECONDARY EDUCATION PROGRAM (RSEP)">1991 - 1992 REVISED SECONDARY EDUCATION PROGRAM (RSEP)</div>

                        <div class="optgroup-label historical-curriculum">New Secondary Education Curriculum (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="1992 - 1993 NEW SECONDARY EDUCATION CURRICULUM (NSEC)">1992 - 1993 NEW SECONDARY EDUCATION CURRICULUM (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="1993 - 1994 NEW SECONDARY EDUCATION CURRICULUM (NSEC)">1993 - 1994 NEW SECONDARY EDUCATION CURRICULUM (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="1994 - 1995 NEW SECONDARY EDUCATION CURRICULUM (NSEC)">1994 - 1995 NEW SECONDARY EDUCATION CURRICULUM (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="1995 - 1996 NEW SECONDARY EDUCATION CURRICULUM (NSEC)">1995 - 1996 NEW SECONDARY EDUCATION CURRICULUM (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="1996 - 1997 NEW SECONDARY EDUCATION CURRICULUM (NSEC)">1996 - 1997 NEW SECONDARY EDUCATION CURRICULUM (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="1997 - 1998 NEW SECONDARY EDUCATION CURRICULUM (NSEC)">1997 - 1998 NEW SECONDARY EDUCATION CURRICULUM (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="1998 - 1999 NEW SECONDARY EDUCATION CURRICULUM (NSEC)/SPECIAL SCIENCE CURRICULUM-ENGINEERING & SCIENCE EDUCATION PROJECT (SSC-ESEP)">1998 - 1999 NEW SECONDARY EDUCATION CURRICULUM (NSEC)/SPECIAL SCIENCE CURRICULUM-ENGINEERING & SCIENCE EDUCATION PROJECT (SSC-ESEP)</div>
                        <div class="option-item historical-curriculum" data-value="1999 - 2000 NEW SECONDARY EDUCATION CURRICULUM (NSEC)">1999 - 2000 NEW SECONDARY EDUCATION CURRICULUM (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="2000 - 2001 NEW SECONDARY EDUCATION CURRICULUM (NSEC)">2000 - 2001 NEW SECONDARY EDUCATION CURRICULUM (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="2001 - 2002 NEW SECONDARY EDUCATION CURRICULUM (NSEC)">2001 - 2002 NEW SECONDARY EDUCATION CURRICULUM (NSEC)</div>
                        <div class="option-item historical-curriculum" data-value="2002 - 2003 NEW SECONDARY EDUCATION CURRICULUM (NSEC)/SPECIAL SCIENCE CLASS-ENGINEERING &SCIENCE EDUCATION PROJECT (SSC-ESEP)">2002 - 2003 NEW SECONDARY EDUCATION CURRICULUM (NSEC)/SPECIAL SCIENCE CLASS-ENGINEERING &SCIENCE EDUCATION PROJECT (SSC-ESEP)</div>

                        <div class="optgroup-label historical-curriculum">Basic Education Curriculum (BEC)</div>
                        <div class="option-item historical-curriculum" data-value="2003 - 2004 BASIC EDUCATION CURRICULUM (BEC)/MAKABAYAN">2003 - 2004 BASIC EDUCATION CURRICULUM (BEC)/MAKABAYAN</div>
                        <div class="option-item historical-curriculum" data-value="2004 - 2005 BASIC EDUCATION CURRICULUM (BEC)">2004 - 2005 BASIC EDUCATION CURRICULUM (BEC)</div>
                        <div class="option-item historical-curriculum" data-value="2005 - 2006 BASIC EDUCATION CURRICULUM (BEC)">2005 - 2006 BASIC EDUCATION CURRICULUM (BEC)</div>
                        <div class="option-item historical-curriculum" data-value="2006 – 2007 BASIC EDUCATION CURRICULUM (BEC)/DLP">2006 – 2007 BASIC EDUCATION CURRICULUM (BEC)/DLP</div>
                        <div class="option-item historical-curriculum" data-value="2007 - 2008 BASIC EDUCATION CURRICULUM (BEC)/SPECIAL SCIENCE CURRICULUM">2007 - 2008 BASIC EDUCATION CURRICULUM (BEC)/SPECIAL SCIENCE CURRICULUM</div>
                        <div class="option-item historical-curriculum" data-value="2008 - 2009 BASIC EDUCATION CURRICULUM (BEC)">2008 - 2009 BASIC EDUCATION CURRICULUM (BEC)</div>
                        <div class="option-item historical-curriculum" data-value="2009 - 2010 BASIC EDUCATION CURRICULUM (BEC)">2009 - 2010 BASIC EDUCATION CURRICULUM (BEC)</div>
                        <div class="option-item historical-curriculum" data-value="2010 - 2011 BASIC EDUCATION CURRICULUM (BEC)">2010 - 2011 BASIC EDUCATION CURRICULUM (BEC)</div>
                        <div class="option-item historical-curriculum" data-value="2011 - 2012 BASIC EDUCATION CURRICULUM (BEC)">2011 - 2012 BASIC EDUCATION CURRICULUM (BEC)</div>
                        <div class="option-item historical-curriculum" data-value="2012 - 2013 BASIC EDUCATION CURRICULUM (BEC)">2012 - 2013 BASIC EDUCATION CURRICULUM (BEC)</div>

                        <div class="optgroup-label historical-curriculum">Modern Programs (2013 onwards)</div>
                        <div class="option-item historical-curriculum" data-value="2013 - 2014 SECONDARY EDUCATION CURRICULUM (SEC)">2013 - 2014 SECONDARY EDUCATION CURRICULUM (SEC)</div>
                        <div class="option-item historical-curriculum" data-value="2014 - 2015 ENHANCED BASIC EDUCATION CURRICULUM (EBEC)">2014 - 2015 ENHANCED BASIC EDUCATION CURRICULUM (EBEC)</div>
                        <div class="option-item historical-curriculum" data-value="2015 - 2016 K-12 BASIC EDUCATION PROGRAM (BEP)/SPECIAL SCIENCE CURRICULUM-SCIENCE&TECHNOLOGY ENGINEERING PROGRAM">2015 - 2016 K-12 BASIC EDUCATION PROGRAM (BEP)/SPECIAL SCIENCE CURRICULUM-SCIENCE&TECHNOLOGY ENGINEERING PROGRAM</div>
                        <div class="option-item historical-curriculum" data-value="2016 - 2017 BASIC EDUCATION PROGRAM (BEP)/ARABIC LANGUAGE & ISLAMIC VALUES EDUCATION (ALIVE)">2016 - 2017 BASIC EDUCATION PROGRAM (BEP)/ARABIC LANGUAGE & ISLAMIC VALUES EDUCATION (ALIVE)</div>
                        <div class="option-item historical-curriculum" data-value="2017 – 2018 BASIC EDUCATION PROGRAM (BEP)/ MADARASAH EDUCATION PROGRAM/SCIENCE TECHNOLOGY&ENGINEERING PROGRAM (STEP)/SPECIAL PROGRAM IN TECHNICAL-VOCATIONAL EDUCATION (SPTVE)">2017 – 2018 BASIC EDUCATION PROGRAM (BEP)/ MADARASAH EDUCATION PROGRAM/SCIENCE TECHNOLOGY&ENGINEERING PROGRAM (STEP)/SPECIAL PROGRAM IN TECHNICAL-VOCATIONAL EDUCATION (SPTVE)</div>

                        <!-- K-12 PROGRAMS -->
                        <div class="optgroup-label k12-curriculum">Recent K-12 Programs</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 SCIENCE TECHNOLOGY AND ENGINEERING (STE)">K TO 12 SCIENCE TECHNOLOGY AND ENGINEERING (STE)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 SPECIAL PROGRAM IN THE ARTS (SPA)">K TO 12 SPECIAL PROGRAM IN THE ARTS (SPA)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 SPECIAL PROGRAM IN FOREIGN LANGUAGES (SPFL)">K TO 12 SPECIAL PROGRAM IN FOREIGN LANGUAGES (SPFL)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 SPECIAL PROGRAM IN SPORTS (SPS)">K TO 12 SPECIAL PROGRAM IN SPORTS (SPS)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 SPECIAL PROGRAM IN TECHNICAL -VOCATIONAL EDUCATION (SPTVE)">K TO 12 SPECIAL PROGRAM IN TECHNICAL -VOCATIONAL EDUCATION (SPTVE)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 SPECIAL PROGRAM IN JOURNALISM (SPJ)">K TO 12 SPECIAL PROGRAM IN JOURNALISM (SPJ)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 BASIC EDUCATION PROGRAM (BEP)">K TO 12 BASIC EDUCATION PROGRAM (BEP)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 OPEN HIGH SCHOOL PROGRAM (OHSP)">K TO 12 OPEN HIGH SCHOOL PROGRAM (OHSP)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 SCIENCE, TECHNOLOGY, ENGINEERING AND MATHEMATICS (STEM)">K TO 12 SCIENCE, TECHNOLOGY, ENGINEERING AND MATHEMATICS (STEM)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 ARTS AND DESIGN (ADT)">K TO 12 ARTS AND DESIGN (ADT)</div>
                        <div class="option-item k12-curriculum" data-value="K TO 12 TECHNICAL AND VOCATIONAL LIVELIHOOD (TVL)">K TO 12 TECHNICAL AND VOCATIONAL LIVELIHOOD (TVL)</div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="curriculum" id="curriculum_input_hidden" required>
        </div>

        <div class="form-group floating">
            <input type="text" name="school_year" placeholder=" " required>
            <label>School Year</label>
        </div>

        <div class="form-group floating">
            <input type="text" name="purpose" id="purpose_input" list="purpose-suggestions" placeholder=" " required>
            <label>Purpose of Certification</label>
            <datalist id="purpose-suggestions"></datalist>
        </div>

        <script>
            // Custom Dropdown Logic
            const customDropdown = document.getElementById('curriculum_dropdown');
            const dropdownTrigger = document.getElementById('curriculum_trigger');
            const dropdownMenu = document.getElementById('curriculum_menu');
            const dropdownSearch = document.getElementById('curriculum_search');
            const hiddenInput = document.getElementById('curriculum_input_hidden');
            const optionItems = document.querySelectorAll('.option-item');
            const optgroupLabels = document.querySelectorAll('.optgroup-label');

            dropdownTrigger.addEventListener('click', () => {
                dropdownMenu.classList.toggle('show');
                customDropdown.closest('.form-group').classList.toggle('dropdown-active');
                if (dropdownMenu.classList.contains('show')) {
                    dropdownSearch.focus();
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!customDropdown.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    customDropdown.closest('.form-group').classList.remove('dropdown-active');
                }
            });

            // Search logic
            dropdownSearch.addEventListener('input', () => {
                const filter = dropdownSearch.value.toUpperCase();
                let visibleCount = 0;

                // First hide all labels
                optgroupLabels.forEach(label => label.style.display = 'none');

                optionItems.forEach(item => {
                    const text = item.textContent || item.innerText;
                    const isVisibleCertType = item.style.display !== 'none'; // Respect cert type logic
                    
                    if (text.toUpperCase().indexOf(filter) > -1 && isVisibleCertType) {
                        item.classList.remove('filtered-out');
                        item.classList.add('visible-search');
                        // Show parent label if child matches
                        let label = item.previousElementSibling;
                        while(label && !label.classList.contains('optgroup-label')) {
                            label = label.previousElementSibling;
                        }
                        if(label) label.style.display = '';
                    } else {
                        item.classList.add('filtered-out');
                        item.classList.remove('visible-search');
                    }
                });
            });

            // Selection logic
            optionItems.forEach(item => {
                item.addEventListener('click', () => {
                    const val = item.getAttribute('data-value');
                    hiddenInput.value = val;
                    dropdownTrigger.textContent = val;
                    dropdownTrigger.classList.add('selected');
                    dropdownMenu.classList.remove('show');
                    customDropdown.closest('.form-group').classList.remove('dropdown-active');
                });
            });

            // Master Fix: Restore custom dropdown value on page load (history back/bfcache)
            function restoreDropdownUI() {
                if (hiddenInput.value) {
                    dropdownTrigger.textContent = hiddenInput.value;
                    dropdownTrigger.classList.add('selected');
                    console.log("Restored Curriculum UI: ", hiddenInput.value);
                }
            }
            
            // Run immediately and on relevant events
            restoreDropdownUI();
            window.addEventListener('pageshow', restoreDropdownUI);

            const purposeMap = {
                'CERTIFICATE OF ENROLLMENT': [
                    'Pantawid Pamilyang Pilipino Program (4Ps)',
                    'Scholarship',
                    'Travel',
                    'Legal Purposes'
                ],
                'GOOD MORAL CHARACTER (COLLEGE/SHS ADMISSION)': [
                    'College Admission',
                    'Senior High School Enrollment'
                ],
                'GOOD MORAL CHARACTER (SCHOOL TRANSFER)': [
                    'School Transfer'
                ],
                'CERTIFICATE OF GRADUATION': [
                    'TESDA',
                    'Employment',
                    'College Admission'
                ],
                'CERTIFICATE OF COMPLETION': [
                    'Employment',
                    'Senior High School Enrollment'
                ],
                'CERTIFICATE OF RANKING': [
                    'Scholarship Application',
                    'College Admission',
                    'Recognition'
                ],
                'TRANSFER CERTIFICATION': [
                    'School Transfer'
                ],
                'CERTIFICATE OF NON-ISSUANCE OF YEARBOOK': [
                    'Employment Abroad',
                    'Immigration',
                    'Legal Purposes'
                ],
                'CERTIFICATE OF NON-ISSUANCE OF ID': [
                    'Verification',
                    'Legal Purposes',
                    'Immigration'
                ],
                'LOST ID CERTIFICATION': [
                    'Replacement',
                    'Verification',
                    'Legal Purposes'
                ],
                'RECORD DAMAGE CERTIFICATION': [
                    'Record Verification',
                    'Replacement',
                    'Legal Purposes'
                ],
                'SCHOLARSHIP RECOMMENDATION': [
                    'Scholarship Application',
                    'Financial Aid',
                    'Educational Grant'
                ],
                'SCHOOL ACCREDITATION CERTIFICATE': [
                    'Verification',
                    'Recognition',
                    'Legal Purposes'
                ]
            };

            const certSelect = document.getElementById('certificate_type');
            const purposeInput = document.getElementById('purpose_input');
            const purposeDatalist = document.getElementById('purpose-suggestions');

            function updatePurpose() {
                const list = purposeMap[certSelect.value] || ['Reference'];
                
                purposeDatalist.innerHTML = '';
                list.forEach(p => {
                    const o = document.createElement('option');
                    o.value = p;
                    purposeDatalist.appendChild(o);
                });

                // Only set default if field is currently empty
                if (list.length > 0 && !purposeInput.value) {
                    purposeInput.value = list[0];
                }
            }

            const schoolLevelGroup = document.getElementById('school-level-group');
            const lrnGroup = document.getElementById('lrn-group');
            const lrnInput = document.getElementById('lrn_input');
            const gradeGroup = document.getElementById('grade-group');
            const gradeInput = document.getElementById('grade_level');
            const sectionGroup = document.getElementById('section-group');
            const curriculumGroup = document.getElementById('curriculum-group');
            // const curriculumInput = document.getElementById('curriculum_input'); // Replaced by custom

            function updateVisibility() {
                updatePurpose();
                
                // Curriculum visibility logic for Custom Dropdown
                const historicalItems = document.querySelectorAll('.historical-curriculum');
                const showHistorical = certSelect.value === 'CERTIFICATE OF GRADUATION' || 
                                     certSelect.value === 'GOOD MORAL CHARACTER (COLLEGE/SHS ADMISSION)';
                
                historicalItems.forEach(item => {
                    item.style.display = showHistorical ? '' : 'none';
                });

                // Only reset if it's a dynamic change, not on first load
                // (Handled by restoreDropdownUI and state management)

                // GMC Normal Request Specific Visibility
                if (certSelect.value === 'GOOD MORAL CHARACTER') {
                    // Hide almost everything for simplicity
                    schoolLevelGroup.style.display = 'none';
                    lrnGroup.style.display = 'none';
                    lrnInput.required = false;
                    gradeGroup.style.display = 'none';
                    gradeInput.required = false;
                    sectionGroup.style.display = 'none';
                    
                    // Keep Curriculum visible as requested
                    curriculumGroup.style.display = 'block';
                    curriculumInput.required = true;
                } else {
                    // Standard Visibility for other certificates
                    if (certSelect.value.startsWith('GOOD MORAL CHARACTER')) {
                        schoolLevelGroup.style.display = 'block';
                    } else {
                        schoolLevelGroup.style.display = 'none';
                    }

                    lrnGroup.style.display = 'block';
                    lrnInput.required = true;
                    gradeGroup.style.display = 'block';
                    gradeInput.required = true;
                    sectionGroup.style.display = 'block';
                    curriculumGroup.style.display = 'block';
                    curriculumInput.required = false;
                }
            }

            certSelect.addEventListener('change', updateVisibility);
            updateVisibility();
        </script>

        <h3>Signatory & Footer</h3>

        <div class="form-group floating">
            <input type="text" name="principal_name" value="SANDY T. CABARLE, EdD" placeholder=" " required>
            <label>Principal Name</label>
        </div>

        <div class="form-group floating">
            <input type="text" name="principal_title" value="PRINCIPAL IV" placeholder=" " required>
            <label>Principal Title</label>
        </div>

        <div class="form-group floating">
            <input type="text" name="rcc_code" placeholder=" ">
            <label>RCC / Office Code</label>
        </div>

        <div class="form-group floating readonly-group">
            <input type="text" name="contact_number"
                   value="(047) 223-3744 / (047) 224-8452" placeholder=" " readonly style="background: #e9ecef; cursor: not-allowed;">
            <label>Contact Number</label>
        </div>

        <div class="form-group floating readonly-group">
            <input type="text" name="email"
                   value="olongapocitynationalhighschool@gmail.com" placeholder=" " readonly style="background: #e9ecef; cursor: not-allowed;">
            <label>Email Address</label>
        </div>

        <div class="form-group floating readonly-group">
            <input type="text" name="website" value="https://ocnhs.edu.ph" placeholder=" " readonly style="background: #e9ecef; cursor: not-allowed;">
            <label>Official Website</label>
        </div>

        <button type="submit" class="submit-btn" name="generate">Generate Certificate</button>
    </form>
</div>

<script>
    // Form Progress Tracker
    const form = document.querySelector('form');
    const progressBar = document.getElementById('formProgress');
    const inputs = form.querySelectorAll('input[required], select[required]');

    function updateProgress() {
        let completed = 0;
        let total = 0;

        inputs.forEach(input => {
            // Check if the input is actually visible
            let current = input;
            let isVisible = true;
            while(current && current !== form) {
                if (current.style.display === 'none') {
                    isVisible = false;
                    break;
                }
                current = current.parentElement;
            }

            if (isVisible) {
                total++;
                if (input.value && input.value !== "") {
                    completed++;
                }
            }
        });

        const progress = (completed / total) * 100;
        progressBar.style.width = progress + '%';
        
        // Dynamic Glowing effect based on completion
        if (progress === 100) {
            progressBar.style.boxShadow = '0 0 20px rgba(0, 210, 255, 0.8)';
        } else {
            progressBar.style.boxShadow = '0 0 10px rgba(0, 210, 255, 0.5)';
        }
    }

    form.addEventListener('input', updateProgress);
    window.addEventListener('load', updateProgress);
    
    // Observer for dynamic visibility changes
    const observer = new MutationObserver(updateProgress);
    observer.observe(form, { attributes: true, subtree: true, attributeFilter: ['style'] });

    // Magnetic Submit Button - High precision fix
    const submitBtn = document.querySelector('.submit-btn');
    submitBtn.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        // Use clientX/Y to get mouse pos relative to the viewport
        // and subtract the button's position for local button coordinates
        const x = (e.clientX - rect.left) - (rect.width / 2);
        const y = (e.clientY - rect.top) - (rect.height / 2);
        
        // 0.2 strength means the button follows the mouse 
        // 20% of the distance within its own hit box
        this.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
    });

    submitBtn.addEventListener('mouseleave', function() {
        this.style.transform = 'translate(0px, 0px)';
    });
</script>

</body>
</html>
