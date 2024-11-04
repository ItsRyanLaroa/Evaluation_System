<?php
include 'db_connect.php';

// Initialize variables
$academic_id = null;

// Get the academic ID from the session
if (isset($_SESSION['academic']['id'])) {
    $academic_id = $_SESSION['academic']['id'];
} else {
    die("Academic ID not set in session.");
}

// Get the faculty ID from the session
$faculty_id = $_SESSION['login_id'];

// Fetch the faculty details using the login ID safely
$stmt = $conn->prepare("SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM faculty_list WHERE id = ?");
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$faculty = $stmt->get_result()->fetch_assoc();
$faculty_name = $faculty['name'];

// Fetch classes and subjects for the specific teacher safely
$query = "
    SELECT cl.id AS class_id, 
           CONCAT(cl.curriculum, ' ', cl.level, ' - ', cl.section) AS class_name,
           sl.id AS subject_id,
           CONCAT(sl.code, ' - ', sl.subject) AS subject_name
    FROM class_list cl
    JOIN subject_list sl ON FIND_IN_SET(sl.id, cl.subject_id) > 0
    WHERE FIND_IN_SET(?, cl.teacher_id) > 0
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$classes_and_subjects = $stmt->get_result();

$c_arr = [];
$s_arr = [];
while ($row = $classes_and_subjects->fetch_assoc()) {
    $c_arr[$row['class_id']] = $row['class_name'];
    $s_arr[$row['subject_id']] = $row['subject_name'];
}
?>

<style>
    .card-tools i{
    color: #dc143c;
    font-weight: bold;
}
</style>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary new_subject" href="javascript:void(0)">
                    <i class="fa fa-plus"></i> Add New
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table tabe-hover table-bordered styled-table" id="r-list">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (!empty($academic_id)) {
                        $stmt = $conn->prepare("SELECT * FROM restriction_list WHERE academic_id = ? AND faculty_id = ? ORDER BY id ASC");
                        $stmt->bind_param("ii", $academic_id, $faculty_id);
                        $stmt->execute();
                        $restriction = $stmt->get_result();
                        
                        while ($row = $restriction->fetch_assoc()): 
                    ?>
                    <tr>
                        <td>
                            <b><?php echo isset($c_arr[$row['class_id']]) ? $c_arr[$row['class_id']] : ''; ?></b>
                            <input type="hidden" name="rid[]" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="class_id[]" value="<?php echo $row['class_id']; ?>">
                        </td>
                        <td>
                            <b><?php echo isset($s_arr[$row['subject_id']]) ? $s_arr[$row['subject_id']] : ''; ?></b>
                            <input type="hidden" name="subject_id[]" value="<?php echo $row['subject_id']; ?>">
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                               
                                <button type="button" class="btn btn-danger btn-flat delete_class" data-id="<?php echo $row['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    } else {
                        echo "<tr><td colspan='3'>No academic ID specified.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#r-list').dataTable();
    
    // Handle the "Add New" button click
    $('.new_subject').click(function(){
        uni_modal("", "<?php echo $_SESSION['login_view_folder']; ?>manage_subject.php?faculty_id=<?php echo $faculty_id; ?>&academic_id=<?php echo $academic_id; ?>");
    });

    $('.edit_class').click(function(){
        let id = $(this).attr('data-id');
        uni_modal("Edit Class", "<?php echo $_SESSION['login_view_folder']; ?>manage_subject.php?id=" + id);
    });

    $('.delete_class').click(function(){
        let id = $(this).attr('data-id');
        _conf("Are you sure to delete this entry?", "delete_subject_restriction", [id]);
    });
});

function delete_subject_restriction(id){
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_subject_restriction',
        method: 'POST',
        data: { id: id },
        success: function(resp){
            if(resp == 1){
                alert_toast("Data successfully deleted", 'success');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else {
                alert_toast("An error occurred while deleting", 'error');
            }
        }
    });
}
</script>

<style>
/* Table styling */
table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {
    border-bottom-width: 0;
    border: none;
    color: #333;
    font-weight: 500;
}

.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

thead th {
    background-color: #dc143c;
    color: #f3f3f3;
    font-weight: bold;
}

/* Button styles */
.btn-primary {
    color: blue;
    background-color: transparent;
    border: none;
}

.btn-danger {
    color: red;
    background-color: transparent;
    border: none;
}

.card-header {
    background-color: transparent;
    border-bottom: none;
    padding: .75rem 1.25rem;
    position: relative;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
}

/* Hover effect for rows */
tbody tr:hover {
    background-color: #f1f1f1;
}
</style>
