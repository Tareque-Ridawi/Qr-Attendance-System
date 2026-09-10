<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");
$user_data = check_login($con);

// Fetch courses enrolled by current user
$user_name = $con->real_escape_string($user_data['user_name']);
$sql = "SELECT courses.* 
        FROM courses 
        INNER JOIN enrollments ON courses.course_id = enrollments.course_id 
        WHERE enrollments.student_id = '$user_name'";

$result = $con->query($sql);


// Update Only Name (Avoid Changing user_name)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["full_name"], $_POST["email"])) {
    $full_name = $con->real_escape_string($_POST["full_name"]);
    $email = $con->real_escape_string($_POST["email"]);

    // Update name and email for the current user_name
    $sql = "UPDATE users SET name = '$full_name', email = '$email' WHERE user_name = '{$user_data['user_name']}'";

    if ($con->query($sql) === TRUE) {
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        echo "Error: " . $con->error;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Qr Code Based Attendance</title>
    <style>
        .hero {
            background-image: url("../assets/bg-std-dash.png");
            background-size: 100% auto;
            background-position: center;
            padding-top: 100px;
        }

        @media screen and (max-width: 786px) {
            nav {
                flex-direction: row;
                box-shadow: unset;
            }

            nav li {
                flex-direction: row;
                font-size: 12px;
            }

            .dboard {
                display: none;
            }

            .nav-btn {
                display: block;
            }

            .nav-btn ul {
                margin: 0;
            }

            .nav-btn ul button {
                font-size: 12px;
            }

            .std li ul:nth-of-type(2) {
                margin-left: 0px;
            }

            .std svg {
                width: 15px;
            }

            .hero {
                height: 170px;
            }
        }
    </style>
</head>

<body>
    <nav class="std">
        <li>
            <ul class="dboard">Dashboard</ul>
            <ul>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z" />
                </svg>
                <div id="std-name"><?php echo htmlspecialchars($user_data['name']); ?></div>
            </ul>
            <ul>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z" />
                </svg>
                <div>Student</div>
            </ul>
        </li>
        <li class="nav-btn">
            <ul><a href="../access-pages/sign-in-st.php"><button id="tr-s-btn">Logout</button></a></ul>
        </li>
    </nav>
    <main>
        <div class="hero">
            <div class="hero-img-logo-cont">
                <img src="../assets/tap-qr.png" alt="Main Logo">
            </div>
            <div class="hero-text-cont">
                <div class="main-hero">
                    <div class="hero-1">
                        Welcome
                    </div>
                    <div class="hero-2">
                        <?php echo htmlspecialchars($user_data['name']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!--
        <div class="attendance-result">
            <div class="c-held">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c.2 35.5-28.5 64.3-64 64.3l-320.4 0c-35.3 0-64-28.7-64-64l0-160.4-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L416 100.7 416 64c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 121 52.8 46.4c8 7 12 15 11 24zM248 192c-13.3 0-24 10.7-24 24l0 80c0 13.3 10.7 24 24 24l80 0c13.3 0 24-10.7 24-24l0-80c0-13.3-10.7-24-24-24l-80 0z"/></svg>
                <div class="c-held-txt">
                    <span class="c-held-num">100</span>
                    <span class="c-held-t">Class Held</span>
                </div>
            </div>

            <div class="c-attend">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                <div class="c-attend-txt">
                    <span class="c-attend-num">100</span>
                    <span class="c-attend-t">Class Attended</span>
                </div>
            </div>

            <div class="c-miss">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/></svg>
                <div class="c-miss-txt">
                    <span class="c-miss-num">100</span>
                    <span class="c-miss-t">Class Missed</span>
                </div>
            </div>

            <div class="c-per">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M374.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-320 320c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l320-320zM128 128A64 64 0 1 0 0 128a64 64 0 1 0 128 0zM384 384a64 64 0 1 0 -128 0 64 64 0 1 0 128 0z"/></svg>
                <div class="c-per-txt">
                    <span class="c-per-num">100</span>
                    <span class="c-per-t">Percentage</span>
                </div>
            </div>
        </div>
    -->

        <div class="courses">
            <p class="course-e">Courses Enrolled:</p>
            <p class="c-info">
                Note : Only Instructrors can enroll and unenroll students. Contact your instructor for making any changes.
            </p>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $course_id = $row["course_id"];
                    $course_title = $row["course_title"];

                    // Fetch attendance stats for this course and student
                    $stats_sql = "SELECT 
                        COUNT(*) AS total,
                        SUM(status = 'Present') AS attended,
                        SUM(status = 'Absent') AS missed
                      FROM attendance 
                      WHERE course_id = '$course_id' AND student_id = '$user_name'";
                    $stats_result = $con->query($stats_sql);
                    $stats = $stats_result->fetch_assoc();

                    $total = $stats['total'];
                    $attended = $stats['attended'];
                    $missed = $stats['missed'];
                    $percentage = $total > 0 ? round(($attended / $total) * 100) : 0;

                    echo '<div class="course">';
                    echo '    <p class="course-title"><span id="course-num">' . $course_id . '</span> ' . $course_title . '</p>';
                    echo '    <div class="course-progess">';

                    // Class Held
                    echo '        <div class="c-h">';
                    echo '            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c.2 35.5-28.5 64.3-64 64.3l-320.4 0c-35.3 0-64-28.7-64-64l0-160.4-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L416 100.7 416 64c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 121 52.8 46.4c8 7 12 15 11 24zM248 192c-13.3 0-24 10.7-24 24l0 80c0 13.3 10.7 24 24 24l80 0c13.3 0 24-10.7 24-24l0-80c0-13.3-10.7-24-24-24l-80 0z" /></svg>';
                    echo '            <span>' . $total . '</span> Class Held';
                    echo '        </div>';

                    // Class Attended
                    echo '        <div class="c-a">';
                    echo '            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" /></svg>';
                    echo '            <span>' . $attended . '</span> Class Attended';
                    echo '        </div>';

                    // Class Missed
                    echo '        <div class="c-m">';
                    echo '            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z" /></svg>';
                    echo '            <span>' . $missed . '</span> Class Missed';
                    echo '        </div>';

                    // Percentage
                    echo '        <div class="c-p">';
                    echo '            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M374.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-320 320c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l320-320zM128 128A64 64 0 1 0 0 128a64 64 0 1 0 128 0zM384 384a64 64 0 1 0 -128 0 64 64 0 1 0 128 0z" /></svg>';
                    echo '            <span>' . $percentage . '</span> % Percentage';
                    echo '        </div>';

                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo "<p>No enrolled courses found.</p>";
            }
            ?>



            <!--
            <div class="course">
                <p class="course-title"><span id="couse-num">03</span> Computer Ethics</p>
                <div class="course-progess">
                    <div class="c-h"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c.2 35.5-28.5 64.3-64 64.3l-320.4 0c-35.3 0-64-28.7-64-64l0-160.4-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L416 100.7 416 64c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 121 52.8 46.4c8 7 12 15 11 24zM248 192c-13.3 0-24 10.7-24 24l0 80c0 13.3 10.7 24 24 24l80 0c13.3 0 24-10.7 24-24l0-80c0-13.3-10.7-24-24-24l-80 0z" />
                        </svg> <span>100</span> Class Held</div>
                    <div class="c-a"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                        </svg> <span>100</span> Class Attended</div>
                    <div class="c-m"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z" />
                        </svg> <span>100</span> Class Missed</div>
                    <div class="c-p"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512
                            <path d="M374.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-320 320c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l320-320zM128 128A64 64 0 1 0 0 128a64 64 0 1 0 128 0zM384 384a64 64 0 1 0 -128 0 64 64 0 1 0 128 0z" />
                        </svg> <span>100</span> % Percentage</div>
                </div>
            </div>
            -->

        </div>

        <div class="abt-std">
            <div class="abt-std-top info-edit">
                <span class="abt-std-in">Student Information</span>
                <span class="abt-std-btn">Edit</span>
            </div>
            <div class="center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z" />
                </svg>
            </div>
            <div class="abt-std-info"><span>Name</span><span><?php echo htmlspecialchars($user_data['name']); ?></span></div>
            <div class="abt-std-info"><span>ID</span><span><?php echo htmlspecialchars($user_data['user_name']); ?></span></div>
            <div class="abt-std-info"><span>E-mail</span><span><?php echo htmlspecialchars($user_data['email']); ?></span></div>
        </div>



        <div class="edit-user-form">
            <form method="POST">
                <label for="full_name">
                    Name:
                    <input type="text" id="full_name" required placeholder="Name" name="full_name">
                </label>
                <label for="email">
                    Email:
                    <input type="email" id="email" required placeholder="Email" name="email">
                </label>
                <label for="user_name">
                    ID:
                    <input readonly type="text" id="user_name" required placeholder="ID Can't be changed" name="user_name">
                </label>
                <input type="submit" value="Change">
            </form>
        </div>

    </main>
    <footer>
        <div class="logo-container">
            <img src="../assets/logo.png" alt="">
        </div>
        <div class="foot-info">
            <p class="foot-title">QR Code Based Attendance System</p>
            <p class="foot-abt">&copy; 2025 . All Rights Reserved . Developed by <span>Super Developer</span></p>
        </div>
    </footer>

    <script>
        /*-------------------------------------------------
        Edit form
        ---------------------------------------------------*/
        document.querySelector(".info-edit").addEventListener("click", function() {
            document.querySelector(".edit-user-form").style.display = "flex";
        });

        document.querySelector(".info-edit").addEventListener("click", function() {
            // Create overlay if it doesn't exist
            let overlay = document.querySelector(".custom-overlay");
            if (!overlay) {
                overlay = document.createElement("div");
                overlay.classList.add("custom-overlay");
                document.body.appendChild(overlay);

                // Add click event to remove overlay and hide form
                overlay.addEventListener("click", function() {
                    document.querySelector(".edit-user-form").style.display = "none";
                    overlay.remove();
                });
            }

            // Show form and overlay
            document.querySelector(".edit-user-form").style.display = "flex";
            overlay.style.display = "block";
        });



        // Add styles dynamically for the overlay
        const style = document.createElement("style");
        style.innerHTML = `
    .custom-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        display: none;
    }
    .add-course-form,.edit-user-form {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        z-index: 1001;
        border-radius: 5px;
    }
`;
        document.head.appendChild(style);
    </script>

    <!--  Fixed Firebase versions -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            //  Firebase Configuration
            const firebaseConfig = {
                apiKey: "AIzaSyAiu3Psww6S95HXZAExQcKr0WB7Hb8bhhQ",
                authDomain: "qr-attendance-34479.firebaseapp.com",
                databaseURL: "https://qr-attendance-34479-default-rtdb.firebaseio.com",
                projectId: "qr-attendance-34479",
                storageBucket: "qr-attendance-34479.firebasestorage.app",
                messagingSenderId: "472523260246",
                appId: "1:472523260246:web:324e97172fc910f0dad6f7",
                measurementId: "G-QDH7859V5K"
            };

            //  Initialize Firebase
            firebase.initializeApp(firebaseConfig);
            const db = firebase.database();

            //  QR Code Scanner (Click on Logo to Scan)
            const img = document.querySelector('img[alt="Main Logo"]');

            img.addEventListener("click", function() {
                const overlay = document.createElement("div");
                overlay.style.position = "fixed";
                overlay.style.top = "0";
                overlay.style.left = "0";
                overlay.style.width = "100vw";
                overlay.style.height = "100vh";
                overlay.style.background = "rgba(0,0,0,0.8)";
                overlay.style.display = "flex";
                overlay.style.alignItems = "center";
                overlay.style.justifyContent = "center";
                overlay.style.zIndex = "1000";

                const scannerBox = document.createElement("div");
                scannerBox.style.width = "300px";
                scannerBox.style.height = "300px";
                scannerBox.style.background = "#fff";
                scannerBox.style.position = "relative";

                const scannerDiv = document.createElement("div");
                scannerDiv.id = "qr-reader";
                scannerBox.appendChild(scannerDiv);
                overlay.appendChild(scannerBox);
                document.body.appendChild(overlay);

                const html5QrCode = new Html5Qrcode("qr-reader");
                html5QrCode.start({
                        facingMode: "environment"
                    }, // Back Camera
                    {
                        fps: 10,
                        qrbox: 250
                    },
                    (decodedText) => {
                        //  Send scanned data to Firebase
                        db.ref("attendance").push({
                            student_id: "<?= $user_data['id']; ?>",
                            qr_data: decodedText,
                            timestamp: new Date().toISOString()
                        }).then(() => {
                            alert("Scan successful!");
                        }).catch((error) => {
                            console.error("Error saving to Firebase:", error);
                        });

                        html5QrCode.stop();
                        overlay.remove();
                    },
                    (error) => {}
                );

                overlay.addEventListener("click", function(e) {
                    if (e.target === overlay) {
                        html5QrCode.stop();
                        overlay.remove();
                    }
                });
            });
        });
    </script>

</body>

</html>
