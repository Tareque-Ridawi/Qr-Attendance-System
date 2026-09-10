<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");
$user_data = check_loginins($con);
// Check if course_id is passed through URL as a query parameter
if (isset($_GET['course_id']) && !empty($_GET['course_id'])) {
    $course_id = $_GET['course_id']; // Get the course_id from URL
} else {
    echo "Course ID not provided!";
    exit();  // Stop execution if course_id is missing
}

// Escape the course_id to prevent SQL injection
$course_id = $con->real_escape_string($course_id);

// SQL Query to get course title by course_id
$sql = "SELECT course_title FROM courses WHERE id = '$course_id' LIMIT 1";
$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    $course = $result->fetch_assoc();
    $course_title = $course['course_title']; // Store course title
} else {
    $course_title = "Course not found"; // Default message if course not found
}

// Get Course Code from Sql
$sql = "SELECT course_id FROM courses WHERE id = '$course_id' LIMIT 1";
$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    $course = $result->fetch_assoc();
    $course_code = $course['course_id']; // Store course title
} else {
    $course_code = "Course not found"; // Default message if course not found
}

$date = $con->real_escape_string($_GET['date']);

// Fetch attendance records for the given date and course
$sql = "SELECT users.name, attendance.status 
        FROM attendance
        JOIN users ON attendance.student_id = users.user_name
        WHERE attendance.course_id = '$course_code' AND attendance.date = '$date'";

$result = $con->query($sql);

$attendance_records = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendance_records[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_present'])) {
    if (!empty($_POST['student_id'])) {
        foreach ($_POST['student_id'] as $student_name) {
            $student_name = $con->real_escape_string($student_name);

            // Convert student name to user_name
            $query = "SELECT user_name FROM users WHERE name = '$student_name' LIMIT 1";
            $res = $con->query($query);
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $user_name = $row['user_name'];

                // Update status to 'Present'
                $update_sql = "UPDATE attendance 
                               SET status = 'Present' 
                               WHERE course_id = '$course_code' 
                               AND student_id = '$user_name' 
                               AND date = '$date'";

                $con->query($update_sql);
            }
        }
        echo "<script>alert('Students marked present successfully!'); window.location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('No student selected!');</script>";
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
</head>

<body>
    <main>
        <div class="course-qr">
            <div class="course-title-qr"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                </svg> <?php echo $course_title; ?></div>
            <div class="time-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" />
                </svg>
                <span id="time">Loading...</span> &nbsp &nbsp &nbsp
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                </svg>
                <span id="date">Loading...</span>
            </div>
        </div>



        <div class="qr-updates">
            <div class="records-head">
                <p class="qr-update-txt">Records for : <span><?php echo htmlspecialchars($date); ?></span></p>
                <div class="cn-btn">
                    <span>Change</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z" />
                    </svg>
                </div>
            </div>
            <div class="qr-update-table">
                <div class="qr-update-head">
                    <div class="id">Name</div>
                    <div class="status">Status</div>
                </div>

                <?php if (!empty($attendance_records)) : ?>
                    <?php foreach ($attendance_records as $record) : ?>
                        <div class="qr-row rec-hover">
                            <div class="qr-cl-id"><?php echo htmlspecialchars($record['name']); ?></div>
                            <div class="qr-cl-time"><?php echo htmlspecialchars($record['status']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No attendance records found for this date.</p>
                <?php endif; ?>

                <!--
                <div class="qr-row rec-hover">
                    <div class="qr-cl-id">Faisal Madkhali</div>
                    <div class="qr-cl-time">Absent</div>
                </div>
                -->

            </div>
        </div>

        <div class="add-std-form">
            <form method="POST">
                <?php foreach ($attendance_records as $record): ?>
                    <?php if ($record['status'] === 'Absent'): ?>
                        <label class="atd-edit">
                            <p><?php echo htmlspecialchars($record['name']); ?></p>
                            <input type="checkbox" name="student_id[]" value="<?php echo htmlspecialchars($record['name']); ?>">
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>

                <p class="info">Note: Once students are marked present, the data can't be changed</p>
                <input type="submit" name="mark_present" value="Mark Present">
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
</body>
<script>
    document.querySelector(".cn-btn").addEventListener("click", function() {
        document.querySelector(".add-std-form").style.display = "flex";
    });

    document.querySelector(".cn-btn").addEventListener("click", function() {
        // Create overlay if it doesn't exist
        let overlay = document.querySelector(".custom-overlay");
        if (!overlay) {
            overlay = document.createElement("div");
            overlay.classList.add("custom-overlay");
            document.body.appendChild(overlay);

            // Add click event to remove overlay and hide form
            overlay.addEventListener("click", function() {
                document.querySelector(".add-std-form").style.display = "none";
                overlay.remove();
            });
        }

        // Show form and overlay
        document.querySelector(".add-std-form").style.display = "flex";
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
    .add-std-form {
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
    // Time API Handling
    const timeElement = document.getElementById("time");
             const dateElement = document.getElementById("date");

             async function fetchTime() {
                 try {
                     const response = await fetch("https://worldtimeapi.org/api/timezone/UTC");
                     if (!response.ok) throw new Error('API Error');

                     const data = await response.json();
                     const dateTime = new Date(data.datetime);

                     // Format time with leading zeros
                     const timeStr = dateTime.toLocaleTimeString('en-IN', {
                         hour: '2-digit',
                         minute: '2-digit',
                         second: '2-digit',
                         hour12: false
                     });

                     // Format date as DD/MM/YYYY
                     const dateStr = dateTime.toLocaleDateString('en-IN', {
                         day: '2-digit',
                         month: '2-digit',
                         year: 'numeric'
                     }).replace(/\//g, '/');

                     if (timeElement && dateElement) {
                         timeElement.textContent = timeStr;
                         dateElement.textContent = dateStr;
                     }
                 } catch (error) {
                     console.log("Using local time");
                     // Fallback to client time
                     const now = new Date();
                     timeElement.textContent = now.toLocaleTimeString();
                     dateElement.textContent = now.toLocaleDateString();
                 }
             }

             // Initial call and update every second
             fetchTime();
             setInterval(fetchTime, 1000);
</script>

</html>