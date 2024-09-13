<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require 'config.php';

    $query = $pdo->query("SELECT * FROM users");
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
?>
