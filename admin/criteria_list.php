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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester Page</title>
    <link rel="stylesheet" href="Css/category.css">
    <style>
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

        .buttonContainer {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .buttonContainer button {
            padding: 10px 20px;
            cursor: pointer;
        }

        .tabPanel {
            display: none;
        }

        .tabPanel.active {
            display: block;
        }

        .nextButton {
            margin-top: 20px;
            padding: 10px 20px;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 20px;
        }

        select {
            padding: 10px;
            width: 300px;
            font-size: 16px;
        }

        .dropright a:hover {
            color: black !important;
        }
    </style>
</head>

<body>
<?php include 'top.php'; ?>
    <div class="container">
     
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const semesterButton = document.getElementById("semesterButton");
            const categoryButton = document.getElementById("categoryButton");
            const questionnaireButton = document.getElementById("questionnaireButton");

            const semesterPanel = document.getElementById("semesterPanel");
            const categoryPanel = document.getElementById("categoryPanel");
            const questionnairePanel = document.getElementById("questionnairePanel");

            const nextToCategoryButton = document.getElementById("nextToCategory");
            const nextToQuestionnaireButton = document.getElementById("nextToQuestionnaire");

            const semesterDropdown = document.getElementById("semesterDropdown");

            const hideAllPanels = () => {
                semesterPanel.style.display = "none";
                categoryPanel.style.display = "none";
                questionnairePanel.style.display = "none";
            };

            semesterButton.addEventListener("click", () => {
                hideAllPanels();
                semesterPanel.style.display = "block";
                semesterButton.classList.add("active");
                categoryButton.classList.remove("active");
                questionnaireButton.classList.remove("active");
            });

            categoryButton.addEventListener("click", () => {
                hideAllPanels();
                categoryPanel.style.display = "block";
                categoryButton.classList.add("active");
                semesterButton.classList.remove("active");
                questionnaireButton.classList.remove("active");
            });

            questionnaireButton.addEventListener("click", () => {
                hideAllPanels();
                questionnairePanel.style.display = "block";
                questionnaireButton.classList.add("active");
                semesterButton.classList.remove("active");
                categoryButton.classList.remove("active");
            });

            nextToCategoryButton.addEventListener("click", () => {
                hideAllPanels();
                categoryPanel.style.display = "block";
                categoryButton.classList.add("active");
                semesterButton.classList.remove("active");
                questionnaireButton.classList.remove("active");
            });

            nextToQuestionnaireButton.addEventListener("click", () => {
                hideAllPanels();
                questionnairePanel.style.display = "block";
                questionnaireButton.classList.add("active");
                semesterButton.classList.remove("active");
                categoryButton.classList.remove("active");
            });

            semesterDropdown.addEventListener("change", () => {
                if (semesterDropdown.value !== "") {
                    nextToCategoryButton.disabled = false;
                } else {
                    nextToCategoryButton.disabled = true;
                }
            });

            hideAllPanels();
            semesterPanel.style.display = "block";
        });
        $(document).ready(function(){
		$('#ui-sortable-list').sortable()
		$('.edit_criteria').click(function(){
				var id = $(this).attr('data-id')
				var criteria = <?php echo json_encode($criteria) ?>;
				$('#manage-criteria').find("[name='id']").val(criteria[id].id)
				$('#manage-criteria').find("[name='criteria']").val(criteria[id].criteria)

		})
		$('#manage-criteria').on('reset',function(){
			$(this).find('input:hidden').val('')
		})
		$('.delete_criteria').click(function(){
		_conf("Are you sure to delete this criteria?","delete_criteria",[$(this).attr('data-id')])
		})
		$('.make_default').click(function(){
		_conf("Are you sure to make this criteria year as the system default?","make_default",[$(this).attr('data-id')])
		})

		$('#manage-criteria').submit(function(e){
			e.preventDefault();
			start_load()
			$('#msg').html('')
			$.ajax({
				url:'ajax.php?action=save_criteria',
				method:'POST',
				data:$(this).serialize(),
				success:function(resp){
					if(resp == 1){
						alert_toast("Data successfully saved.","success");
						setTimeout(function(){
							location.reload()	
						},1750)
					}else if(resp == 2){
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Criteria already exist.</div>')
						end_load()
					}
				}
			})
		})
		$('#order-criteria').submit(function(e){
			e.preventDefault();
			start_load()
			$.ajax({
				url:'ajax.php?action=save_criteria_order',
				method:'POST',
				data:$(this).serialize(),
				success:function(resp){
					if(resp == 1){
						alert_toast("Data successfully saved.","success");
						setTimeout(function(){
							location.reload()	
						},1750)
				}
				}
			})
		})

	})
	function delete_criteria($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_criteria',
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
					alert_toast("Dafaut criteria Year Updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
    </script>
</body>
</html>
