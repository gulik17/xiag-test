<?php

// BEGIN -- ENTER YOUR DATABASE SETTINGS
$host = "localhost"; // Host name
$user = "root"; // User name
$pass = ""; // Password
$db_name = "xiag"; // DataBase name
// END

$link = mysqli_connect($host, $user, $pass, $db_name);
if (mysqli_connect_errno()) {
    $result = [
        'error' => true,
        'desc' => "Error: " . mysqli_connect_error()
    ];
    echo json_encode($result);
    exit();
}
