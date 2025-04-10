<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentName = trim($_POST['new_student'] ?? '');
    $subjectName = trim($_POST['new_subject'] ?? '');
    $gradeValue = trim($_POST['new_grade'] ?? '');

    if (empty($studentName) || empty($subjectName) || empty($gradeValue)) {
        $_SESSION['error'] = "❗ All fields are required.";
        header("Location: index.php");
        exit;
    }

    if (!preg_match("/^[\p{L}\s]+$/u", $studentName)) {
        $_SESSION['error'] = "❗ Invalid student name.";
        header("Location: index.php");
        exit;
    }

    if (!preg_match("/^[\p{L}\s]+$/u", $subjectName)) {
        $_SESSION['error'] = "❗ Invalid subject name.";
        header("Location: index.php");
        exit;
    }

    if (!is_numeric($gradeValue)) {
        $_SESSION['error'] = "❗ Invalid grade. It must be a number.";
        header("Location: index.php");
        exit;
    }

    try {
        // ievieto vai iegūst student id
        $stmt = $pdo->prepare("SELECT id FROM students WHERE name = ?");
        $stmt->execute([$studentName]);
        $studentId = $stmt->fetchColumn();

        if (!$studentId) {
            $stmt = $pdo->prepare("INSERT INTO students (name) VALUES (?)");
            $stmt->execute([$studentName]);
            $studentId = $pdo->lastInsertId();
        }

        // ievieto vai iegūst subject id
        $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_name = ?");
        $stmt->execute([$subjectName]);
        $subjectId = $stmt->fetchColumn();

        if (!$subjectId) {
            $stmt = $pdo->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
            $stmt->execute([$subjectName]);
            $subjectId = $pdo->lastInsertId();
        }

        // ievieto grade
        $stmt = $pdo->prepare("INSERT INTO grades (student_id, subject_id, grade) VALUES (?, ?, ?)");
        $stmt->execute([$studentId, $subjectId, $gradeValue]);

        header("Location: index.php?success=1");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ DB Error: " . htmlspecialchars($e->getMessage());
        header("Location: index.php");
        exit;
    }
}
?>