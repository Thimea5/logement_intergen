<?php
    $host = 'localhost';
    $db = 'db-uncoinchezmoi';
    $user = 'root';  // MySQL, root sans mot de passe
    $pass = ''; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
?>