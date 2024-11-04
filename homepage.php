<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Evaluation System</title>
    <link rel="stylesheet" href="Css/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar-header">
                <a href="homepage.php" class="navbar-logo">
                    <img src="images/feslogo.png" width="70" height="70">
                </a>
                <div class="logo-text">
                    <span class="logo_title">Faculty Evaluation System</span>
                    <span class="logo_subtitle">St. Cecilia's College, Cebu - Inc.</span>
                </div>
                <div class="navbar-toggler" type="button" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars"></i>
                </div>
                <div class="nav-navbar" id="navbarNav">
                    <ul>
                        <li><a href="homepage.php">Home</a></li>
                        <li><a href="#about-us">About</a></li>
                        <li><a href="#contact-details">Contacts</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div style="min-height:400px; padding-bottom:10px;">
        <div class="pattern">
            <div class="container">
                <div class="first-row">
                    <div class="row-title">
                        <h1>FACULTY EVALUATION SYSTEM</h1>
                        <p>Students and teachers can now evaluate through this tool.</p>
                    </div>
                </div>
                
                <div class="second-row">
                    <div class="image-column">
                        <div class="left-img">
                            <img src="images/studentevaluate.png">
                        </div>
                    </div>

                    <div class="text-column">
                        <div class="right-content">
                            <h2>Student evaluating a teacher</h2>
                            <p>Students can evaluate faculty of subjects enrolled in a semester. This feedback allows students to express their opinions on teaching effectiveness, course content, and overall learning experiences. By participating in these evaluations, students contribute to improving educational quality and hold faculty accountable for their performance.</p>
                        </div>
                    </div>
                </div>

                <div class="third-row">
                    <div class="text-column">
                        <div class="right-content">
                            <h2>Faculty evaluating their colleagues</h2>
                            <p>Faculty can evaluate their colleagues in their respective departments. This peer evaluation process fosters a collaborative environment where educators can share constructive feedback on teaching methods, curriculum development, and research contributions. By engaging in these evaluations, faculty members promote professional growth and accountability within the department.</p>
                        </div>
                    </div>

                    <div class="image-column">
                        <div class="left-img">
                            <img src="images/teacher.png">
                        </div>
                    </div>
                </div>

                <div class="second-row">
                    <div class="image-column">
                        <div class="left-img">
                            <img src="images/dean.png">
                        </div>
                    </div>

                    <div class="text-column">
                        <div class="right-content">
                            <h2>Faculty evaluating their dean</h2>
                            <p>Faculty can evaluate their dean's department. This evaluation process allows faculty members to provide feedback on departmental leadership, communication, and support for academic initiatives. By sharing their insights, faculty contribute to a culture of transparency and accountability, ensuring that the department aligns with the needs and goals of both faculty and students.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="about-us" id="about-us">
        <div class="about-logo">
            <div class="first-alogo">
                <img src="images/feslogo.png" alt="">
            </div>
            <div class="second-alogo">
                <img src="images/file.png" alt="">
            </div>
        </div>
        <div class="about-details">
            <h1>What is Faculty Evaluation System?</h1>
            <p>The Faculty Evaluation System is a web application designed to automate the evaluation of faculty performance. The system provides an effective and timely way to monitor faculty performance, facilitating quick intervention when needed. The confidentiality of the information is fully upheld.</p>
        </div>
    </div>
    <footer>
        <div class="footer-pattern">
            <div class="footer-container">
                <div class="footer-logo">
                    <div class="first-flogo">
                        <img src="images/feslogo.png" alt="">
                    </div>
                    <div class="second-flogo">
                        <img src="images/file.png" alt="">
                    </div>
                    <div class="footer-text">
                        <h6>Faculty Evaluation System</h6>
                        <p>All rights reserved.</p>
                    </div>
                </div>
                <div class="footer-details" id="contact-details">
                    <div class="vertical-line-1">
                        <div class="footer-contacts">
                            <h1>Contacts</h1>
                            <p>info@stcecilia.edu.ph</p>
                            <p>0919 072 7109</p>
                        </div>
                    </div>

                    <div class="vertical-line-2">
                        <div class="footer-follow">
                            <h1>Follow</h1>
                            <i class="fa-brands fa-facebook"><p>Facebook</p></i>
                            <i class="fa-brands fa-twitter"><p>Twitter</p></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script type="text/javascript">
    /*
    var counter = 2;
    setInterval(function(){
        document.getElementById('radio' + counter).checked = true;
        counter++;
        if(counter > 3){
            counter = 1;
        }
    }, 5000); */

    const toggler = document.querySelector('.navbar-toggler');
    const navbar = document.getElementById('navbarNav');

    toggler.addEventListener('click', () => {
        navbar.classList.toggle('show');
    });

    $('a[href^="#"]').on('click', function(event) {
        var target = $($(this).attr('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 1000);
        }
    });

    $(document).ready(function() {

    $('.row-title').addClass('animate');

    $('a[href="homepage.php"]').on('click', function(event) {
        $('.row-title').removeClass('animate');
        setTimeout(function() {
            $('.row-title').addClass('animate');
        }, 100);
    });
});

$(document).ready(function() {

    $('.row-title').addClass('animate');
    $('.image-column').addClass('animate');
    $('.text-column').addClass('animate');

    
    $('a[href="homepage.php"]').on('click', function(event) {
        $('.row-title').removeClass('animate');
        $('.image-column').removeClass('animate');
        $('.text-column').removeClass('animate');

        setTimeout(function() {
            $('.row-title').addClass('animate');
            $('.image-column').addClass('animate');
            $('.text-column').addClass('animate');
        }, 100);
    });
});


</script> 
</body>
</html>