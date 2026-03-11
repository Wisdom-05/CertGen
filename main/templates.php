<?php
/**
 * templates.php - Centralized configuration for certificate types, body text, and purposes.
 */

/**
 * Returns an array of available certificate types.
 */
function get_certificate_types()
{
    return [
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
}

/**
 * Returns the purpose suggestions for each certificate type.
 */
function get_purpose_map()
{
    return [
        'CERTIFICATE OF ENROLLMENT' => [
            'Pantawid Pamilyang Pilipino Program (4Ps)',
            'Scholarship',
            'Travel',
            'Legal Purposes'
        ],
        'GOOD MORAL CHARACTER (COLLEGE/SHS ADMISSION)' => [
            'College Admission',
            'Senior High School Enrollment'
        ],
        'GOOD MORAL CHARACTER (SCHOOL TRANSFER)' => [
            'School Transfer'
        ],
        'CERTIFICATE OF GRADUATION' => [
            'TESDA',
            'Employment',
            'College Admission'
        ],
        'CERTIFICATE OF COMPLETION' => [
            'Employment',
            'Senior High School Enrollment'
        ],
        'CERTIFICATE OF RANKING' => [
            'Scholarship Application',
            'College Admission',
            'Recognition'
        ],
        'TRANSFER CERTIFICATION' => [
            'School Transfer'
        ],
        'CERTIFICATE OF NON-ISSUANCE OF YEARBOOK' => [
            'Employment Abroad',
            'Immigration',
            'Legal Purposes'
        ],
        'CERTIFICATE OF NON-ISSUANCE OF ID' => [
            'Verification',
            'Legal Purposes',
            'Immigration'
        ],
        'LOST ID CERTIFICATION' => [
            'Replacement',
            'Verification',
            'Legal Purposes'
        ],
        'RECORD DAMAGE CERTIFICATION' => [
            'Record Verification',
            'Replacement',
            'Legal Purposes'
        ],
        'SCHOLARSHIP RECOMMENDATION' => [
            'Scholarship Application',
            'Financial Aid',
            'Educational Grant'
        ],
        'SCHOOL ACCREDITATION CERTIFICATE' => [
            'Verification',
            'Recognition',
            'Legal Purposes'
        ]
    ];
}

/**
 * Generates the title and body text based on certificate type.
 */
function get_certificate_content($type, $data)
{
    // Extract data for easier usage in templates
    $sn = "<strong>" . ($data['student_name'] ?? '') . "</strong>";
    $lrn = $data['lrn'] ?? '';
    $student_info = "$sn with (<strong>LRN $lrn</strong>)";
    $grade = $data['grade_level'] ?? '';
    $section = $data['section_track'] ?? '';
    $curriculum = $data['curriculum'] ?? '';
    $sy = $data['school_year'] ?? '';
    $purpose = $data['purpose'] ?? '';
    $school_name = $data['school_name'] ?? '';
    $school_address = $data['school_address'] ?? '';
    $division = $data['division'] ?? '';
    $school_level = $data['school_level'] ?? '';

    $content = [
        'title' => 'CERTIFICATE',
        'body' => ''
    ];

    switch ($type) {
        case 'CERTIFICATE OF ENROLLMENT':
            $content['title'] = "CERTIFICATION";
            $content['body'] = "<p>This is to certify that $student_info is currently enrolled in <strong>$grade - $section</strong> under <strong>$curriculum</strong> this School Year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-mentioned student for <strong>$purpose</strong> purpose only.</p>";
            break;

        case 'CERTIFICATE OF GRADUATION':
            $content['title'] = "CERTIFICATE OF<br>GRADUATION";
            $content['body'] = "<p>This is to certify that $student_info has satisfactorily completed the requirements for graduation from the Secondary Graduate Curriculum at this school during the School Year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purpose only.</p>";
            break;

        case 'CERTIFICATE OF COMPLETION':
            $content['title'] = "CERTIFICATE OF<br>COMPLETION";
            $content['body'] = "<p>This is to certify that $student_info has satisfactorily completed the requirements for Junior High School at this school during the School Year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purpose only.</p>";
            break;

        case 'GOOD MORAL CHARACTER':
            $content['title'] = "CERTIFICATE OF<br>GOOD MORAL CHARACTER";
            $content['body'] = "<p>This is to certify that $sn was a bona fide student of this school under <strong>$curriculum</strong> during the school year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>The student has no derogatory records filed in this office as of this date.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong>.</p>";
            break;

        case 'GOOD MORAL CHARACTER (SCHOOL TRANSFER)':
            $content['title'] = "CERTIFICATE OF<br>GOOD MORAL CHARACTER";
            $content['body'] = "<p>This is to certify that $sn with <strong> (LRN $lrn)</strong> was a bona fide <strong>$grade &ndash; $section</strong> student of this school under <strong>$curriculum</strong> during the school year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>The student has no derogatory records filed in this office as of this date.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purposes.</p>";
            break;

        case 'GOOD MORAL CHARACTER (COLLEGE/SHS ADMISSION)':
            $content['title'] = "CERTIFICATE OF<br>GOOD MORAL CHARACTER";
            $level_wording = ($school_level === 'SENIOR HIGH SCHOOL') ? 'SENIOR HIGH SCHOOL' : 'JUNIOR HIGH SCHOOL';
            $content['body'] = "<p>This is to certify that <strong>{$data['student_name']} (LRN $lrn)</strong> is a bona fide <strong>$grade &ndash; $section</strong> student of this school under <strong>$curriculum</strong> . He/She has not violated any act or omission punishable by the school rules and regulation, and he/she is in the list of those completing <strong>$level_wording</strong> this school year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named student as a requirement for <strong>$purpose</strong> purpose only.</p>";
            break;

        case 'CERTIFICATE OF RANKING':
            $content['title'] = "CERTIFICATE OF<br>RANKING";
            $content['body'] = "<p>This is to certify that $sn (<strong>LRN $lrn</strong>) is officially ranked among the students of <strong>$grade - $section</strong> under <strong>$curriculum</strong> during the School Year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named student for <strong>$purpose</strong> purposes.</p>";
            break;

        case 'CERTIFICATE OF NON-ISSUANCE OF YEARBOOK':
            $content['title'] = "CERTIFICATE OF<br>NON-ISSUANCE OF YEARBOOK";
            $content['body'] = "<p>This is to certify that $sn graduated from this school under <strong>$curriculum</strong> during the School Year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>This certifies further that there was no Annual Yearbook issued during that year.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named person for <strong>$purpose</strong> purposes.</p>";
            break;

        case 'CERTIFICATE OF NON-ISSUANCE OF ID':
            $content['title'] = "CERTIFICATE OF<br>NON-ISSUANCE OF SCHOOL ID";
            $content['body'] = "<p>This is to certify that $sn was a bona fide <strong>$grade - $section</strong> student of this school during the School Year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>This certifies further that <strong>NO SCHOOL IDENTIFICATION CARD</strong> was issued to the above-named student during that school year.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named person for <strong>$purpose</strong> purposes.</p>";
            break;

        case 'LOST ID CERTIFICATION':
            $content['title'] = "CERTIFICATION OF<br>LOST SCHOOL ID";
            $content['body'] = "<p>This is to certify that $sn (<strong>LRN $lrn</strong>) is a bona fide <strong>$grade - $section</strong> student of this school under <strong>$curriculum</strong> for the School Year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>This certifies further that the student has reported the loss of their School Identification Card issued by this institution.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named student for <strong>$purpose</strong> purposes.</p>";
            break;

        case 'RECORD DAMAGE CERTIFICATION':
            $content['title'] = "CERTIFICATION OF<br>DAMAGED SCHOOL RECORDS";
            $content['body'] = "<p>This is to certify that certain school records of $sn (<strong>LRN $lrn</strong>), a <strong>$grade - $section</strong> student of this school, have been damaged or rendered partially illegible.</p>";
            $content['body'] .= "<p>This certification is issued to acknowledge the condition of the records and to facilitate any necessary verification or replacement procedures.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named student for <strong>$purpose</strong> purposes.</p>";
            break;

        case 'SCHOLARSHIP RECOMMENDATION':
            $content['title'] = "SCHOLARSHIP<br>RECOMMENDATION";
            $content['body'] = "<p>This is to recommend $sn (<strong>LRN $lrn</strong>), a <strong>$grade - $section</strong> student of this school under <strong>$curriculum</strong> during the School Year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>Based on academic performance and conduct, the student is hereby recommended for scholarship consideration.</p>";
            $content['body'] .= "<p>This recommendation is issued to support the student's application for <strong>$purpose</strong>.</p>";
            break;

        case 'TRANSFER CERTIFICATION':
            $content['title'] = "TRANSFER<br>CERTIFICATION";
            $content['body'] = "<p>This is to certify that $student_info was a bona fide student of this school under <strong>$curriculum</strong> during the School Year <strong>$sy</strong>.</p>";
            $content['body'] .= "<p>This certifies further that the student is cleared of all property and financial responsibilities from this institution and is hereby granted honorable dismissal to transfer to another school.</p>";
            $content['body'] .= "<p>This certification is issued upon the request of the above-named student for <strong>$purpose</strong> purposes.</p>";
            break;

        case 'SCHOOL ACCREDITATION CERTIFICATE':
            $content['title'] = "SCHOOL<br>ACCREDITATION CERTIFICATE";
            $content['body'] = "<p>This is to certify that <strong>$school_name</strong>, located at <strong>$school_address</strong>, is a duly recognized and accredited public secondary school under the <strong>Department of Education - $division</strong>.</p>";
            $content['body'] .= "<p>The school operates under the <strong>K TO 12 BASIC EDUCATION PROGRAM</strong> and is authorized to issue official certifications and academic credentials.</p>";
            $content['body'] .= "<p>This certification is issued for <strong>$purpose</strong> purposes.</p>";
            break;

        default:
            $content['body'] = "<p>This is to certify that $sn.</p>";
            break;
    }

    return $content;
}
