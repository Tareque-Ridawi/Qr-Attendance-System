<?php
session_start();
include("../includes/connection.php");
include("../includes/functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_id'];
    $pass = $_POST['pass'];

    if (!empty($user_name) && !empty($pass)) {
        $user_name = mysqli_real_escape_string($con, $user_name);
        $query = "SELECT * FROM users WHERE user_name = '$user_name' AND instructor = 0 LIMIT 1";
        $result = mysqli_query($con, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $password_valid = password_verify($pass, $user_data['password']);

            // Upgrade passwords created before password hashing was enabled.
            if (!$password_valid && hash_equals((string) $user_data['password'], $pass)) {
                $password_hash = mysqli_real_escape_string($con, password_hash($pass, PASSWORD_DEFAULT));
                mysqli_query($con, "UPDATE users SET password = '$password_hash' WHERE id = '{$user_data['id']}'");
                $password_valid = true;
            }

            if ($password_valid) {
                $_SESSION['user_id'] = $user_name;
                echo "Login successful!";
                header("Location: ../client-pages/student-dash.php");
                exit();
            }
        }

        if (!$result || mysqli_num_rows($result) === 0 || !$password_valid) {
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
    <title>Student Sign In</title>
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
    <form id="student-sign-in" class="form-class" method="POST" autocomplete="off">
        <h1 class="title">Log in Page for <span class="hightlight">Students</span></h1>
        <p class="alter">If you are a <span class="hightlight">Instructor</span> Log in <a href="sign-in-te.php">Here</a> </p>
        <label for="student-user-name">
            Enter Username:
            <input type="text" id="student-user-name" required placeholder="@userid" name="user_id">
        </label>
        <label for="student-password">
            Enter Password:
            <input type="password" id="student-password" required placeholder="password" name="pass">
        </label>
        <input type="submit" value="Log In">
        <p class="sign-up">Don't have an account? <a href="sign-up-st.php">Sign Up</a></p>
    </form>
</body>
</html>