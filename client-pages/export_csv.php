<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");
$user_data = check_loginins($con);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_course'])) {
    $course_id = $con->real_escape_string($_POST['export_course']);
} else {
    die("No course selected.");
}

$instructor_id = $con->real_escape_string($user_data['user_name']);
$course_sql = "SELECT course_id FROM courses
               WHERE course_id = '$course_id' AND instructor_id = '$instructor_id'
               LIMIT 1";
$course_result = $con->query($course_sql);
if (!$course_result || $course_result->num_rows === 0) {
    http_response_code(403);
    die("You are not authorized to export this course.");
}

$course_id = $course_result->fetch_assoc()['course_id'];

// Get unique students for that course
$student_sql = "SELECT DISTINCT student_id FROM attendance WHERE course_id = '$course_id'";
$student_result = $con->query($student_sql);
$students = [];
while ($row = $student_result->fetch_assoc()) {
    $students[] = $row['student_id'];
}

if (empty($students)) {
    die("No students found.");
}

// Get student names from users table
$placeholders = implode(',', array_fill(0, count($students), '?'));
$stmt = $con->prepare("SELECT user_name, name FROM users WHERE user_name IN ($placeholders)");
$stmt->bind_param(str_repeat('s', count($students)), ...$students);
$stmt->execute();
$result = $stmt->get_result();

$student_names = [];
while ($row = $result->fetch_assoc()) {
    $student_names[$row['user_name']] = $row['name'];
}
$stmt->close();

// Get all unique dates
$date_sql = "SELECT DISTINCT date FROM attendance WHERE course_id = '$course_id' ORDER BY date ASC";
$date_result = $con->query($date_sql);
$dates = [];
while ($row = $date_result->fetch_assoc()) {
    $dates[] = $row['date'];
}
if (empty($dates)) {
    die("No attendance dates.");
}

// Build attendance matrix
$attendance_data = [];
foreach ($students as $sid) {
    $attendance_data[$sid] = array_fill_keys($dates, 'A'); // Default Absent
}

$att_sql = "SELECT student_id, date, status FROM attendance WHERE course_id = '$course_id'";
$att_result = $con->query($att_sql);
while ($row = $att_result->fetch_assoc()) {
    $sid = $row['student_id'];
    $date = $row['date'];
    $status = $row['status'] === 'Present' ? 'P' : 'A';
    $attendance_data[$sid][$date] = $status;
}

// ----------
// OUTPUT CSV
// ----------

// Set headers for CSV download
header('Content-Type: text/csv');
$filename = $course_id . "_Attendance_" . date("d-m-Y") . ".csv";
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Pragma: no-cache');
header('Expires: 0');

// (Optional but recommended) Add UTF-8 BOM so Excel opens non-English characters properly
echo "\xEF\xBB\xBF";

// Create output
$output = fopen('php://output', 'w');

// First row: Headers
$header = array_merge(['Name'], array_map(function ($d) {
    return date("j/n/Y", strtotime($d));
}, $dates));
fputcsv($output, $header);

// Following rows: Attendance
foreach ($attendance_data as $sid => $status_by_date) {
    $row = array_merge([$student_names[$sid] ?? $sid], array_values($status_by_date));
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
