<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM academic_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
function ordinal_suffix($num){
    $num = $num % 100; // protect against large numbers
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}
?>
<style>
	.card-info:not(.card-outline)>.card-header {
    background-color: #b31b1b;
}

.bg-gradient-secondary {
    background: #B31B1C linear-gradient(182deg, #b31b1b, #dc3545) repeat-x !important;
    color: #fff;
}
.bg-gradient-success {
    background: #dc143c linear-gradient(180deg, #dc143c, #dc143c) repeat-x !important;
    color: #fff;
}

.bg-gradient-success.btn:hover {
    background: #007bff linear-gradient(180deg, #268fff, #007bff) repeat-x !important;
    color: #fff;
}

.card-header {
    background-color: transparent;
    border-bottom: none;
}
    /*.nav-bar {
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
        font-family: tahoma;
    } */

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

    .content-container {
        margin-top: 20px;
        padding: 20px;
    }

	.card-info.card-outline{
    	border-top: 3px solid #dc143c;
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

	.border-info{
		border-color: #dc143c !important;
	}

</style>

<div class="nav-bar"> 
    <a href="./index.php?page=category" class="nav-link nav-category <?php echo (isset($_GET['page']) && $_GET['page'] == 'category') ? 'active' : ''; ?>">Category</a>
    <a href="./index.php?page=semester" class="nav-link nav-semester <?php echo (isset($_GET['page']) && $_GET['page'] == 'semester') ? 'active' : ''; ?>">Semester</a>
    <a href="javascript:void(0)" class="nav-link nav-questionnaire disabled">Questionnaire</a>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div class="card card-info card-primary">
				<div class="card-header">
					<b>Question Form</b>
				</div>
				<div class="card-body">
					<form action="" id="manage-question">
						<input type="hidden" name="academic_id" value="<?php echo isset($id) ? $id : '' ?>">
						<input type="hidden" name="id" value="">
						<div class="form-group">
							<label for="">Category</label>
							<select name="criteria_id" id="criteria_id" class="custom-select custom-select-sm select2">
								<option value=""></option>
							<?php 
								$criteria = $conn->query("SELECT * FROM criteria_list order by abs(order_by) asc ");
								while($row = $criteria->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>"><?php echo $row['criteria'] ?></option>
							<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="">Question</label>
							<textarea name="question" id="question" cols="30" rows="4" class="form-control" required=""><?php echo isset($question) ? $question : '' ?></textarea>
						</div>
					</form>
				</div>
				<div class="card-footer">
					<div class="d-flex justify-content-end w-100">
						<button class="btn btn-sm btn-primary btn-flat bg-gradient-primary mx-1" form="manage-question">Save</button>
						<button class="btn btn-sm btn-flat btn-secondary bg-gradient-secondary mx-1" form="manage-question" type="reset">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card card-outline card-info">
				<div class="card-header">
					<b>Evaluation Questionnaire for Academic: <span style="color: #dc143c; font-weight: bold;"><?php echo $year . ' (' . ordinal_suffix($semester) . ' Semester)'; ?></span></b>
					<div class="card-tools">
						<!-- <button class="btn btn-sm btn-flat btn-primary bg-gradient-primary mx-1" id="eval_restrict" type="button">Evaluation Restriction</button> -->
						<button class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1" form="order-question">Save Order</button>
					</div>
				</div>
				<div class="card-body">
					<fieldset class="border border-info p-2 w-100">
						<h3><span style="font-weight: bold;">Rating Legend</h3></span>
						<p>
							<span style="color: #dc143c; font-weight: bold;">5</span> - Strongly Agree <span style="color: #007bff; font-weight: bold;"> | </span>
							<span style="color: #dc143c; font-weight: bold;">4</span> - Agree <span style="color: #007bff; font-weight: bold;"> | </span>
							<span style="color: #dc143c; font-weight: bold;">3</span> - Uncertain <span style="color: #007bff; font-weight: bold;"> | </span>
							<span style="color: #dc143c; font-weight: bold;">2</span> - Disagree <span style="color: #007bff; font-weight: bold;"> | </span>
							<span style="color: #dc143c; font-weight: bold;">1</span> - Strongly Disagree
						</p>
					</fieldset>
					<form id="order-question">
					<div class="clear-fix mt-2"></div>
					<?php 
							$q_arr = array();
						$criteria = $conn->query("SELECT * FROM criteria_list order by abs(order_by) asc ");
						while($crow = $criteria->fetch_assoc()):
					?>
					<table class="table table-condensed">
						<thead>
							<tr class="bg-gradient-secondary">
								<th colspan="2" class=" p-1"><b><?php echo $crow['criteria'] ?></b></th>
								<th class="text-center">5</th>
								<th class="text-center">4</th>
								<th class="text-center">3</th>
								<th class="text-center">2</th>
								<th class="text-center">1</th>
							</tr>
						</thead>
						<tbody class="tr-sortable">
							<?php 
							$questions = $conn->query("SELECT * FROM question_list where criteria_id = {$crow['id']} and academic_id = $id order by abs(order_by) asc ");
							while($row=$questions->fetch_assoc()):
							$q_arr[$row['id']] = $row;
							?>
							<tr class="bg-white">
								<td class="p-1 text-center" width="5px">
									<span class="btn-group dropright">
									  <span type="button" class="btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									   <i class="fa fa-ellipsis-v"></i>
									  </span>
									  <div class="dropdown-menu">
									     <a class="dropdown-item edit_question" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
					                      <div class="dropdown-divider"></div>
					                     <a class="dropdown-item delete_question" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete  </a>
									  </div>
									</span>
								</td>
								<td class="p-1" width="40%">
									<?php echo $row['question'] ?>
									<input type="hidden" name="qid[]" value="<?php echo $row['id'] ?>">
								</td>
								<?php for($c=0;$c<5;$c++): ?>
								<td class="text-center">
									<div class="icheck-success d-inline">
				                        <input type="radio" name="qid[<?php echo $row['id'] ?>][]" id="qradio<?php echo $row['id'].'_'.$c ?>">
				                        <label for="qradio<?php echo $row['id'].'_'.$c ?>">
				                        </label>
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
	$(document).ready(function(){
     $('.select2').select2({
	    placeholder:"Please select here",
	    width: "100%"
	  });
     })
	$('.edit_question').click(function(){
		var id = $(this).attr('data-id')
		var question = <?php echo json_encode($q_arr) ?>;
		$('#manage-question').find("[name='id']").val(question[id].id)
		$('#manage-question').find("[name='question']").val(question[id].question)
		$('#manage-question').find("[name='criteria_id']").val(question[id].criteria_id).trigger('change')
	})
	$('.delete_question').click(function(){
		_conf("Are you sure to delete this question?","delete_question",[$(this).attr('data-id')])
		})
	$('#eval_restrict').click(function(){
		uni_modal("Manage Evaluation Restrictions","<?php echo $_SESSION['login_view_folder'] ?>manage_restriction.php?id=<?php echo $id ?>","mid-large")
	})
	$('.tr-sortable').sortable()
	$('#manage-question').on('reset',function(){
			$(this).find('input[name="id"]').val('')
			$('#manage-question').find("[name='criteria_id']").val('').trigger('change')
		})
    $('#manage-question').submit(function(e){
    	e.preventDefault()
    	start_load()
    	if($('#question').val() == ''){
    		alert_toast("Please fill the question description first",'error');
    		end_load();
    		return false;
    	}
    	$.ajax({
    		url:'ajax.php?action=save_question',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
    })
    $('#order-question').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_question_order',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Order successfully saved',"success");
					end_load()
				}
			}
    	})
    })
    function delete_question($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_question',
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
</script>