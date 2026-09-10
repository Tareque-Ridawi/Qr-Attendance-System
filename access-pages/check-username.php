<?php
include("../includes/connection.php");

if (isset($_GET['user_id'])) {
    $user_id = trim($_GET['user_id']);
    $check_query = "SELECT user_name FROM users WHERE user_name = '$user_id'";
    $result = mysqli_query($con, $check_query);

    echo (mysqli_num_rows($result) > 0) ? "taken" : "available";
}
?>
