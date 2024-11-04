<?php include('db_connect.php'); ?>
<?php 
function ordinal_suffix1($num){
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
$astat = array("Not Yet Started","On-going","Closed");
?>
<style>
  h3{
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  }

  .card{
      box-shadow: none;
      background: transparent;
    }

  .callout.callout-info{
    border-left-color: #dc143c;
  }

  .icon i{
      color: #dc143c;
      padding: 10px 10px 10px 0;
  }

  .inner h3{
    color: #dc143c;
  }

  .inner p{
    font-weight: bold;
    color: black;
  }

  .small-box>.inner{
      background-color: white;
      padding: 20px;
    }

    .col-12.col-sm-6.col-md-4{
      box-shadow: none;
    }

    .row{
      margin: 20px;
    }
</style>
<div class="col-12">
    <div class="card">
      <div class="card-body">
        <h3>Welcome, <b><?php echo $_SESSION['login_name'] ?>!</h3></b>
        <br>
        <div class="col-md-5">
          <div class="callout callout-info">
            <h5><b>Academic Year: <span style="color: #dc143c;"><?php echo $_SESSION['academic']['year'].' ('.ordinal_suffix1($_SESSION['academic']['semester']).' Semester)'; ?></span></b></h5>
            <h6><b>Evaluation Status: <span style="color: #dc143c;"><?php echo $astat[$_SESSION['academic']['status']] ?></span></b></h6>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="row">
  <div class="col-12 col-sm-6 col-md-4">
    <div class="small-box bg-light shadow-sm border">
      <div class="inner">
        <!-- Get the total unique evaluated students -->
        <h3><?php 
          $evaluated_students_query = "SELECT COUNT(DISTINCT student_id) AS total_evaluated FROM evaluation_list"; 
          $result = $conn->query($evaluated_students_query);
          $row = $result->fetch_assoc();
          echo $row['total_evaluated']; 
        ?></h3>
        <p>Total Students Who Evaluated</p>
      </div>
      <div class="icon">
        <i class="fa fa-clipboard-check"></i> <!-- Change icon as needed -->
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-4">
    <div class="small-box bg-light shadow-sm border">
      <div class="inner">
        <h3><?php echo $conn->query("SELECT * FROM student_list")->num_rows; ?></h3>
        <p>Total Students</p>
      </div>
      <div class="icon">
        <i class="fa fa-users"></i>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-4">
    <div class="small-box bg-light shadow-sm border">
      <div class="inner">
        <!-- Get the total number of subjects for the logged-in user -->
        <h3>
        <?php 
          // Assuming the user is identified by a session variable or similar
          $faculty_id = $_SESSION['login_id']; // Adjust this according to your session setup
          $total_subjects_query = "
            SELECT COUNT(DISTINCT sl.id) AS total_subjects
            FROM class_list cl
            JOIN subject_list sl ON cl.subject_id = sl.id
            WHERE cl.teacher_id = ?"; // Use the correct field that links to the user (faculty_id)
          
          $stmt = $conn->prepare($total_subjects_query);
          $stmt->bind_param("i", $faculty_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo $row['total_subjects']; 
        ?>
        </h3>
        <p>Total Subjects</p>
      </div>
      <div class="icon">
        <i class="fa fa-book"></i> <!-- Icon for subjects -->
      </div>
    </div>
  </div>
</div>
