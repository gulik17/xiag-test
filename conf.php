<?php

$link = mysqli_connect("localhost", "root", "", "xiag");

if (mysqli_connect_errno()) {
    $result = [
        'error' => true,
        'desc' => "Ошибка: " . mysqli_connect_error()
    ];
    echo json_encode($result);
    exit();
}