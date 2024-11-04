<?php include 'db_connect.php'; ?>

<?php 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Convert to integer for safety

    // Modify the query to retrieve all teachers and subjects associated with the class
    $class_qry = $conn->prepare("SELECT c.id as class_id, 
                                        concat(c.curriculum, ' ', c.level, '-', c.section) as class,
                                        c.teacher_id, c.subject_id
                                 FROM class_list c
                                 WHERE c.id = ?");
    $class_qry->bind_param("i", $id); // Bind the $id parameter
    $class_qry->execute();
    $result = $class_qry->get_result();

    if ($result->num_rows > 0) {
        $class_data = $result->fetch_assoc();
        $class_name = $class_data['class'];
        $class_id = $class_data['class_id'];
        
        // Fetch teachers
        $teacher_ids = explode(',', $class_data['teacher_id']);
        $teachers = [];
        if (!empty($teacher_ids)) {
            $teacher_id_placeholders = implode(',', array_fill(0, count($teacher_ids), '?'));
            $teacher_qry = $conn->prepare("SELECT firstname, lastname FROM faculty_list WHERE id IN ($teacher_id_placeholders)");
            $teacher_qry->bind_param(str_repeat('i', count($teacher_ids)), ...$teacher_ids);
            $teacher_qry->execute();
            $teacher_result = $teacher_qry->get_result();
            while ($teacher_row = $teacher_result->fetch_assoc()) {
                $teachers[] = $teacher_row['firstname'] . ' ' . $teacher_row['lastname'];
            }
        }

        // Fetch subjects
        $subject_ids = explode(',', $class_data['subject_id']);
        $subjects = [];
        if (!empty($subject_ids)) {
            $subject_id_placeholders = implode(',', array_fill(0, count($subject_ids), '?'));
            $subject_qry = $conn->prepare("SELECT code FROM subject_list WHERE id IN ($subject_id_placeholders)");
            $subject_qry->bind_param(str_repeat('i', count($subject_ids)), ...$subject_ids);
            $subject_qry->execute();
            $subject_result = $subject_qry->get_result();
            while ($subject_row = $subject_result->fetch_assoc()) {
                $subjects[] = $subject_row['code'];
            }
        }
    } else {
        $class_name = 'N/A';
        $class_id = 'N/A';
        $teachers = ['N/A'];
        $subjects = ['N/A'];
    }
} else {
    echo "No valid class ID specified.";
    exit;
}
?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary new_class" href="javascript:void(0)" data-id="<?php echo $class_id; ?>">
                    <i class="fa fa-plus"></i> <span style="color: #dc143c; font-weight: bold;">Add New</span>
                </a>
            </div>
            <a href="index.php?page=class_list" class="btn-back">
                <i class="fa fa-arrow-left"></i> Back
            </a>
            
            <div class="class-details mt-3">
                <span class="class-name" style="font-size: 24px;">Class: <?php echo $class_name; ?></span>
                <span class="class-code" style="font-size: 24px;">| Class ID: <?php echo $class_id; ?></span>
            </div>
            <div class="teacher-details">
                <span class="teacher-name" style="font-size: 24px;">Teacher(s): <?php echo implode(', ', $teachers); ?></span>
                <span class="subject-name" style="font-size: 24px;"> | Subject(s): <span style="color: #dc143c; font-weight: bold; font-size: 24px;"><?php echo implode(', ', $subjects); ?></span>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="list">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>School ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->prepare("SELECT s.*, concat(s.firstname, ' ', s.lastname) as name 
                                            FROM student_list s 
                                            INNER JOIN class_list e ON s.class_id = e.id 
                                            WHERE e.id = ? 
                                            ORDER BY concat(s.firstname,' ',s.lastname) ASC");
                    $qry->bind_param("i", $id);
                    $qry->execute();
                    $result = $qry->get_result();

                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><b><?php echo $row['school_id'] ?></b></td>
                        <td><b><?php echo ucwords($row['name']) ?></b></td>
                        <td><b><?php echo $row['email'] ?></b></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item view_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./index.php?page=edit_student&id=<?php echo $row['id'] ?>">Edit</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item delete_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
                            </div>
                        </td>
                    </tr>  
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
     .btn-back {
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 25px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.3s, box-shadow 0.3s;
        margin-bottom: 15px;
    }

    .btn-back i {
        margin-right: 8px;
    }

    .btn-back:hover {
        background-color: #dc143c;
        color: black;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .class-details, .teacher-details {
        margin-top: 10px;
        font-size: 16px;
    }

    .class-name, .teacher-name {
        font-weight: bold;
    }

    .subject-name {
        color: #666;
    }

    .card-success.card-outline {
        border-top: none;
    }

    .card-tools i{
        color: #dc143c;
        font-weight: bold;
    }

    .table-bordered {
        border: none;
    }

    thead th {
        background-color: #dc143c; 
        color: white;
        text-align: center;
        font-weight: bold;
    }

    tbody tr:hover {
        background-color: #95d2ec;
    }
    .class-details {
        display: flex;
        align-items: center;
        font-size: 18px;
    }

    .class-name {
        font-weight: bold;
        margin-right: 15px;
    }

    .class-code {
        color: #555;
    }

    .teacher-details {
        margin-top: 10px;
        font-size: 16px;
    }

    .teacher-name {
        font-weight: bold;
        color: #333;
    }

    .subject-name {
        font-size: 16px;
        color: #666;
    }

    .card-success.card-outline {
        border-top: none;
    }

    table.table-bordered.dataTable tbody th, 
    table.table-bordered.dataTable tbody td {
        border: none;
        color: #333; 
        font-weight: 500;
    }

    table.table-bordered.dataTable {
        border: none;
    }

    thead th {
        background-color: #dc143c;
        color: white;
        text-align: center;
        font-weight: bold;
    }

    .card-header {
        background-color: transparent;
        border-bottom: none;
    }

    .btn-primary {
        color: blue;
        background-color: white;
        border: none;
    }

    .btn-danger {
        color: red;
        background-color: white;
        border: none;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>

<script>
    $(document).ready(function(){
        $('#list').dataTable();
        $('.view_student').click(function(){
            uni_modal("<i class='fa fa-id-card'></i> Student Details", "<?php echo $_SESSION['login_view_folder'] ?>view_student.php?id=" + $(this).attr('data-id'));
        });
        $('.delete_student').click(function(){
            _conf("Are you sure to delete this student?", "delete_student", [$(this).attr('data-id')]);
        });

        $('.new_class').click(function(){
            var class_id = $(this).attr('data-id');
            uni_modal("New students", "<?php echo $_SESSION['login_view_folder'] ?>manage_student.php?class_id=" + class_id);
        });
    }); 

    function delete_student($id){
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_student',
            method: 'POST',
            data: {id: $id},
            success: function(resp){
                if(resp == 1){
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>
