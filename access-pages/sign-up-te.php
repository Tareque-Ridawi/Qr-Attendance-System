<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = trim($_POST['full_name']);
    $user_id = trim($_POST['user_id']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);

    if (!empty($user_name) && !empty($user_id) && !empty($email) && !empty($pass)) {
        // Check if username already exists
        $check_query = "SELECT * FROM users WHERE user_name = '$user_id'";
        $result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($result) > 0) {
            echo "exists"; // username taken
            exit();
        }

        // Insert new instructor
        $query = "INSERT INTO users (user_name, password, name, instructor, email) 
                  VALUES ('$user_id', '$pass', '$user_name', 1, '$email')";

        if (mysqli_query($con, $query)) {
                header("Location: ../access-pages/sign-in-te.php");
        } else {
            echo "error: " . mysqli_error($con);
        }
    } else {
        echo "invalid";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Sign In</title>
    <link rel="stylesheet" href="../style.css"/>
    <style>
        body{
            height: 100vh;
            width:100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.4)), url("../assets/bg-members.png");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
    </style>
</head>
<body>
    <form id="teacher-sign-up form" class="form-class" method="POST" autocomplete="off">
        <h1 class="title">Sign Up Page for <span class="hightlight">Instructor</span></h1>
        <p class="alter">If you are a <span class="hightlight">Student</span> Sign Up <a href="sign-up-st.php">Here</a> </p>
        <label for="teacher-full-name">
            Enter Full Name:
            <input type="text" id="teacher-full-name" required placeholder="Full Name" name="full_name">
        </label>
        <label for="teacher-user-name">
            Enter Unique Username:
            <input type="text" id="teacher-user-name" required placeholder="@userid" name="user_id">
        </label>
        <label for="teacher-email">
            Enter E-mail Address:
            <input type="email" id="teacher-email" required placeholder="user@domain.extn" name="email">
        </label>
        <label for="teacher-password">
            Enter New Password:
            <input type="password" id="teacher-password" required placeholder="password" name="pass">
        </label>
        <label for="teacher-password-repeat">
            Repeat Password:
            <input type="password" id="teacher-password-repeat" required placeholder="password">
        </label>
        <label for="agree-to-rules">
            <input type="checkbox" id="agree-to-rules" required>
            I agree to the <a href="../terms-and-conditions.html">terms and conditons.</a>
        </label>
        <input type="submit" value="Register">
        <p class="sign-up">Already have an account? <a href="sign-in-te.php">Sign In</a></p>
    </form>
    <script src="validate.js"></script>
</body>
</html>