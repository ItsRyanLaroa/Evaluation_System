<?php
session_start();
include 'db_connect.php';

// Ensure the request is coming via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all necessary parameters are set
    if (isset($_POST['action']) && $_POST['action'] === 'save_restriction') {
        // Check if the login ID and academic ID are set in the session
        if (!isset($_SESSION['login_id']) || !isset($_SESSION['academic']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Login ID or Academic ID not set in session.']);
            exit;
        }

        $faculty_id = $_SESSION['login_id'];
        $academic_id = $_SESSION['academic']['id'];
        
        // Check if class ID and subject ID are provided
        if (isset($_POST['class_id']) && isset($_POST['subject_id'])) {
            $class_id = $_POST['class_id'];
            $subject_id = $_POST['subject_id'];

            // Check if this restriction already exists in the database
            $stmt = $conn->prepare("SELECT id FROM restriction_list WHERE academic_id = ? AND faculty_id = ? AND class_id = ? AND subject_id = ?");
            $stmt->bind_param("iiii", $academic_id, $faculty_id, $class_id, $subject_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                // No existing record found, proceed with the insertion
                $stmt = $conn->prepare("INSERT INTO restriction_list (academic_id, faculty_id, class_id, subject_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiii", $academic_id, $faculty_id, $class_id, $subject_id);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Restriction added successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'This restriction already exists.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Class ID and Subject ID are required.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action or missing parameters.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
