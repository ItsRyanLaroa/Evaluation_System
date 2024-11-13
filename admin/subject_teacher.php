<?php 
include 'db_connect.php';

// Get the subject ID from the URL
$subject_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch subject details
$subject_qry = $conn->query("SELECT * FROM subject_list WHERE id = $subject_id");
$subject = $subject_qry->fetch_assoc();

// Updated query to fetch teachers with the academic year
$teachers_qry = $conn->query("SELECT st.id AS subject_teacher_id, 
                                      CONCAT(t.firstname, ' ', t.lastname) AS full_name,
                                      a.year
                               FROM faculty_list t 
                               JOIN subject_teacher st ON t.id = st.faculty_id 
                               JOIN academic_list a ON st.academic_year = a.id 
                               WHERE st.subject_id = $subject_id AND a.status = 1");

if (!$teachers_qry) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subject Teachers</title>
    <link rel="stylesheet" href="path/to/your/css/style.css">
</head>
<body>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <a class="btn btn-sm btn-default btn-flat border-primary" href="index.php?page=subject_list">
                <i class="fa fa-arrow-left" style="color: #dc143c; font-weight: bold;"> Back to Subjects</i> 
            </a>
            <br>
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary add_teacher" href="javascript:void(0)">
                    <i class="fa fa-plus"></i> <span style="color: #dc143c; font-weight: bold;">Add Teacher</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered styled-table" id="teacher_list">
                <h3 class="card-title">Teachers for Subject: <?php echo htmlspecialchars($subject['subject']); ?></h3>
                <br><br>
                <colgroup>
                    <col width="10%">
                    <col width="50%">
                    <col width="20%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Teacher Name</th>
                        <th>Academic Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($row = $teachers_qry->fetch_assoc()):
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['year']); ?></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-flat delete_subject" data-id="<?php echo $row['subject_teacher_id'] ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="path/to/your/js/jquery.min.js"></script>
<script src="path/to/your/js/bootstrap.bundle.min.js"></script>
<script src="path/to/your/js/datatables.min.js"></script>
<script>
   $(document).ready(function(){
    $('#teacher_list').dataTable();

    // Open the modal for adding a new teacher to the subject
    $('.add_teacher').click(function(){
        uni_modal("Add Teacher to Subject", "<?php echo $_SESSION['login_view_folder'] ?>manage_subject_teacher.php?subject_id=<?php echo $subject_id; ?>");
    });

    // Trigger the delete function on button click
    $('.delete_subject').click(function(){
        let id = $(this).data('id');
        if (confirm("Are you sure you want to delete this teacher from the subject?")) {
            delete_subject(id);
        }
    });
});

function delete_subject(id) {
    $.ajax({
        url: 'ajax.php?action=delete_subject_teacher',
        method: 'POST',
        data: { id: id },
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Data successfully deleted", 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                alert_toast("Failed to delete data", 'danger');
            }
        }
    });
}
</script>

</body>
</html>
