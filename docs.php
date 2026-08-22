<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Docs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .hero{
            background-image: url("./assets/docs-hero-2.png");
        }
        nav li ul:nth-of-type(2){
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
                        Documentation - Full Guide
                    </div>
                </div>
                <div class="sc-hero">
                    Read Docs To Take Full Advantage
                </div>
            </div>
        </div>
        
        <div class="docs-sec">
            <div class="docs-card">
                <div class="doc-step animateF">Step 1</div>
                <div class="docs-info">
                    <img class="step-img animateR" src="./assets/docs-1.png" alt="">
                    <div class="step-text-cont animateL">
                        <p class="step-title">Create an Account</p>
                        <p class="step-des">
                            <ul>
                                <li>To begin using the QR code-based attendance system, you’ll need to create an account</li>
                                <li>Visit the system’s registration page and fill in your details</li>
                                <li>Log in to your account and complete your profile setup </li>
                                <li>Ensure your role (e.g., instructor, administrator) is correctly assigned for proper access permissions.</li>
                            </ul>
                        </p>
                    </div>
                </div>
            </div>

            <div class="docs-card">
                <div class="doc-step animateF2">Step 2</div>
                <div class="docs-info">
                <img class="step-img animateR2" src="./assets/docs-2.png" alt="">
                    <div class="step-text-cont animateL2">
                        <p class="step-title">Create Classes</p>
                        <p class="step-des">
                            <ul>
                                <li>Navigate to the Class Management section and click Create New Class.</li>
                                <li>Input class details: name, subject code</li>
                                <li>Add students to the class.</li>
                            </ul>
                        </p>
                    </div>
                </div>
            </div>

            <div class="docs-card">
                <div class="doc-step animateF3">Step 3</div>
                <div class="docs-info">
                <img class="step-img animateR3" src="./assets/docs-3.png" alt="">
                    <div class="step-text-cont animateL3">
                        <p class="step-title">Start Taking Attendance Through the System</p>
                        <p class="step-des">
                            <ul>
                                <li>Open the class details page and click Start Attendance.</li>
                                <li>Display the generated QR code to students (e.g., project it on a screen).</li>
                                <li>Students scan the QR code using their smartphones</li>
                                <li>Attendance is recorded automatically once students validate their identity.</li>
                            </ul>
                        </p>
                    </div>
                </div>
            </div>

            <div class="docs-card">
                <div class="doc-step animateF4">Step 4</div>
                <div class="docs-info">
                <img class="step-img animateR4" src="./assets/docs-4.png" alt="">
                    <div class="step-text-cont animateL4">
                        <p class="step-title">Review and Make Changes</p>
                        <p class="step-des">
                            <ul>
                                <li>Go to the Attendance Logs section and filter by class, date.</li>
                                <li>Edit entries if errors occur (e.g., mark a student “Present” if they scanned incorrectly).</li>
                                <li>Export preliminary reports for departmental review or audits (formats: CSV, PDF).</li>
                            </ul>
                        </p>
                    </div>
                </div>
            </div>

            <div class="docs-card">
                <div class="doc-step animateF5">Step 5</div>
                <div class="docs-info">
                <img class="step-img animateR5" src="./assets/docs-5.png" alt="">
                    <div class="step-text-cont animateL5">
                        <p class="step-title">Finalize the Result</p>
                        <p class="step-des">
                            <ul>
                                <li>Confirm the action to permanently save the attendance data.</li>
                                <li>Export finalized reports for official records or integration with grading systems (e.g., LMS platforms like Moodle or Canvas).</li>
                                <li>Archive the class data for future reference or compliance purposes.</li>
                            </ul>
                        </p>
                    </div>
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