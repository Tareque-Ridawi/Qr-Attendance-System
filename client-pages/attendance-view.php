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


// Fetch enrolled student IDs from enrollments table
$sql = "SELECT student_id FROM enrollments WHERE course_id = '$course_code'";
//$sql = "SELECT student_id FROM enrollments WHERE course_id = '$course_id'";
$result = $con->query($sql);

$students = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row['student_id'];
    }
}


// Fetch student details if students exist
$student_details = [];
if (!empty($students)) {
    $placeholders = implode(',', array_fill(0, count($students), '?'));
    $stmt = $con->prepare("SELECT name, user_name FROM users WHERE user_name IN ($placeholders)");

    if ($stmt) {
        $stmt->bind_param(str_repeat('s', count($students)), ...$students);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $student_details[] = $row;
        }
        $stmt->close();
    }
}

// Fetch attendance records for the given course
$sql = "SELECT date, 
               SUM(status = 'Present') AS present_count, 
               COUNT(*) AS total_students 
        FROM attendance 
        WHERE course_id = '$course_code' 
        GROUP BY date 
        ORDER BY date DESC";

$result = $con->query($sql);

$attendance_records = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendance_records[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_student']) && $_POST['delete_student'] == "1") {
        // DELETE Logic (Prevent conflict with INSERT)
        if (!isset($_POST['course_id']) || !isset($_POST['student_id'])) {
            die("Missing data!");
        }
        $course_id = $con->real_escape_string($_POST['course_id']);
        $student_id = $con->real_escape_string($_POST['student_id']);

        $sql = "DELETE FROM enrollments WHERE course_id = '$course_code' AND student_id = '$student_id'";
        if ($con->query($sql) === TRUE) {
            echo "Student removed successfully!";
        } else {
            echo "Error: " . $con->error;
        }
        exit();
    }

    // Existing INSERT Logic
    echo "<script>console.log('Form submitted');</script>";
    if (!isset($course_code) || empty($course_code)) {
        die("Error: Course code not found!");
    }

    $student_id = $con->real_escape_string($_POST['student_id']);
    $sql = "INSERT INTO enrollments (course_id, student_id) VALUES ('$course_code', '$student_id')";

    if ($con->query($sql) === TRUE) {
        echo "<script>alert('Student added successfully!'); window.location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Error: " . $con->error . "');</script>";
    }

    $con->close();
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
            <div class="abt-std-top mrg-btm act">
                <span class="abt-std-in flex cust">Students Enrolled</span>
                <span class="abt-std-btn flex"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z" />
                    </svg> Add
                </span>
            </div>
            <div class="qr-update-table">
                <div class="qr-update-head">
                    <div class="id">Full Name</div>
                    <div class="status">User ID</div>
                </div>

                <?php if (!empty($student_details)) : ?>
                    <?php foreach ($student_details as $student) : ?>
                        <div class="qr-row rec-hover">
                            <div class="qr-cl-id"><?php echo htmlspecialchars($student['name']); ?> <span class="del-std"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3zM472 200l144 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-144 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z" />
                                    </svg></span></div>
                            <div class="qr-cl-time"><?php echo htmlspecialchars($student['user_name']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No students enrolled.</p>
                <?php endif; ?>
                <?php  //echo $students[0]; 
                ?>
                <!--
                <div class="qr-row rec-hover">
                    <div class="qr-cl-id">John Doe</div>
                    <div class="qr-cl-time">john-doe-909</div>
                </div>
                -->
            </div>
        </div>

        <div class="qr-updates">
            <p class="qr-update-txt">Records:</p>
            <div>Tap on a date to change records</div>
            <div class="qr-update-table">
                <div class="qr-update-head">
                    <div class="id">Date</div>
                    <div class="status">Present</div>
                </div>

                <?php if (!empty($attendance_records)) : ?>
                    <?php foreach ($attendance_records as $record) : ?>
                        <div class="qr-row rec-hover" onclick="redirectToAttendance('<?php echo $record['date']; ?>')">
                            <div class="qr-cl-id"><?php echo htmlspecialchars($record['date']); ?></div>
                            <div class="qr-cl-time">
                                <?php echo $record['present_count'] . "/" . $record['total_students']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No attendance records found.</p>
                <?php endif; ?>

                <script>
                    function redirectToAttendance(date) {
                        let courseid = "<?php echo $course_id; ?>";
                        window.location.href = `attendance-date-view.php?date=${date}&course_id=${courseid}`;
                    }
                </script>


                <!--
                <div class="qr-row rec-hover">
                    <div class="qr-cl-id">11/11/1111</div>
                    <div class="qr-cl-time">50/50</div>
                </div>
                -->

            </div>
            <form method="POST" action="export_csv.php" target="_blank">
                <button  type="submit" name="export_course" value="<?php echo $course_code; ?>" class="c-m export-attendance exp-csv">
                    Export Attendance
                </button>
            </form>

        </div>

        <div class="add-std-form">
            <form method="POST">
                <label for="course-id">
                    Student Id:
                    <input type="text" id="student-id" required placeholder="Student ID" name="student_id">
                </label>
                <input type="submit" value="Add">
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
    document.querySelector(".abt-std-btn").addEventListener("click", function() {
        document.querySelector(".add-std-form").style.display = "flex";
    });

    document.querySelector(".abt-std-btn").addEventListener("click", function() {
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


    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".del-std").forEach(function(delBtn) {
            delBtn.addEventListener("click", function(event) {
                event.stopPropagation(); // Prevent triggering other events

                let studentId = this.closest(".qr-row").querySelector(".qr-cl-time").textContent.trim();
                let courseId = new URLSearchParams(window.location.search).get("course_id");

                if (!courseId || !studentId) {
                    alert("Missing course or student information.");
                    return;
                }

                if (confirm("Are you sure you want to remove this student?")) {
                    fetch(window.location.href, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: `delete_student=1&course_id=${encodeURIComponent(courseId)}&student_id=${encodeURIComponent(studentId)}`
                        })
                        .then(response => response.text())
                        .then(data => {
                            alert(data);
                            window.location.reload(); // Refresh the page after deletion
                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        });
    });


    // Corrected Time API Handling
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