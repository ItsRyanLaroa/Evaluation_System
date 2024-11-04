<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Evaluation System</title>
    <link rel="stylesheet" href="css/about-us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
</head>
<body>
<header>
        <div class="container">
            <div class="navbar-header">
                <a href="index.php" class="navbar-logo">
                    <img src="images/feslogo.png" width="70" height="70">
                </a>
                <div class="logo-text">
                    <span class="logo_title">
                        Faculty Evaluation System
                    </span>
                    <span class="logo_subtitle">
                        St. Cecilia's College, Cebu - Inc.
                    </span>
                </div>
                <ul class="nav-navbar">
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="about-us.php">About</a>
                    </li>
                    <li>
                        <a href="register.php">Sign up</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div style="min-height:400px; padding-bottom:10px;">
        <div class="pattern">
            <div class="container">
                <div class="row">
                    <div class="center-details">
                        <img src="images/feslogo.png" style="margin-top: 20px;" width="100" height="100">
                        <div class="overview">
                            <h2 style="font-size: 48px; font-weight: bold; padding-bottom: 30px;">What is Faculty Evaluation System?</h2>
                            <p style="font-size: 25px;">The Faculty Evaluation System is a web application designed to automate the evaluation of faculty performance.</p>
                            <p style="font-size: 25px;">The system provides an effective and timely way to monitor faculty performance, facilitating quick intervention when needed.</p>
                            <p style="font-size: 25px;">The confidentiality of the information is fully upheld.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="check-list">
        <div class="first-row">
            <i class="fa-solid fa-circle-dot"></i>
            <div class="text-columns">
                <div class="column1">
                    <h3>Students</h3>
                </div>
                <div class="column2">
                    <p>Students can assess the teachers of the subjects they are taking this semester.</p>
                </div>
            </div>
        </div>
        <div class="second-row">
            <i class="fa-solid fa-circle-dot"></i>
            <div class="text-columns">
                <div class="column1">
                    <h3>Faculty</h3>
                </div>
                <div class="column2">
                    <p>Faculty teachers can evaluate their colleagues within their own department.</p>
                </div>
            </div>
        </div>
        <div class="third-row">
            <i class="fa-solid fa-circle-dot"></i>
            <div class="text-columns">
                <div class="column1">
                    <h3>Teachers</h3>
                </div>
                <div class="column2">
                    <p>Teachers can evaluate their dean.</p>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer-pattern">
            <div class="footer-container">
                <div class="footer-text">
                    <h6>Faculty Evaluation System. All rights reserved.</h6>
                </div>
            </div>
        </div>
    </footer>

    <script type="text/javascript">
    
    var counter = 2;
    setInterval(function(){
        document.getElementById('radio' + counter).checked = true;
        counter++;
        if(counter > 3){
            counter = 1;
        }
    }, 5000);

</script> 
</body>
</html>