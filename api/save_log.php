<?php
/**
 * save_log.php - AJAX endpoint
 * Logs a certificate to the database only when the user prints/saves.
 */
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

require_once dirname(__DIR__) . '/config/database.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['certificate_type']) || empty($data['student_name'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO certificate_logs (certificate_type, student_name, lrn, grade_level, section_track, curriculum, school_year, purpose, date_issued, principal_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss",
    $data['certificate_type'],
    $data['student_name'],
    $data['lrn'],
    $data['grade_level'],
    $data['section_track'],
    $data['curriculum'],
    $data['school_year'],
    $data['purpose'],
    $data['date_issued'],
    $data['principal_name']
);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(['status' => 'success']);
?>
