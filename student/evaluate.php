<?php 

// Function to get ordinal suffix
function ordinal_suffix($num) {
    $num = $num % 100; // protect against large numbers
    if ($num < 11 || $num > 13) {
        switch ($num % 10) {
            case 1: return $num . 'st';
            case 2: return $num . 'nd';
            case 3: return $num . 'rd';
        }
    }
    return $num . 'th';
}

// Initialize variables from GET parameters
$rid = isset($_GET['rid']) ? $_GET['rid'] : '';
$faculty_id = isset($_GET['fid']) ? $_GET['fid'] : '';
$subject_id = isset($_GET['sid']) ? $_GET['sid'] : '';

// Fetch restrictions with evaluations if they exist
$restriction = $conn->query("SELECT r.id, s.id as sid, f.id as fid, concat(f.firstname, ' ', f.lastname) as faculty, s.code, s.subject, el.evaluation_id as evaluation_id
    FROM restriction_list r
    INNER JOIN faculty_list f ON f.id = r.faculty_id
    INNER JOIN subject_list s ON s.id = r.subject_id
    LEFT JOIN evaluation_list el ON el.restriction_id = r.id 
        AND el.academic_id = {$_SESSION['academic']['id']} 
        AND el.student_id = {$_SESSION['login_id']}
    WHERE r.academic_id = {$_SESSION['academic']['id']}
        AND r.class_id = {$_SESSION['login_class_id']}
");

?>

<style>
    .list-group-item.active {
        z-index: 2;
        color: #fff;
        background-color: #dc143c;
        border-color: black;
    }

    .card-info.card-outline {
        border-top: 3px solid #dc143c !important;
    }

    .border-info {
        border-color: #dc143c !important;
        margin-bottom: 20px;
        margin-top: 20px;
    }

    .bg-gradient-secondary {
        background: #007bff !important;
        color: #fff;
    }

   .evaluated {
    color: white; 
    cursor: not-allowed; 
    pointer-events: none; /* Disables any interaction with the element */
    user-select: none; /* Prevents text selection */
}

.evaluated .badge {
    cursor: not-allowed;
}

.evaluated input[type="radio"] {
    pointer-events: none; /* Disables radio buttons */
}

.evaluated label {
    pointer-events: none; /* Prevents clicking on the label */
}

.evaluated:hover {
    background-color: transparent; /* Prevents hover effect */
}

    .evaluated { color: white; cursor: not-allowed; } .evaluated .badge { cursor: not-allowed; }
    
</style>

<div class="col-lg-12">
    <div class="row">
      <div class="col-md-3">
    <div class="list-group">
        <?php 
        $displayed_ids = []; // Array to track displayed IDs
        while ($row = $restriction->fetch_array()):
            // Remove the automatic selection code
            if (!in_array($row['id'], $displayed_ids)) { // Check if ID has already been displayed
                $displayed_ids[] = $row['id']; // Add ID to the array
        ?>
        <a class="list-group-item list-group-item-action <?php echo isset($rid) && $rid == $row['id'] ? 'active' : '' ?>" 
            href="./index.php?page=evaluate&rid=<?php echo $row['id'] ?>&sid=<?php echo $row['sid'] ?>&fid=<?php echo $row['fid'] ?>"
            <?php echo $row['evaluation_id'] ? 'style="pointer-events: none;"' : ''; ?>>
            <?php echo ucwords($row['faculty']) . ' - (' . $row["code"] . ') ' . $row['subject'] ?>
            <?php if ($row['evaluation_id']): ?>
                <span class="badge badge-success evaluated">
                    <i class="fa fa-check"></i> Done
                </span>
            <?php else: ?>
                <span class="badge badge-warning">Not Evaluated</span>
            <?php endif; ?>
        </a>

        <?php 
            } // End of ID check
        endwhile; ?>
    </div>
</div>

        <div class="col-md-9">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <b>Evaluation Questionnaire for Academic: <?php echo $_SESSION['academic']['year'] . ' ' . (ordinal_suffix($_SESSION['academic']['semester'])) ?></b>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-flat btn-primary bg-gradient-primary mx-1" form="manage-evaluation" id="submit-evaluation" disabled>Submit Evaluation</button>
                    </div>
                </div>
                <div class="card-body">
                    <fieldset class="border border-info p-2 w-100">
                        <legend class="w-auto">Rating Legend</legend>
                        <p>5 = Strongly Agree, 4 = Agree, 3 = Uncertain, 2 = Disagree, 1 = Strongly Disagree</p>
                    </fieldset>
                    <form id="manage-evaluation">
                        <input type="hidden" name="class_id" value="<?php echo $_SESSION['login_class_id'] ?>">
                        <input type="hidden" name="faculty_id" value="<?php echo $faculty_id ?>">
                        <input type="hidden" name="restriction_id" value="<?php echo $rid ?>">
                        <input type="hidden" name="subject_id" value="<?php echo $subject_id ?>">
                        <input type="hidden" name="academic_id" value="<?php echo $_SESSION['academic']['id'] ?>">
                        <div class="clear-fix mt-2"></div>
                        <?php 
                        $q_arr = array();
                        $criteria = $conn->query("SELECT * FROM criteria_list WHERE id IN 
                            (SELECT criteria_id FROM question_list WHERE academic_id = {$_SESSION['academic']['id']}) 
                            ORDER BY abs(order_by) ASC");
                        while ($crow = $criteria->fetch_assoc()):
                        ?>
                        <table class="table table-condensed">
                            <thead>
                                <tr class="bg-gradient-secondary">
                                    <th class="p-1"><b><?php echo $crow['criteria'] ?></b></th>
                                    <th class="text-center">1</th>
                                    <th class="text-center">2</th>
                                    <th class="text-center">3</th>
                                    <th class="text-center">4</th>
                                    <th class="text-center">5</th>
                                </tr>
                            </thead>
                            <tbody class="tr-sortable">
                                <?php 
                                $questions = $conn->query("SELECT * FROM question_list 
                                    WHERE criteria_id = {$crow['id']} 
                                    AND academic_id = {$_SESSION['academic']['id']} 
                                    ORDER BY abs(order_by) ASC");
                                while ($row = $questions->fetch_assoc()):
                                    $q_arr[$row['id']] = $row;
                                    $isEvaluated = isset($row['evaluation_id']) && $row['evaluation_id'] > 0;
                                ?>
                                <tr class="bg-white">
                                    <td class="p-1" width="40%">
                                        <?php echo $row['question'] ?>
                                        <input type="hidden" name="qid[]" value="<?php echo $row['id'] ?>">
                                    </td>
                                    <?php for ($c = 1; $c <= 5; $c++): ?>
                                    <td class="text-center">
                                        <div class="icheck-success d-inline">
                                            <input type="radio" name="rate[<?php echo $row['id'] ?>]" id="qradio<?php echo $row['id'] . '_' . $c ?>" value="<?php echo $c ?>" <?php echo $isEvaluated ? 'disabled' : ''; ?>>
                                            <label for="qradio<?php echo $row['id'] . '_' . $c ?>"></label>
                                        </div>
                                    </td>
                                    <?php endfor; ?>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <?php endwhile; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    // Prevent clicking on links if they are evaluated
    $('.evaluated-link').on('click', function(e) {
        e.preventDefault(); // Prevent the link from being clicked
        alert('This evaluation has already been completed.');
    });

    // Function to check if all questions have been answered
    function checkFormCompletion() {
        let allAnswered = true;
        $('input[type="radio"]').each(function() {
            const name = $(this).attr('name');
            if (!$('input[name="' + name + '"]:checked').length) {
                allAnswered = false;
                return false; // exit loop if any question is unanswered
            }
        });
        // Enable the submit button if all questions are answered
        $('#submit-evaluation').prop('disabled', !allAnswered);
    }

    // Event listener for when a radio button is selected
    $('input[type="radio"]').on('change', checkFormCompletion);

    // Initial check if form is complete
    checkFormCompletion();

    $('#manage-evaluation').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_evaluation',
            method: 'POST',
            data: $(this).serialize(),
            success:function(response){
                end_load();
                if(response == 1){
                    alert_toast("Evaluation successfully submitted.", "success");
                    
                    // Automatically redirect to the next restriction
                    setTimeout(function(){
                        let nextRestriction = $('.list-group-item.active').next('.list-group-item');
                        if (nextRestriction.length) {
                            let nextUrl = nextRestriction.attr('href');
                            window.location.href = nextUrl;
                        } else {
                            alert("All evaluations are completed.");
                        }
                    }, 1500);
                } else {
                    alert_toast("Error saving the evaluation.", "error");
                }
            }
        });
    });
});
</script>

