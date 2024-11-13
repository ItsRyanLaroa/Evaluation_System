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

        // Prepare the insert query including the status column
        $stmt = $conn->prepare("INSERT INTO student_list (school_id, firstname, lastname, class_id, email, password, status) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Set the status to 'active'
        $status = 'active';

        // Loop through each row of the spreadsheet
        foreach ($sheetData as $row) {
            $school_id = $row['A']; // Adjust column index as per your Excel file
            $firstname = $row['B'];
            $lastname = $row['C'];
            $email = $row['D']; // Assuming column D holds the email
            $password = $row['E']; // Assuming column E holds the plain-text password

            // Hash the password using MD5 before storing it in the database
            $hashedPassword = md5($password);

            // Bind the parameters and execute the statement
            $stmt->bind_param("sssssss", $school_id, $firstname, $lastname, $class_id, $email, $hashedPassword, $status);

            // Execute the statement and log errors if any
            if (!$stmt->execute()) {
                error_log("Error inserting data: " . $stmt->error);
            }
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();

        // Redirect the user to the students page for the specific class
        header('Location: ../index.php?page=view_class&id=' . $class_id);
        exit;
    } else {
        // Handle file upload error and redirect with error message
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
