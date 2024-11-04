<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['login_id'])) {
    echo 'Login ID not found. Please log in.';
    exit;
}

$faculty_id = $_SESSION['login_id'];

// Fetch faculty details
$stmt = $conn->prepare("SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM faculty_list WHERE id = ?");
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
$faculty = $result->fetch_assoc();
$faculty_name = $faculty['name'];

// Fetch classes and subjects for the faculty
$query = "
    SELECT 
        cl.id AS class_id, 
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
    if (!isset($c_arr[$row['class_id']])) {
        $c_arr[$row['class_id']] = $row['class_name'];
    }
    if (!isset($s_arr[$row['subject_id']])) {
        $s_arr[$row['subject_id']] = $row['subject_name'];
    }
}

// Fetch active academic year
$active_academic_query = "SELECT id, year FROM academic_list WHERE status = 1";
$active_academic_result = $conn->query($active_academic_query);
$active_academic = $active_academic_result->fetch_assoc();

$active_academic_id = isset($active_academic['id']) ? $active_academic['id'] : '';
$active_academic_year = isset($active_academic['year']) ? $active_academic['year'] : 'No Active Academic Year';
?>

<!-- HTML and JavaScript for Form Submission -->
<div class="container-fluid">
    <form action="" id="manage-restriction-<?php echo htmlspecialchars($faculty_id); ?>">
        <input type="hidden" name="faculty_id" value="<?php echo htmlspecialchars($faculty_id); ?>">
        
        <!-- Modal Header -->
        <div class="modal-header">
            <h5>Add subject</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <div class="row">
                <!-- Class Dropdown -->
                <div class="form-group">
                    <label for="class_id" class="control-label">Class</label>
                    <select name="class_id" id="class_id-<?php echo htmlspecialchars($faculty_id); ?>" class="form-control form-control-sm select2" required>
                        <option value="">Select Class</option>
                        <?php foreach ($c_arr as $class_id => $class): ?>
                            <option value="<?php echo htmlspecialchars($class_id); ?>">
                                <?php echo htmlspecialchars($class); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Subject Dropdown -->
                <div class="form-group">
                    <label for="subject_id" class="control-label">Subject</label>
                    <select name="subject_id" id="subject_id-<?php echo htmlspecialchars($faculty_id); ?>" class="form-control form-control-sm select2" required>
                        <option value="">Select Subject</option>
                        <?php foreach ($s_arr as $subject_id => $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject_id); ?>">
                                <?php echo htmlspecialchars($subject); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $('#manage-restriction-<?php echo htmlspecialchars($faculty_id); ?>').submit(function(e) {
        e.preventDefault();
        $('input, select').removeClass("border-danger");
        start_load();

        const formData = new FormData($(this)[0]);
        formData.append('academic_id', '<?php echo htmlspecialchars($active_academic_id); ?>');

        $.ajax({
            url: 'ajax.php?action=save_restriction',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast('Restriction successfully saved.', "success");
                    setTimeout(function() {
                        location.replace('index.php?page=subject');
                    }, 750);
                } else if (resp == 2) {
                    alert_toast('Duplicate class and subject combination found.', "warning");
                    end_load();
                } else if (resp == 3) {
                    alert_toast('Duplicate entry for this faculty ID.', "warning");
                    end_load();
                } else {
                    alert_toast('Failed to save restriction.', "error");
                    end_load();
                }
            },
            error: function() {
                alert_toast('An error occurred. Please try again.', "error");
                end_load();
            }
        });
    });
</script>
