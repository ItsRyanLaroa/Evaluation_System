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
 

    <div class="content-container">
        <?php 
        if (isset($_GET['page']) && file_exists($_GET['page'] . ".php")) {
            include $_GET['page'] . ".php";
        } else {
            include "semester.php"; // Default content
        }
        ?>
    </div>