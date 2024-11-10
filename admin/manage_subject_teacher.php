<?php
include '../db_connect.php';

// Sanitize and validate the subject_id
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;
if ($subject_id <= 0) {
    die("Invalid subject ID."); // Handle invalid subject ID
}

// Fetch the subject name for display
$subject_qry = $conn->query("SELECT subject FROM subject_list WHERE id = $subject_id");
if (!$subject_qry || $subject_qry->num_rows == 0) {
    die("Subject not found."); // Handle missing subject
}
$subject = $subject_qry->fetch_assoc();

// Fetch all teachers from the faculty list
$teachers = $conn->query("SELECT * FROM faculty_list ORDER BY lastname ASC");
if (!$teachers) {
    die("Failed to fetch teachers."); // Handle teacher fetch error
}

// Fetch already associated teachers for this subject
$assigned_teachers_qry = $conn->query("SELECT faculty_id FROM subject_teacher WHERE subject_id = $subject_id");
$assigned_teachers = [];
while ($row = $assigned_teachers_qry->fetch_assoc()) {
    $assigned_teachers[] = $row['faculty_id'];
}

// Fetch academic years and semesters
$academic_years = $conn->query("SELECT * FROM academic_list ORDER BY year DESC, semester DESC");
if (!$academic_years) {
    die("Failed to fetch academic years."); // Handle academic years fetch error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subject Teachers</title>
    <link rel="stylesheet" href="/path/to/your/css/bootstrap.min.css">
</head>
<body>

<div class="container-fluid">
    <h4>Manage Teachers for Subject: <?php echo htmlspecialchars($subject['subject']); ?></h4>
    <form id="manage-subject-teacher-form">
        <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">

        <div id="msg" class="form-group"></div>

        <!-- Academic Year and Semester Selection -->
        <div class="form-group">
            <label for="academic_year">Select Academic Year & Semester</label>
            <select name="academic_year" id="academic_year" class="form-control" required>
                <option value="">-- Select Academic Year & Semester --</option>
                <?php while ($row = $academic_years->fetch_assoc()): ?>
                    <option value="<?php echo intval($row['id']); ?>">
                        <?php echo $row['year'] . " - Semester " . $row['semester']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- List of teachers with checkboxes -->
        <div class="form-group">
            <label class="control-label">Select Teachers</label>
            <div id="teacher_list">
                <?php while ($row = $teachers->fetch_assoc()): ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="faculty_id[]" 
                            value="<?php echo intval($row['id']); ?>" 
                            <?php echo in_array($row['id'], $assigned_teachers) ? 'checked' : ''; ?>>
                        <label class="form-check-label">
                            <?php echo htmlspecialchars($row['lastname']) . ", " . htmlspecialchars($row['firstname']); ?>
                        </label>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

    </form>
</div>

<script>
$(document).ready(function(){
    $('#manage-subject-teacher-form').submit(function(e){
        e.preventDefault();
        start_load();
        $('#msg').html(''); // Clear any previous messages

        $.ajax({
            url: 'ajax.php?action=save_subject_teacher', // Send to the PHP script to process
            method: 'POST',
            data: $(this).serialize(), // This will include subject_id, faculty_id[], and academic_year
            success: function(resp){
                if (resp == 1) {
                    alert_toast("Data successfully saved.", "success");
                    setTimeout(function(){
                        location.reload(); // Reload the page after success
                    }, 1750);
                } else if (resp == 2) {
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> This teacher is already assigned to this subject for the selected academic year and semester.</div>');
                } else {
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> An error occurred while saving. Please try again.</div>');
                }
                end_load();
            },
            error: function() {
                $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> An unexpected error occurred.</div>');
                end_load();
            }
        });
    });
});

</script>

</body>
</html>
