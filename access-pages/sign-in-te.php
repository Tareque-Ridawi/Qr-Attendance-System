<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_id'];
    $pass = $_POST['pass'];

    if (!empty($user_name) && !empty($pass)) {
        $query = "SELECT * FROM users WHERE user_name = '$user_name' AND password = '$pass' AND instructor = 1 LIMIT 1";
        $result = mysqli_query($con, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $_SESSION['user_id'] = $user_name;
            echo "Login successful!";
            // Redirect to dashboard or homepage
            header("Location: ../client-pages/instructor-panel.php");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Please enter both username and password.";
    }
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
    <header>
        
    </header>
    <form id="teacher-sign-in" class="form-class" method="POST" autocomplete="off">
        <h1 class="title">Log in Page for <span class="hightlight">Instructor</span></h1>
        <p class="alter">If you are a <span class="hightlight">Student</span> Log in <a href="sign-in-st.php">Here</a> </p>
        <label for="teacher-user-name">
            Enter Username:
            <input type="text" id="teacher-user-name" required placeholder="@userid" name="user_id">
        </label>
        <label for="teacher-password">
            Enter Password:
            <input type="password" id="teacher-password" required placeholder="password" name="pass">
        </label>
        <input type="submit" value="Log In">
        <p class="sign-up">Don't have an account? <a href="sign-up-te.php">Sign Up</a></p>
    </form>
</body>
</html>