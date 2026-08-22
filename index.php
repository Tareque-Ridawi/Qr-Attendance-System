<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Qr Code Based Attendance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        nav li ul:nth-of-type(1){
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
                        QR Code Based
                    </div>
                    <div class="hero-2">
                        Attendance System
                    </div>
                </div>
                <div class="sc-hero">
                    An anti-proxy easy technology.
                </div>
            </div>
        </div>
        <div class="abt-prj">
            <h2 class="animateF">How does this work?</h2>
            <div class="abt-prj-det">
                <img src="./assets/f-1.png" alt="featured-image 1" class="animateR">
                <p class="des animateL">The student scans the QR code provided by the teacher using a compatible device, which grants them access to their attendance record. Additionally, the system includes a manual entry feature, allowing the teacher to add students to the attendance list in cases where scanning is not feasible due to technical or other issues. This ensures a seamless and inclusive attendance tracking process.</p>
            </div>

            <h2 class="animateF2">How is this anti-proxy?</h2>
            <div class="abt-prj-det">
                <img src="./assets/f-2.png" alt="featured-image 1" class="animateR2">
                <p class="des animateL2">The QR code is designed to refresh every 10 seconds, preventing students from reusing it for proxy attendance. Additionally, the system logs and tracks IP addresses to ensure that the same device cannot reuse the code within a 15-second window to mark attendance for another student. This dual-layer security mechanism effectively mitigates the risk of proxy attendance and ensures the integrity of the attendance tracking process.</p>
            </div>

            <h2 class="animateF3">What Data is Preserved?</h2>
            <div class="abt-prj-det">
                <img src="./assets/f-3.png" alt="featured-image 1" class="animateR3">
                <p class="des animateL3">The system saves the date and time when students scan the QR code for attendance. All the data is stored and can be viewed in one place as a list. The information stays saved unless the teacher deletes it permanently. Even when deleted, the data stays in the trash for 30 days before it is removed completely.</p>
            </div>

            <h2 class="animateF4">Can Student See History?</h2>
            <div class="abt-prj-det">
                <img src="./assets/f-4.png" alt="featured-image 1" class="animateR4">
                <p class="des animateL4">Yes, students can access and view their own attendance history through a personalized portal within the system. This feature allows students to track their attendance over time, ensuring transparency and providing them with an easy way to stay informed about their attendance status. However, students can only view their own data and not the attendance records of other students.</p>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.js" integrity="sha512-ZKNVEa7gi0Dz4Rq9jXcySgcPiK+5f01CqW+ZoKLLKr9VMXuCsw3RjWiv8ZpIOa0hxO79np7Ec8DDWALM0bDOaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="animation.js"></script>
</body>
</html>