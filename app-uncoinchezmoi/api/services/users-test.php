<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require '../model/users.php';
    require '../model/config.php';

    $users = getUsers($pdo);

    echo json_encode($users);
?>
