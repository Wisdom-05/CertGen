require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['certificate_type']) || empty($data['student_name'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO certificate_logs (certificate_type, student_name, lrn, grade_level, section_track, curriculum, school_year, purpose, date_issued, principal_name, generated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssi",
    $data['certificate_type'],
    $data['student_name'],
    $data['lrn'],
    $data['grade_level'],
    $data['section_track'],
    $data['curriculum'],
    $data['school_year'],
    $data['purpose'],
    $data['date_issued'],
    $data['principal_name'],
    $_SESSION['user_id']
);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(['status' => 'success']);
?>
