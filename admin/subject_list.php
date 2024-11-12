<style>
/* Modern table styling */
table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {
    border-bottom-width: 0;
    border: none;
    color: #333;
    font-weight: 500; /* Add slight boldness */
}

/* Styled table */
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

/* Red table header */
.card-primary.card-outline {
    border-top: none;
}

thead th {
    background-color: #9b0a1e;
    color: #f3f3f3;
    font-weight: bold;
}

/* Card header styling */
.card-header {
    background-color: transparent;
    border-bottom: none;
    padding: .75rem 1.25rem;
    position: relative;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
}

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

.card-tools i{
    color: #dc143c;
    font-weight: bold;
}

/* Hover effect for rows */
tbody tr:hover {
    background-color: #95d2ec;
}

.btn-primary{
    color: #007bff;
}


</style>


<?php include 'db_connect.php' ?>
<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary new_subject" href="javascript:void(0)">
                    <i class="fa fa-plus"></i> <span style="color: #dc143c; font-weight: bold;">Add New</span>
                </a>
            </div>
        </div>
        <div class="card-body">
        <table class="table tabe-hover table-bordered styled-table" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="30%">
                    <col width="40%">
                    <col width="15%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Code</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Action</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM subject_list ORDER BY subject ASC ");
                    while($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><b><?php echo $row['code'] ?></b></td>
                        <td><b><?php echo $row['subject'] ?></b></td>
                        <td><b><?php echo $row['description'] ?></b></td>
                        <td class="text-center">
                        <div class="btn-group">
                                    <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-primary btn-flat manage_subject">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-flat delete_subject" data-id="<?php echo $row['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <a class="dropdown-item class_student manage-subject-btn" href="index.php?page=subject_teacher&id=<?php echo $row['id'] ?>"><i class="fa fa-eye"></i></a>
                                    </a>
                                </div>
                        </td>
                    </tr>	
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.new_subject').click(function(){
            uni_modal("New subject", "<?php echo $_SESSION['login_view_folder'] ?>manage_subject.php")
        })
        $('.manage_subject').click(function(){
            uni_modal("Manage subject", "<?php echo $_SESSION['login_view_folder'] ?>manage_subject.php?id=" + $(this).attr('data-id'))
        })
        $('.delete_subject').click(function(){
            _conf("Are you sure to delete this subject?", "delete_subject", [$(this).attr('data-id')])
        })
        $('#list').dataTable()
    })

    function delete_subject($id){
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_subject',
            method: 'POST',
            data: {id: $id},
            success: function(resp){
                if(resp == 1){
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function(){
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>
