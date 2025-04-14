<?php
// Include the PDO connection
require_once 'db_connection.php';

// Ensure no extra output before sending JSON response
header('Content-Type: application/json');

// Read the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Check if the required data is present
if (isset($data['student_id']) && isset($data['subject_id'])) {
    $studentId = $data['student_id'];
    $subjectId = $data['subject_id'];

    // Prepare the DELETE statement using PDO
    $sql = "DELETE FROM grades WHERE student_id = :student_id AND subject_id = :subject_id";

    try {
        // Prepare and execute the query using PDO
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':subject_id', $subjectId, PDO::PARAM_INT);
        
        // Execute the query
        $stmt->execute();

        // Check if the row was actually deleted
        if ($stmt->rowCount() > 0) {
            // Send JSON response (success)
            echo json_encode(["success" => true]);
        } else {
            // Send JSON response (nothing was deleted)
            echo json_encode(["success" => false, "error" => "No grade found to delete"]);
        }
    } catch (PDOException $e) {
        // Send JSON response (PDO error)
        echo json_encode(["success" => false, "error" => "Error deleting grade: " . $e->getMessage()]);
    }
} else {
    // Send JSON response (invalid data)
    echo json_encode(["success" => false, "error" => "Invalid data received"]);
}

// Close the connection (optional, since PDO will automatically handle it)
$pdo = null;
?>
