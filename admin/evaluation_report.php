<?php
include '../db_connect.php';
$faculty_id = isset($_GET['fid']) ? $_GET['fid'] : '';

function ordinal_suffix($num) {
    $num = $num % 100; // Protect against large numbers
    if ($num < 11 || $num > 13) {
        switch ($num % 10) {
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}
?>
<div class="col-lg-12">
    <div class="callout callout-info">
        <div class="d-flex w-100 justify-content-center align-items-center">
            <label for="faculty">Select Faculty</label>
            <div class="mx-2 col-md-4">
                <select name="" id="faculty_id" class="form-control form-control-sm select2">
                    <option value=""></option>
                    <?php 
                    $faculty = $conn->query("SELECT *, concat(firstname,' ',lastname) as name FROM faculty_list ORDER BY concat(firstname,' ',lastname) ASC");
                    $fname = array();
                    while($row = $faculty->fetch_assoc()):
                        $fname[$row['id']] = ucwords($row['name']);
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? "selected" : "" ?>><?php echo ucwords($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-1">
            <div class="d-flex justify-content-end w-100">
                <button class="btn btn-sm btn-success bg-gradient-success" style="display:none" id="print-btn">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="callout callout-info">
                <div class="list-group" id="class-list">
                    <!-- Class list will be loaded here -->
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="callout callout-info" id="printable">
                <div>
                    <h3 class="text-center">Evaluation Report</h3>
                    <hr>
                    <table width="100%">
                        <tr>
                            <td width="50%"><p><b>Faculty: <span id="fname"></span></b></p></td>
                            <td width="50%"><p><b>Academic Year: <span id="ay"><?php echo $_SESSION['academic']['year'].' '.ordinal_suffix($_SESSION['academic']['semester']) ?> Semester</span></b></p></td>
                        </tr>
                        <tr>
                            <td width="50%"><p><b>Class: <span id="classField"></span></b></p></td>
                            <td width="50%"><p><b>Subject: <span id="subjectField"></span></b></p></td>
                        </tr>
                    </table>
                    <p><b>Total Students Evaluated: <span id="tse"></span></b></p>
                </div>
                <fieldset class="border border-info p-2 w-100">
                    <legend class="w-auto">Rating Legend</legend>
                    <p>5 = Strongly Agree, 4 = Agree, 3 = Uncertain, 2 = Disagree, 1 = Strongly Disagree</p>
                </fieldset>
                <!-- Questions and ratings are dynamically generated here -->
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#faculty_id').change(function(){
            if ($(this).val() > 0)
                window.history.pushState({}, null, './index.php?page=report&fid=' + $(this).val());
            load_class();
        });
        if ($('#faculty_id').val() > 0)
            load_class();
    });

    function load_class(){
        start_load();
        var fname = <?php echo json_encode($fname) ?>;
        $('#fname').text(fname[$('#faculty_id').val()]);
        $.ajax({
            url: "ajax.php?action=get_class",
            method: "POST",
            data: {faculty_id: $('#faculty_id').val()},
            success: function(resp){
                $('#class-list').html(resp);
                end_load();
            }
        });
    }
</script>
