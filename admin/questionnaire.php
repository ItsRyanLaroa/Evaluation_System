	<?php include'db_connect.php' ?>
	<style>
        .nav-bar {
            display: flex;
            justify-content: space-around;
            background: #f4f4f4;
            padding: 10px 0;
        }

        .nav-bar a {
            text-decoration: none;
            padding: 10px 15px;
            color: #333;
        }

        .nav-bar a.active {
            font-weight: bold;
            border-bottom: 2px solid blue;
        }

        .content-container {
            margin-top: 20px;
            padding: 20px;
        }
    </style>
<div class="nav-bar">
        <a href="./index.php?page=semester" class="nav-link nav-semester <?php echo (isset($_GET['page']) && $_GET['page'] == 'semester') ? 'active' : ''; ?>">Semester</a>
        <a href="./index.php?page=category" class="nav-link nav-category <?php echo (isset($_GET['page']) && $_GET['page'] == 'category') ? 'active' : ''; ?>">Category</a>
        <a href="./index.php?page=questionnaire" class="nav-link nav-questionnaire <?php echo (isset($_GET['page']) && $_GET['page'] == 'questionnaire') ? 'active' : ''; ?>">Questionnaire</a>
    </div>
	<div class="col-lg-12">
		<div class="card card-outline card-primary">
			<div class="card-header">
				<div class="card-tools">
					<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_academic" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
				</div>
			</div>
			<div class="card-body">
			<table class="table tabe-hover table-bordered styled-table" id="list">
					<colgroup>
						<col width="5%">
						<col width="35%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
					</colgroup>
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Academic Year</th>
							<th>Semester</th>
							<th>Questions</th>
							<th>Answered</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$qry = $conn->query("SELECT * FROM academic_list order by abs(year) desc,abs(semester) desc ");
						while($row= $qry->fetch_assoc()):
							$questions = $conn->query("SELECT * FROM question_list where academic_id ={$row['id']} ")->num_rows;
							$answers = $conn->query("SELECT * FROM evaluation_list where academic_id ={$row['id']} ")->num_rows;
						?>
						<tr>
							<th class="text-center"><?php echo $i++ ?></th>
							<td><b><?php echo $row['year'] ?></b></td>
							<td><b><?php echo $row['semester'] ?></b></td>
							<td class="text-center"><b><?php echo number_format($questions) ?></b></td>
							<td class="text-center"><b><?php echo number_format($answers) ?></b></td>
							<td class="text-center">
								<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
								Action
								</button>
								<div class="dropdown-menu" style="">
								<a class="dropdown-item manage_questionnaire" href="index.php?page=manage_questionnaire&id=<?php echo $row['id'] ?>">Manage</a>
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
    background-color: #dc143c;
    color: white;
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

/* Button styles */
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

/* Hover effect for rows */
tbody tr:hover {
    background-color: #f1f1f1;
}

</style>

	<script>
		$(document).ready(function(){
			$('.new_academic').click(function(){
				uni_modal("New academic","<?php echo $_SESSION['login_view_folder'] ?>manage_academic.php")
			})
			$('.manage_academic').click(function(){
				uni_modal("Manage academic","<?php echo $_SESSION['login_view_folder'] ?>manage_academic.php?id="+$(this).attr('data-id'))
			})
			$('.delete_academic').click(function(){
			_conf("Are you sure to delete this academic?","delete_academic",[$(this).attr('data-id')])
			})
			$('.make_default').click(function(){
			_conf("Are you sure to make this academic year as the system default?","make_default",[$(this).attr('data-id')])
			})
			$('#list').dataTable()
		})
		function delete_academic($id){
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_academic',
				method:'POST',
				data:{id:$id},
				success:function(resp){
					if(resp==1){
						alert_toast("Data successfully deleted",'success')
						setTimeout(function(){
							location.reload()
						},1500)

					}
				}
			})
		}
		function make_default($id){
			start_load()
			$.ajax({
				url:'ajax.php?action=make_default',
				method:'POST',
				data:{id:$id},
				success:function(resp){
					if(resp==1){
						alert_toast("Dafaut Academic Year Updated",'success')
						setTimeout(function(){
							location.reload()
						},1500)
					}
				}
			})
		}
	</script>