<?php
    session_start();
    include("../includes/connection.php");
    include("../includes/functions.php");
    $user_data = check_loginins($con);
    $instructor_id = $con->real_escape_string($user_data['user_name']);
    $sql = "SELECT * FROM courses WHERE instructor_id = '{$user_data['user_name']}'";
    $result = $con->query($sql);
    
    // Insert Course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["course_id"]) && isset($_POST["course_title"])) {
    $course_id = $con->real_escape_string($_POST["course_id"]);
    $course_title = $con->real_escape_string($_POST["course_title"]);

    $sql = "INSERT INTO courses (course_id, course_title, instructor_id) 
            VALUES ('$course_id', '$course_title', '$instructor_id')";
    
    if ($con->query($sql) === TRUE) {
        // Redirect to the same page to avoid resubmitting the form after refresh
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        echo "Error: " . $con->error;
    }
}


// Delete Course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_course"])) {
    $course_id = $con->real_escape_string($_POST["delete_course"]);
    $sql = "DELETE FROM courses WHERE id = '$course_id'";
    if ($con->query($sql) === TRUE) {
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }
}

// Fetch Courses
function getCourses($con, $instructor_id) {
    $stmt = $con->prepare("SELECT * FROM courses WHERE instructor_id = ?");
    $stmt->bind_param("s", $instructor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
$courses = getCourses($con, $user_data['user_name']);


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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .hero{
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
            nav li{
                flex-direction: row;
                font-size: 12px;
            }
            .dboard {
               display:none;
            }
            .nav-btn{
                display: block;
            }
            .nav-btn ul{
                margin: 0;
            }
            .nav-btn ul button{
                font-size: 12px;
            }
            .std li ul:nth-of-type(2) {
                margin-left:0px;
            }
            .std svg{
                width:15px;
            }
            .hero{
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
                <div id="std-name"><?php echo htmlspecialchars($user_data['name']); ?></div>
            </ul>
            <ul>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M192 96a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm-8 384l0-128 16 0 0 128c0 17.7 14.3 32 32 32s32-14.3 32-32l0-288 56 0 64 0 16 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-16 0 0-64 192 0 0 192-192 0 0-32-64 0 0 48c0 26.5 21.5 48 48 48l224 0c26.5 0 48-21.5 48-48l0-224c0-26.5-21.5-48-48-48L368 0c-26.5 0-48 21.5-48 48l0 80-76.9 0-65.9 0c-33.7 0-64.9 17.7-82.3 46.6l-58.3 97c-9.1 15.1-4.2 34.8 10.9 43.9s34.8 4.2 43.9-10.9L120 256.9 120 480c0 17.7 14.3 32 32 32s32-14.3 32-32z"/></svg>
                <div>Instructor</div>
            </ul>
        </li>
        <li class="nav-btn">
            <ul><a href="../access-pages/sign-in-te.php"><button id="tr-s-btn">Logout</button></a></ul>
        </li>
    </nav>
    <main>
        <div class="hero">
            <div class="hero-img-logo-cont">
                <img src="../assets/logo.png" alt="Main Logo">
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

        <div class="courses">
            <div class="abt-std-top mrg-btm">
                <span class="abt-std-in">Your Cources</span>
                <span class="abt-std-btn flex add-crs"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg> Add</span>
            </div>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="course">';
                        echo '      <p class="course-title"><span id="course-num">' . $row["course_id"] . '</span> ' . $row["course_title"] . '</p>';
                        echo '      <div class="course-progess">';

                        // Start Attendance Form
                        echo '         <form action="scan-qr.php" method="GET">';
                        echo '            <input type="hidden" name="course_id" value="' . $row["id"] . '">';
                        echo '            <button type="submit" class="c-h">';
                        echo '               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg> Start Attendance
                        </button>';
                        echo '         </form>';

                        // View Records Form
                        echo '         <form action="attendance-view.php" method="GET">';
                        echo '            <input type="hidden" name="course_id" value="' . $row["id"] . '">';
                        echo '            <button type="submit" name="view_records" value="' . $row["id"] . '" class="c-a">';
                        echo '               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg> View records
                        </button>';
                        echo '         </form>';

                        // Delete Class Form
                        echo '         <form method="POST">';
                        echo '            <button type="submit" name="delete_course" value="' . $row["id"] . '" class="c-m delete-course">';
                        echo '               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg> Delete Class
                        </button>';
                        echo '         </form>';

                        echo '      </div>';
                        echo '</div>';
                    }
                }
            ?>




            <!--
            <div class="course">
                <p class="course-title"><span id="couse-num">01</span> Computer Ethics</p>
                <div class="course-progess">
                    <div class="c-h"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg> Start Attendance</div>
                    <div class="c-a"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg> View records</div>
                    <div class="c-m"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg> Delete Class</div>
                </div>
            </div>
            -->

        </div>

        <div class="abt-std">
            <div class="abt-std-top">
                <span class="abt-std-in">Instructor Information</span>
                <span class="abt-std-btn info-edit">Edit</span>
            </div>
            <div class="center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
            </div>
            <div class="abt-std-info"><span>Name</span><span><?php echo htmlspecialchars($user_data['name']); ?></span></div>
            <div class="abt-std-info"><span>ID</span><span><?php echo htmlspecialchars($user_data['user_name']); ?></span></div>
            <div class="abt-std-info"><span>E-mail</span><span><?php echo htmlspecialchars($user_data['email']); ?></span></div>
        </div>

        <div class="add-course-form animate__fadeInDown">
            <form method="POST">
                <label for="course-id">
                    Course Id:
                    <input type="text" id="course-id" required placeholder="Course ID" name="course_id">
                </label>
                <label for="course-title">
                    Course Title:
                    <input type="text" id="course-title" required placeholder="Course Title" name="course_title">
                </label>
                <input type="submit" value="Add">
            </form>
        </div>

        <div class="edit-user-form animate__fadeInDown">
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
    <script src="../script.js"></script>
</body>
</html>