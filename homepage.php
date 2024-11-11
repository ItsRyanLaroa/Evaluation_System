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
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div style="min-height: 400px;">
        <div class="pattern">
            <div class="container">
                <div class="first-row">
                    <div class="left-column">
                        <h1>Your Feedback, <br>Our Progress.</h1>
                        <a href="login.php">Login Here</a>
                    </div>

                    <div class="right-column">
                        <div class="image-column">
                            <img src="images/evaluates.png" alt="Evaluating image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="about-us" id="about-us">
        <div class="about-image">
            <div class="first-alogo">
                <img src="images/questions.png" alt="">
            </div>
        </div>
        <div class="about-details">
            <h1>About App</h1>
            <p>An intuitive evaluation system for gathering valuable feedback, offering clear insights to support teaching growth and a better learning experience.</p>
        </div>
    </div>

    <div class="features">
        <div class="features-details">
            <h1>Features</h1>
            <ul>
                <li><span class="check"></span>Students can evaluate teachers.</li>
                <li><span class="check"></span>Automated generates report.</li>
                <li><span class="check"></span>Accessible accross all devices.</li>
            </ul>
        </div>
        <div class="features-image">
            <img src="images/devices.png" alt="">
        </div>
    </div>


    <footer>
        <div class="footer-pattern">
            <div class="footer-container">
                <div class="footer-logo">
                    <div class="first-flogo">
                        <img src="images/whitefeslogo.png" alt="">
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


const aboutSection = document.querySelector("#about-us");

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            aboutSection.classList.add("animate");
        } else {
            aboutSection.classList.remove("animate");
        }
    });
}, { threshold: 0.5 });


observer.observe(aboutSection);



const featuresDetails = document.querySelector('.features-details');
const featuresImage = document.querySelector('.features-image');


function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return rect.top <= window.innerHeight && rect.bottom >= 0;
}


window.addEventListener('scroll', function() {
    if (isInViewport(featuresDetails)) {
        featuresDetails.classList.add('scrolled');
    }
    if (isInViewport(featuresImage)) {
        featuresImage.classList.add('scrolled');
    }
});


</script> 
</body>
</html>