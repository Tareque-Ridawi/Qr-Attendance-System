<?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "qr-code-based-attendance-system";

    if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname)){
        die("failed to connect to database");
    }
?>