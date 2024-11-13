<?php
require '../vendor/autoload.php'; // Adjust the path as necessary for PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['excel_file'])) {
    // Check if the file is uploaded without errors
    if ($_FILES['excel_file']['error'] == UPLOAD_ERR_OK) {
        // Identify the file type and load the spreadsheet
        $fileType = IOFactory::identify($_FILES['excel_file']['tmp_name']);
        $reader = IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($_FILES['excel_file']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // Connect to the database
        include '../db_connect.php';

        // Get class ID from the POST data
        $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : '';

        // Check if class_id is valid
        if (empty($class_id)) {
            header('Location: ../index.php?page=students&error=invalid_class_id');
            exit;
        }

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Prepare the insert and check queries
            $stmtInsert = $conn->prepare("INSERT INTO student_list (school_id, firstname, lastname, class_id, email, password, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM student_list WHERE school_id = ?");

            // Set the status to 'active'
            $status = 'active';

            // Loop through each row of the spreadsheet
            foreach ($sheetData as $row) {
                $school_id = $row['A']; // Adjust column index as per your Excel file
                $firstname = $row['B'];
                $lastname = $row['C'];
                $email = $row['D']; // Assuming column D holds the email
                $password = $row['E']; // Assuming column E holds the plain-text password

                // Hash the password using password_hash
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Check if the student already exists
                $stmtCheck->bind_param("s", $school_id);
                $stmtCheck->execute();
                $stmtCheck->bind_result($count);
                $stmtCheck->fetch();

                // Free the result to avoid "Commands out of sync" error
                $stmtCheck->free_result();

                if ($count == 0) {
                    // Student does not exist, insert new record
                    $stmtInsert->bind_param("sssssss", $school_id, $firstname, $lastname, $class_id, $email, $hashedPassword, $status);

                    // Execute the statement and log errors if any
                    if (!$stmtInsert->execute()) {
                        throw new Exception("Error inserting data: " . $stmtInsert->error);
                    }
                }
            }

            // Commit transaction
            $conn->commit();
        } catch (Exception $e) {
            // Rollback if any error occurs
            $conn->rollback();
            error_log($e->getMessage());
            header('Location: ../index.php?page=view_class&id=' . $class_id . '&error=insert_failed');
            exit;
        }

        // Close the statements and database connection
        $stmtCheck->close();
        $stmtInsert->close();
        $conn->close();

        // Redirect the user to the students page for the specific class
        header('Location: ../index.php?page=view_class&id=' . $class_id);
        exit;
    } else {
        // Log file upload error and redirect with error message
        error_log("File upload error: " . $_FILES['excel_file']['error']);
        $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : '';
        header('Location: ../index.php?page=view_class&id=' . $class_id . '&error=upload_error');
        exit;
    }
} else {
    // Handle missing file and redirect with error message
    header('Location: ../index.php?page=view_class&error=file_missing');
    exit;
}
?>
