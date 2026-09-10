<?php
function check_login($con)
{
    if (isset($_SESSION['user_id'])) {
        $user_name = $_SESSION['user_id']; // Using user_id to store user_name
        $query = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result); // Fetch user data first

            if ($user_data['instructor'] == 0) { // Now check the instructor field
                return $user_data;
            }
        }
    }
    header("Location: ../access-pages/sign-in-st.php");
    exit();
}

function check_loginins($con)
{
    if (isset($_SESSION['user_id'])) {
        $user_name = $_SESSION['user_id']; // Using user_id to store user_name
        $query = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result); // Fetch user data first

            if ($user_data['instructor'] == 1) { // Now check the instructor field
                return $user_data;
            }
        }
    }
    header("Location: ../access-pages/sign-in-te.php");
    exit();
}
?>
