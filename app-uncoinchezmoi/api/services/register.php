<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';
include_once '../model/user.php';
include_once '../model/service.php';
include_once '../model/post.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);
$services = new Service($db);

$data = json_decode(file_get_contents('php://input'), associative: true);

if ($data === null) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$email = $data['mail'] ?:  '';
$password = password_hash($data['password'] ?:  '', PASSWORD_DEFAULT);
$firstName = $data['firstName'] ?:  '';
$lastName = $data['lastName'] ?:  '';
$birthDate = $data['birthDate'] ?:  null;
$telephone = $data['telephone'] ?:  '';
$gender = $data['gender'] ?:  '';
$maritalStatus = $data['maritalStatus'] ?:  '';
$photo = 'lien_photo';
$type = $data['type'] ?:  'guest';

$services = $data['services'] ?:  [false, false, false, false, false, false, false, false];
//var_dump($email, $password, $firstName, $lastName, $birthDate, $telephone, $gender, $maritalStatus, $photo, $type, $services);

if ($user->insertUser($email, $password, $firstName, $lastName, $birthDate, $telephone, $gender, $maritalStatus, $photo, $type, 1)) {
    echo json_encode(["success" => true, "message" => "Utilisateur créé en tant que guest"]);
} else {
    echo json_encode(["success" => false, "message" => "Utilisateur flingué en base"]);
}