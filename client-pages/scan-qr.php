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

// Load the course only when it belongs to the logged-in instructor.
$course_id = $con->real_escape_string($course_id);
$instructor_id = $con->real_escape_string($user_data['user_name']);
$course_sql = "SELECT course_title, course_id
               FROM courses
               WHERE id = '$course_id' AND instructor_id = '$instructor_id'
               LIMIT 1";
$course_result = $con->query($course_sql);

if (!$course_result || $course_result->num_rows === 0) {
    http_response_code(403);
    exit("You are not authorized to access this course.");
}

$course = $course_result->fetch_assoc();
$course_title = $course['course_title'];
$course_code = $course['course_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_attendance'])) {
    header('Content-Type: application/json');
    $attendance_date = date("Y-m-d");
    $delete_stmt = $con->prepare("DELETE FROM attendance WHERE course_id = ? AND date = ?");
    if (!$delete_stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Could not prepare attendance deletion."]);
        exit();
    }

    $delete_stmt->bind_param("ss", $course_code, $attendance_date);
    if ($delete_stmt->execute()) {
        $_SESSION['deleted_attendance_date'][$course_id] = $attendance_date;
        echo json_encode(["success" => true, "deleted" => $delete_stmt->affected_rows, "date" => $attendance_date]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to delete today's attendance records."]);
    }
    $delete_stmt->close();
    exit();
}

if ($course_code && ($_SESSION['deleted_attendance_date'][$course_id] ?? null) !== date("Y-m-d")) {
    // ✅ Auto-mark all students as Absent on page load (if not already marked)
    $date = date("Y-m-d");
    $time = "00:00:00";

    $enroll_sql = "SELECT student_id FROM enrollments WHERE course_id = '$course_code'";
    $enroll_result = $con->query($enroll_sql);

    if ($enroll_result && $enroll_result->num_rows > 0) {
        while ($row = $enroll_result->fetch_assoc()) {
            $std_id = $row['student_id'];

            // Only insert if not already marked today
            $check_sql = "SELECT id FROM attendance 
                      WHERE course_id = '$course_code' AND student_id = '$std_id' AND date = '$date' LIMIT 1";
            $check_result = $con->query($check_sql);

            if ($check_result->num_rows == 0) {
                $insert_absent = "INSERT INTO attendance (course_id, student_id, status, date, time)
                              VALUES ('$course_code', '$std_id', 'Absent', '$date', '$time')";
                $con->query($insert_absent);
            }
        }
    }
}

// ✅ Process QR Attendance Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id']) && isset($_POST['qr_data'])) {
    $student_id = $con->real_escape_string($_POST['student_id']);
    $qr_data = $con->real_escape_string($_POST['qr_data']);

    // Initialize std_id to avoid undefined variable
    $std_id = null;

    // ✅ Get student username from users table
    $student_sql = "SELECT user_name FROM users WHERE id = '$student_id' LIMIT 1";
    $student_result = $con->query($student_sql);
    if ($student_result && $student_result->num_rows > 0) {
        $student_row = $student_result->fetch_assoc();
        $std_id = $student_row['user_name'];
    } else {
        // Student not found in database
        echo json_encode(["error" => "Student not found"]);
        exit();
    }

    // Verify course_code is set
    if (!isset($course_code) || empty($course_code)) {
        echo json_encode(["error" => "Course code not available"]);
        exit();
    }

    $date = date("Y-m-d");
    $time = date("H:i:s");

    //  Update attendance if already marked as Absent, else insert as Present
    $check_sql = "SELECT id, status, time FROM attendance 
WHERE course_id = '$course_code' AND student_id = '$std_id' AND date = '$date' LIMIT 1";
    $check_result = $con->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        $attendance_row = $check_result->fetch_assoc();
        $attendance_id = (int) $attendance_row['id'];
        if ($attendance_row['status'] === 'Present' && $attendance_row['time'] === $time) {
            echo json_encode(["success" => true, "std_id" => $std_id, "already_present" => true]);
        } else {
            $update_stmt = $con->prepare("UPDATE attendance
                                          SET status = 'Present', time = ?
                                          WHERE id = ? AND status = ? AND time = ?");
            $update_stmt->bind_param("siss", $time, $attendance_id, $attendance_row['status'], $attendance_row['time']);
            if ($update_stmt->execute() && $update_stmt->affected_rows === 1) {
                echo json_encode(["success" => true, "std_id" => $std_id, "updated" => true]);
            } else {
                http_response_code(409);
                echo json_encode(["error" => "The attendance record changed before the scan could be applied."]);
            }
            $update_stmt->close();
        }
    } else {
        //  Insert new if not found
        $insert_sql = "INSERT INTO attendance (course_id, student_id, status, date, time) 
     VALUES ('$course_code', '$std_id', 'Present', '$date', '$time')";
        if ($con->query($insert_sql)) {
            echo json_encode(["success" => true, "std_id" => $std_id, "inserted" => true]);
        } else {
            echo json_encode(["error" => "Failed to insert attendance: " . $con->error]);
        }
    }
    exit();
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
            <div class="course-title-qr"><svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                </svg> <?php echo $course_title; ?></div>
            <div class="time-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path
                        d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" />
                </svg>
                <span id="time">Loading...</span> &nbsp &nbsp &nbsp
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path
                        d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                </svg>
                <span id="date">Loading...</span>
            </div>
        </div>

        <div class="page-actions">
            <a class="page-action" href="instructor-panel.php">Back to Instructor Panel</a>
            <button class="page-action" id="delete-attendance-button" type="button">Delete Attendance</button>
        </div>

        <div class="main-qr-container">
            <p class="qr-info">Scan The Qr Code With your device while logged in with your ID to get your attendance
                counted</p>
            <div class="qr-scan">
                <img src="../assets/logo.png" alt="">
            </div>
            <div class="qr-reset-time">
                Code Resets in <span>15</span> Seconds
            </div>
        </div>


        <div class="qr-updates">
            <p class="qr-update-txt">Updates:</p>
            <div class="qr-update-table">
                <div class="qr-update-head">
                    <div class="id">ID</div>
                    <div class="time">Time</div>
                    <div class="status">Status</div>
                </div>

                <div class="no-scan-msg">No one has scanned yet.</div>
            </div>
        </div>

    </main>
    <?php include("../includes/footer.php"); ?>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const course_id = <?php echo json_encode($course_id); ?>;
        let currentQRText = null; // Global variable for QR text

        document.getElementById("delete-attendance-button").addEventListener("click", async function () {
            if (!confirm("Delete all attendance records for this course today? This cannot be undone.")) {
                return;
            }

            this.disabled = true;
            try {
                const response = await fetch(window.location.href, {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "delete_attendance=1"
                });
                const result = await response.json();
                if (!response.ok || !result.success) {
                    alert(result.error || "Unable to delete today's attendance.");
                    this.disabled = false;
                    return;
                }

                const updateTable = document.querySelector(".qr-update-table");
                updateTable.querySelectorAll(".qr-row, .no-scan-msg").forEach(row => row.remove());
                const message = document.createElement("div");
                message.className = "no-scan-msg";
                message.textContent = `Deleted ${result.deleted} attendance record(s) for ${result.date}.`;
                updateTable.appendChild(message);
            } catch (error) {
                alert("Unable to delete today's attendance. Please try again.");
                this.disabled = false;
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            const qrContainer = document.querySelector(".qr-scan");

            function generateQRCodeString() {
                const chars = "abcdefghijklmnopqrstuvwxyz";
                const digits = "0123456789";
                return Array.from({
                    length: 6
                }, (_, i) =>
                    i % 2 === 0 ? digits[Math.floor(Math.random() * digits.length)] :
                        chars[Math.floor(Math.random() * chars.length)]
                ).join('');
            }

            function generateQRCode() {
                qrContainer.innerHTML = "";
                currentQRText = generateQRCodeString(); // Store QR text globally

                new QRCode(qrContainer, {
                    text: currentQRText,
                    width: 150,
                    height: 150
                });
            }

            function startCountdown() {
                let timeLeft = 15;
                const countdownSpan = document.querySelector(".qr-reset-time span");

                const countdown = setInterval(() => {
                    countdownSpan.textContent = timeLeft;

                    if (timeLeft-- <= 0) {
                        clearInterval(countdown);
                        generateQRCode();
                        startCountdown();
                    }
                }, 1000);
            }

            if (qrContainer) {
                generateQRCode();
                startCountdown();
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            const firebaseConfig = {
                apiKey: "AIzaSyCuJLsLu_dgDdkjLNeEHcub8O1PniGxtWY",
                authDomain: "qr-attendance-system-ecf60.firebaseapp.com",
                databaseURL: "https://qr-attendance-system-ecf60-default-rtdb.firebaseio.com",
                projectId: "qr-attendance-system-ecf60",
                storageBucket: "qr-attendance-system-ecf60.firebasestorage.app",
                messagingSenderId: "617612548435",
                appId: "1:617612548435:web:33404aa6d0fffd5a8da9a6",
                measurementId: "G-2RCS482LDJ"
            };

            //  Initialize Firebase
            firebase.initializeApp(firebaseConfig);
            const db = firebase.database();

            let lastTimestamp = new Date().toISOString(); // Store the time when the page loads

            //  Listen only for NEW scans after page load
            db.ref("attendance")
                .orderByChild("timestamp")
                .startAt(lastTimestamp)
                .on("child_added", (snapshot) => {
                    if (!snapshot.exists()) {
                        console.error("❌ No data found in snapshot!");
                        return;
                    }

                    const data = snapshot.val();
                    console.log("New scan received:", data);

                    if (!data.qr_data || !data.student_id) {
                        console.error("❌ Missing QR data or student_id in snapshot!");
                        return;
                    }

                    if (data.qr_data === currentQRText) {
                        fetch(window.location.href, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `student_id=${encodeURIComponent(data.student_id)}&qr_data=${encodeURIComponent(data.qr_data)}&course_id=${encodeURIComponent(course_id)}`
                        })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    //  Inject the scanned student's data into the update table
                                    const updateTable = document.querySelector(".qr-update-table");

                                    // Remove the "No one has scanned yet" message if it exists
                                    const noScanMsg = updateTable.querySelector(".no-scan-msg");
                                    if (noScanMsg) noScanMsg.remove();

                                    // Create new row
                                    const newRow = document.createElement("div");
                                    newRow.className = "qr-row";
                                    newRow.dataset.studentId = result.std_id;
                                    newRow.innerHTML = `
                                                            <div class="qr-cl-id">${result.std_id}</div>
                                                            <div class="qr-cl-time">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                                                            <div class="qr-cl-status">Success</div>
                                                        `;

                                    // Insert the new row at the top (right after the header)
                                    const head = updateTable.querySelector(".qr-update-head");
                                    updateTable.insertBefore(newRow, head.nextSibling);

                                } else {
                                    alert("❌ Failed to record attendance.");
                                }
                            })
                            .catch(error => console.error("❌ Fetch error:", error));

                        //  Delete scanned QR from Firebase
                        snapshot.ref.remove();
                    } else {
                        alert("❌ Invalid QR Code");
                    }

                    lastTimestamp = data.timestamp;
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

</body>

</html>