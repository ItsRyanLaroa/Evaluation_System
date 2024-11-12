<?php
include '../db_connect.php';

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM class_list WHERE id={$_GET['id']}")->fetch_array();
    foreach($qry as $k => $v){
        $$k = $v;
    }
}

// Generate a random class code if not already set
$code = isset($code) ? $code : substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
?>
<div class="container-fluid">
    <form action="" id="manage-class">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>

        <!-- Department dropdown -->
        <div class="form-group">
            <label for="curriculum" class="control-label">Department</label>
            <select name="curriculum" id="curriculum" class="custom-select custom-select-sm" required>
                <option value="BSIT" <?php echo isset($curriculum) && $curriculum == 'BSIT' ? 'selected' : '' ?>>BSIT</option>
                <option value="BSBA" <?php echo isset($curriculum) && $curriculum == 'BSBA' ? 'selected' : '' ?>>BSBA</option>
                <option value="BSCRIM" <?php echo isset($curriculum) && $curriculum == 'BSCRIM' ? 'selected' : '' ?>>BSCRIM</option>
                <option value="BSHM" <?php echo isset($curriculum) && $curriculum == 'BSHM' ? 'selected' : '' ?>>BSHM</option>
                <option value="BEED" <?php echo isset($curriculum) && $curriculum == 'BEED' ? 'selected' : '' ?>>BEED</option>
            </select>
        </div>

        <!-- Year Level dropdown -->
        <div class="form-group">
            <label for="level" class="control-label">Year Level</label>
            <select name="level" id="level" class="custom-select custom-select-sm" required>
                <option value="1" <?php echo isset($level) && $level == '1' ? 'selected' : '' ?>>1st Year</option>
                <option value="2" <?php echo isset($level) && $level == '2' ? 'selected' : '' ?>>2nd Year</option>
                <option value="3" <?php echo isset($level) && $level == '3' ? 'selected' : '' ?>>3rd Year</option>
                <option value="4" <?php echo isset($level) && $level == '4' ? 'selected' : '' ?>>4th Year</option>
            </select>
        </div>

        <!-- Section input -->
        <div class="form-group">
            <label for="section" class="control-label">Section</label>
            <input type="text" class="form-control form-control-sm" name="section" id="section" value="<?php echo isset($section) ? $section : '' ?>" required>
        </div>

        <div class="form-group">
            <label class="control-label">Faculty</label>
            <div id="faculty_list">
                <?php
                $faculty = $conn->query("SELECT * FROM faculty_list ORDER BY lastname ASC");
                $selected_faculties = isset($faculty_id) ? explode(',', $faculty_id) : []; // Split selected faculties if any
                while($row = $faculty->fetch_assoc()):
                ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="faculty_id[]" 
                            value="<?php echo $row['id']; ?>" 
                            <?php echo in_array($row['id'], $selected_faculties) ? 'checked' : ''; ?>>
                        <label class="form-check-label">
                            <?php echo $row['lastname'] . ", " . $row['firstname']; ?>
                        </label>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <script>
$(document).ready(function(){
    $('#manage-class').submit(function(e){
        e.preventDefault();
        start_load();
        $('#msg').html('');

        $.ajax({
            url: 'ajax.php?action=save_class',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp){
                if(resp == 1){
                    alert_toast("Data successfully saved.", "success");
                    setTimeout(function(){
                        location.reload();
                    }, 1750);
                } else if(resp == 2){
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Class already exists.</div>');
                } else {
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> An error occurred while saving the class. Please try again.</div>');
                }
                end_load();
            }
        });
    });
});
</script>
