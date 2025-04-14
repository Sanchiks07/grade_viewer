<?php
session_start();

require "config.php";

// Check if the form was submitted with the correct POST parameters
if (isset($_POST['edit_student']) && isset($_POST['edit_subject']) && isset($_POST['edit_grade'])) {
    // Get the updated values from the form submission
    $student = htmlspecialchars($_POST['edit_student']);
    $subject = htmlspecialchars($_POST['edit_subject']);
    $grade = (int)$_POST['edit_grade'];  // Make sure the grade is an integer

    // Validate the input
    if (empty($student) || empty($subject) || empty($grade)) {
        $_SESSION['error'] = "All fields are required!";
        echo json_encode(['success' => false, 'message' => 'All fields are required!']);
        exit;
    }

    // Update the grade in the database
    try {
        // Prepare SQL statement to update the grade
        $stmt = $pdo->prepare("UPDATE grades SET grade = :grade WHERE student_name = :student AND subject_name = :subject");
        $stmt->bindParam(':grade', $grade, PDO::PARAM_INT);
        $stmt->bindParam(':student', $student, PDO::PARAM_STR);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            // Return success response
            echo json_encode(['success' => true, 'message' => 'Grade updated successfully!']);
        } else {
            // Return failure response
            echo json_encode(['success' => false, 'message' => 'Error updating grade.']);
        }
    } catch (PDOException $e) {
        // If there's a database error, output it
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Handle case where form values are missing
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
