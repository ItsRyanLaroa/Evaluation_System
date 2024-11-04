<?php include 'db_connect.php' ?>
<style>
  .card-header {
    background-color: transparent;
    border-bottom: none;
}
    .nav-bar {
        display: flex; 
    }

    .nav-link {
        margin: 0 10px;
        text-decoration: none;
        color: black;
    }

    .nav-link.active {
        font-weight: bold;
        color: #dc143c;
        border-bottom: 2px solid #007bff;
        margin-bottom: 10px; 
    }

    /* .nav-bar a {
        text-decoration: none;
        padding: 10px 15px;
        color: #333;
      
    }

    .nav-bar a.active {
        font-weight: bold;
        border-bottom: 2px solid blue;
        font-family: tahoma;
    } */

    .content-container {
        margin-top: 20px;
        padding: 20px;
    }

    .card button {
        padding: 10px 15px;
        background-color: #dc143c;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .card button:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .card button:not(:disabled):hover {
        background-color: darkblue;
    }
    .bg-gradient-primary {
    background: #dc143c linear-gradient(180deg, #dc143c, #dc143c) repeat-x !important;
    color: #fff;
    
}
.bg-gradient-secondary {
    background: #000000 linear-gradient(180deg, #000000, #6c757d) repeat-x !important;
    color: #fff;
}

.card-info.card-outline{
    border-top: 3px solid #dc143c;
}

.callout.callout-info{
    border-left-color: #dc143c;
}

.card-primary.card-outline{
    border-top: 3px solid #dc143c;
}
</style>

<div class="nav-bar">
    <a href="./index.php?page=category" class="nav-link nav-category <?php echo (isset($_GET['page']) && $_GET['page'] == 'category') ? 'active' : ''; ?>">Category</a>
    <a href="./index.php?page=semester" class="nav-link nav-semester <?php echo (isset($_GET['page']) && $_GET['page'] == 'semester') ? 'active' : ''; ?>">Semester</a>
    <a href="#" class="nav-link nav-questionnaire <?php echo (isset($_GET['page']) && $_GET['page'] == 'questionnaire') ? 'active' : ''; ?>" id="questionnaireLink">Questionnaire</a>
</div>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header"></div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-outline card-info">
                            <div class="card-header"><b>Category Form</b></div>
                            <div class="card-body">
                                <form action="" id="manage-criteria">
                                    <input type="hidden" name="id">
                                    <div class="form-group">
                                        <label for="">Category</label>
                                        <input type="text" name="criteria" class="form-control form-control-sm">
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end w-100">
                                    <button class="btn btn-sm btn-primary btn-flat bg-gradient-primary mx-1" form="manage-criteria">Save</button>
                                    <button class="btn btn-sm btn-flat btn-secondary bg-gradient-secondary mx-1" form="manage-criteria" type="reset">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="callout callout-info">
                            <?php 
                                $qry = $conn->query("SELECT * FROM criteria_list ORDER BY ABS(order_by) ASC");
                                if($qry->num_rows > 0):
                            ?>
                            <div class="d-flex justify-content-between w-100">
                                <label for=""><b>Criteria List</b></label>
                                <button class="btn btn-sm btn-primary btn-flat bg-gradient-primary mx-1" form="order-criteria">Save Order</button>
                            </div>
                            <hr>
                            <form action="" id="order-criteria">
                                <ul class="list-group btn col-md-8" id="ui-sortable-list">
                                    <?php
                                    $criteria = array();
                                    while($row = $qry->fetch_assoc()):
                                        $criteria[$row['id']] = $row; 
                                    ?>
                                    <li class="list-group-item text-left">
                                        <span class="btn-group dropright float-right">
                                            <span type="button" class="btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </span>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item edit_criteria" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item delete_criteria" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
                                            </div>
                                        </span>
                                        <i class="fa fa-bars"></i><span style="font-weight: bold;"> <?php echo ucwords($row['criteria']) ?></span>
                                        <input type="hidden" name="criteria_id[]" value="<?php echo $row['id'] ?>">
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </form>
                            <?php else: ?>
                                <center>There's no criteria in the database yet</center>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <button id="nextToQuestionnaire" class="nextButton" disabled>Next</button>
            </div>
        </div>
    </div>
</div>

<style>
    .dropright a:hover {
        color: black !important;
    }
</style>

<script>
    $(document).ready(function() {
        // Enable or disable the "Next" button based on the criteria list
        var criteriaCount = $("#ui-sortable-list").children().length;
        $("#nextToQuestionnaire").prop('disabled', criteriaCount === 0);

        // Handle the click event for the "Next" button
        $("#nextToQuestionnaire").click(function() {
            window.location.href = './index.php?page=semester';
        });

        // Make the criteria list sortable
        $('#ui-sortable-list').sortable();

        // Edit criteria
        $('.edit_criteria').click(function() {
            var id = $(this).attr('data-id');
            var criteria = <?php echo json_encode($criteria) ?>;
            $('#manage-criteria').find("[name='id']").val(criteria[id].id);
            $('#manage-criteria').find("[name='criteria']").val(criteria[id].criteria);
        });

        // Reset form
        $('#manage-criteria').on('reset', function() {
            $(this).find('input:hidden').val('');
        });

        // Delete criteria
        $('.delete_criteria').click(function() {
            _conf("Are you sure to delete this criteria?", "delete_criteria", [$(this).attr('data-id')]);
        });

        // Submit form to save criteria
        $('#manage-criteria').submit(function(e) {
            e.preventDefault();
            start_load();
            $.ajax({
                url: 'ajax.php?action=save_criteria',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Data successfully saved.", "success");
                        setTimeout(function() {
                            location.reload();
                        }, 1750);
                    } else if (resp == 2) {
                        $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Criteria already exists.</div>');
                        end_load();
                    }
                }
            });
        });

        // Submit form to save order
        $('#order-criteria').submit(function(e) {
            e.preventDefault();
            start_load();
            $.ajax({
                url: 'ajax.php?action=save_criteria_order',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Data successfully saved.", "success");
                        setTimeout(function() {
                            location.reload();
                        }, 1750);
                    }
                }
            });
        });
    });

    // Function to delete criteria
    function delete_criteria(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_criteria',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>
