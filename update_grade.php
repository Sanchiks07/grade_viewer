<?php
require_once 'config.php';

// Debugging: Log the incoming data
error_log('Incoming Data: ' . print_r($_POST, true));

// Validate incoming data
if (!isset($_POST['student_id'], $_POST['subject_id'], $_POST['grade'], $_POST['student_name'], $_POST['subject_name'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

$studentId = $_POST['student_id'];
$subjectId = $_POST['subject_id'];
$grade = $_POST['grade'];
$studentName = $_POST['student_name'];
$subjectName = $_POST['subject_name'];

// Make sure the student and subject IDs exist in their respective tables
try {
    // Update grade
    $stmt = $pdo->prepare("UPDATE grades SET grade = :grade WHERE student_id = :student_id AND subject_id = :subject_id");
    $stmt->execute(['grade' => $grade, 'student_id' => $studentId, 'subject_id' => $subjectId]);

    // Update student name (if needed)
    $stmt = $pdo->prepare("UPDATE students SET name = :student_name WHERE id = :student_id");
    $stmt->execute(['student_name' => $studentName, 'student_id' => $studentId]);

    // Update subject name (if needed)
    $stmt = $pdo->prepare("UPDATE subjects SET subject_name = :subject_name WHERE id = :subject_id");
    $stmt->execute(['subject_name' => $subjectName, 'subject_id' => $subjectId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log("Error updating grade: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
