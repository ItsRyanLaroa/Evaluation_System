<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="path/to/your/styles.css">
  <style>
    .main-sidebar {
      background-color: #9b0a1e ;
    }
    .layout-navbar-fixed .wrapper .sidebar-dark-primary .brand-link:not([class*=navbar]) {
      background-color: #9b0a1e ;
    }
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, 
    .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
      background-color: #95d2ec;
      color: #000;
      font-weight: bold;
    }
    .sidebar {
      padding: 45px 8px;
    }

    .sidebar-collapse .brand {
      width: 25px;
      height: auto;
    }
    .layout-navbar-fixed.layout-fixed .wrapper .sidebar {
    margin-top: calc(6rem + 1px);
    }

   
    .brand-link {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
    }

    .brand {
      width: 100px;
      height: auto;
    }

    
    
  </style>
  <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css">
  <script src="path/to/jquery.min.js"></script>
</head>
<body>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
      <a href="./" class="brand-link">
      <img src="images/feslogo.png" class="brand">
      <img src="images/file.png"  class="brand">
      </a>
    </div>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=subject_list" class="nav-link nav-subject_list">
              <i class="nav-icon fas fa-book"></i>
              <p>Subjects</p>
            </a>
          </li> 
          <li class="nav-item dropdown">
            <a href="./index.php?page=class_list" class="nav-link nav-class_list">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>Class list</p>
            </a>
          </li> 
          <li class="nav-item dropdown">
            <a href="./index.php?page=academic_list" class="nav-link nav-academic_list">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Academic Year</p>
            </a>
          </li> 
          <li class="nav-item dropdown">
            <a href="./index.php?page=category" class="nav-link nav-criteria_list">
              <i class="nav-icon fas fa-tasks"></i>
              <p>Category</p>
            </a>
          </li> 
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_faculty">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Faculties
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_faculty" class="nav-link nav-new_faculty tree-item">
                  <i class="fas fa-user-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=faculty_list" class="nav-link nav-faculty_list tree-item">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_student">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>
                Students
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_student" class="nav-link nav-new_student tree-item">
                  <i class="fas fa-user-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=student_list" class="nav-link nav-student_list tree-item">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- <li class="nav-item">
            <a href="#" class="nav-link nav-edit_staff">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                Staff
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_staff" class="nav-link nav-new_staff tree-item">
                  <i class="fas fa-user-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=staff_list" class="nav-link nav-staff_list tree-item">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li> -->
          <li class="nav-item dropdown">
            <a href="./index.php?page=report" class="nav-link nav-report">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Evaluation Report</p>
            </a>
          </li>
          <!-- <li class="nav-item dropdown">
            <a href="./index.php?page=staff_report" class="nav-link nav-staff_report">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>Staff Report</p>
            </a>
          </li> -->
       
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>
                Admin
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                  <i class="fas fa-user-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <script>
    $(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
      var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s != '')
        page = page + '_' + s;
      if($('.nav-link.nav-' + page).length > 0){
        $('.nav-link.nav-' + page).addClass('active');
        if($('.nav-link.nav-' + page).hasClass('tree-item') == true){
          $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active');
          $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open');
        }
        if($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-' + page).parent().addClass('menu-open');
        }
      }
    });

    $(document).ready(function(){
  var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
  
  if(s != '') page += '_' + s;

  
  if($('.nav-link.nav-' + page).length > 0){
    $('.nav-link.nav-' + page).addClass('active');
    if($('.nav-link.nav-' + page).hasClass('tree-item')){
      $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active');
      $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open');
    }
    if($('.nav-link.nav-' + page).hasClass('nav-is-tree')){
      $('.nav-link.nav-' + page).parent().addClass('menu-open');
    }
  }

  
  $('.main-sidebar').on('collapse', function() {
    $(this).toggleClass('sidebar-collapse');
  });

  $('.main-sidebar').on('expand', function() {
    $(this).removeClass('sidebar-collapse');
  });
});


  </script>
</body>
</html>
