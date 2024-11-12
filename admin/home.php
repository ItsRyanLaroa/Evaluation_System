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
      border-left-color: #9b0a1e;
    }

    .icon i{
      color: #9b0a1e;
      padding: 10px 10px 10px 0;
    }
    .inner h3{
      color: #9b0a1e;
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
      <div class="card-body"><h3>Welcome, <b><?php echo $_SESSION['login_name']?>!</b></h3>
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
                <h3><?php echo $conn->query("SELECT * FROM faculty_list ")->num_rows; ?></h3>

                <p>Total Faculties</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-friends"></i>
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
                <h3><?php echo $conn->query("SELECT * FROM users")->num_rows; ?></h3>

                <p>Total Users</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-plus"></i>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM class_list")->num_rows; ?></h3>

                <p>Total Classes</p>
              </div>
              <div class="icon">
                <i class="fa fa-list-alt"></i>
              </div>
            </div>
          </div>
      </div>
