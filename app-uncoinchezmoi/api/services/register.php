<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';
include_once '../model/user.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);

$data = json_decode(file_get_contents('php://input'), true);

// Vérifiez si les données ont bien été décodées
if ($data === null) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

// Extraire les données du tableau associatif
$mail = $data['mail'] ?? '';
$password = $data['password'] ?? '';
$firstName = $data['firstName'] ?? '';
$lastName = $data['lastName'] ?? '';
$birthDate = $data['birthDate'] ?? null;
$telephone = $data['telephone'] ?? '';
$gender = $data['gender'] ?? '';
$maritalStatus = $data['maritalStatus'] ?? '';
$type = $data['type'] ?? 'guest';

$passwordHashed = password_hash($password, PASSWORD_DEFAULT);

if ($user->insertUser($mail, $passwordHashed, $firstName, $lastName, $birthDate, $gender, $telephone, $maritalStatus)) {
    echo json_encode(["success" => true, "message" => "Inscription réussie."]);
} else {
    echo json_encode(["success" => false, "message" => "Inscription ratée."]);
}


?>
