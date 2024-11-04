<?php
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

function handleError($message) {
    error_log($message, 3, '../logs/error_log.txt');
    header('Location: ../index.php?page=faculty_list&error=1');
    exit;
}

if (isset($_FILES['excel_file'])) {
    if ($_FILES['excel_file']['error'] == UPLOAD_ERR_OK) {
        $fileType = IOFactory::identify($_FILES['excel_file']['tmp_name']);
        $reader = IOFactory::createReader($fileType);
        
        try {
            $spreadsheet = $reader->load($_FILES['excel_file']['tmp_name']);
        } catch (Exception $e) {
            handleError("Error loading spreadsheet: " . $e->getMessage());
        }
        
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        include '../db_connect.php';

        foreach ($sheetData as $row) {
            $school_id = $row['A'];
            $firstname = $row['B'];
            $lastname = $row['C'];
            $position = $row['D']; 
            $email = $row['E'];
            $password = $row['F'];

            if (empty($school_id) || empty($firstname) || empty($lastname) || empty($position) || empty($email) || empty($password)) {
                error_log("Incomplete data for school_id: $school_id", 3, '../logs/error_log.txt');
                continue;
            }

            $hashedPassword = md5($password);

            $checkStmt = $conn->prepare("SELECT id FROM faculty_list WHERE school_id = ?");
            if (!$checkStmt) {
                handleError("Prepare failed: " . $conn->error);
            }
            $checkStmt->bind_param("s", $school_id);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                error_log("Duplicate entry for school_id: $school_id", 3, '../logs/error_log.txt');
            } else {
                $result = $conn->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM faculty_list");
                $next_id = $result->fetch_assoc()['next_id'];

                // Retrieve the next available id for faculty_list
                $result = $conn->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM faculty_list");
                $next_id = $result->fetch_assoc()['next_id'];

                // Prepare and bind for insertion
                $stmt = $conn->prepare("INSERT INTO faculty_list (id, school_id, firstname, lastname, position, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt) {
                    handleError("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param("issssss", $next_id, $school_id, $firstname, $lastname, $position, $email, $hashedPassword);

                if (!$stmt->execute()) {
                    handleError("Error inserting data for school_id $school_id: " . $stmt->error);
                }
                $stmt->close();
            }

            $checkStmt->close();
        }

        header('Location: ../index.php?page=faculty_list&success=1');
        exit;
    } else {
        handleError("File upload error: " . $_FILES['excel_file']['error']);
    }
} else {
    handleError("No file was uploaded.");
}
?>
