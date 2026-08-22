<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Frequently Asked Questions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .hero{
            background-image: url("./assets/faq.png");
        }
        nav li ul:nth-of-type(3){
            border-bottom: 4px solid var(--theme-2);
        }
        nav li.nav-btn ul{
            border-bottom: none;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <div class="hero">
            <div class="hero-img-logo-cont animateHB">
                <img src="./assets/logo.png" alt="Main Logo">
            </div>
            <div class="hero-text-cont animateRB">
                <div class="main-hero">
                    <div class="hero-1">
                    Frequently Asked Questions
                    </div>
                </div>
                <div class="sc-hero">
                    Get the questions answered
                </div>
            </div>
        </div>
        
        <div class="faq-con animateR">
            <div class="faq-item">
                <div class="faq-ques">What is a QR code-based attendance system?</div>
                <div class="faq-ans">
                A QR code-based attendance system is a digital solution that uses unique QR codes to track and record attendance. Students scan the QR code displayed during a class or event, and their attendance is automatically logged into the system.
                </div>
            </div>
        </div>

        <div class="faq-con animateR2">
            <div class="faq-item">
                <div class="faq-ques"> How does the system work?</div>
                <div class="faq-ans">
                    The instructor generates a unique QR code for each class or session which renews every 15 seconds. <br>
                    Students scan the QR code using their smartphones or devices. <br>
                    The system verifies the student’s identity and records their attendance in real-time. <br>
                </div>
            </div>
        </div>

        <div class="faq-con animateR3">
            <div class="faq-item">
                <div class="faq-ques">Is the system secure?</div>
                <div class="faq-ans">
                    QR codes can be time-bound or expire after a single use.<br>
                    Students must log in or verify their identity before attendance is recorded. <br>
                </div>
            </div>
        </div>

        <div class="faq-con animateR4">
            <div class="faq-item">
                <div class="faq-ques">Can I use the system for multiple classes or events?</div>
                <div class="faq-ans">
                    Yes, you can create and manage multiple classes or events within the system. Each class will have its own unique QR code and attendance records.
                </div>
            </div>
        </div>

        <div class="faq-con animateR5">
            <div class="faq-item">
                <div class="faq-ques">How do I add students to a class?</div>
                <div class="faq-ans">
                    You can add students: <br>
                    Manually by entering their ID <br>
                </div>
            </div>
        </div>

        <div class="faq-con animateR6">
            <div class="faq-item">
                <div class="faq-ques">What happens if a student forgets their phone or cannot scan the QR code?</div>
                <div class="faq-ans">
                    Instructors can manually mark attendance for students who are unable to scan the QR code. This can be done through the system’s dashboard.
                </div>
            </div>
        </div>

        <div class="faq-con animateR7">
            <div class="faq-item">
                <div class="faq-ques">Can students scan the QR code outside the classroom? </div>
                <div class="faq-ans">
                    (Beta) To prevent misuse, the system can be configured with geolocation checks, ensuring students are physically present in the classroom or event venue.
                </div>
            </div>
        </div>

        <div class="faq-con animateR8">
            <div class="faq-item">
                <div class="faq-ques">Can I edit attendance records?</div>
                <div class="faq-ans">
                    Yes, instructors can edit attendance records before finalizing them. Once finalized, changes require admin approval.
                </div>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.js" integrity="sha512-ZKNVEa7gi0Dz4Rq9jXcySgcPiK+5f01CqW+ZoKLLKr9VMXuCsw3RjWiv8ZpIOa0hxO79np7Ec8DDWALM0bDOaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="animation.js"></script>
</body>
</html>