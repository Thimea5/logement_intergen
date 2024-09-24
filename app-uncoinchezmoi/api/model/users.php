<?php

function getUsers($pdo) {
    $query = $pdo->query("SELECT * FROM users");
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    return $users;
}




?>