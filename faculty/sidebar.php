<style>
    .main-sidebar {
      background-color:#9b0a1e ;
    }
    .layout-navbar-fixed .wrapper .sidebar-dark-primary .brand-link:not([class*=navbar]) {
      background-color: #9b0a1e ;
    }
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, 
    .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
      background-color: #95d2ec ;
      color: #000;
      font-weight: bold;
    }
    .sidebar-collapse .brand {
      width: 25px;
      height: auto;
    }
    .layout-navbar-fixed.layout-fixed .wrapper .sidebar {
      margin-top: calc(9.5rem + 1px);
    }

    .layout-navbar-fixed.layout-fixed .wrapper .sidebar-collapse .brand {
      width: 25px;
      height: auto;
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

    .list-group-item.active {
      z-index: 2;
      color: #fff;
      background-color: #dc143c;
    }

</style>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
        <a href="./" class="brand-link">
            <img src="images/feslogo.png" class="brand">
            <img src="images/file.png" class="brand">
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
                    <a href="./index.php?page=result" class="nav-link nav-result">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>Evaluation Result</p>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a href="./index.php?page=evaluation_record" class="nav-link nav-assessment">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Evaluation Record</p>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a href="./index.php?page=subject" class="nav-link nav-subject">
                        <i class="nav-icon fas fa-book"></i> <!-- New icon for Subject -->
                        <p>Subject</p>
                    </a>
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

</script>
