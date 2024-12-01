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
$post = new Post($db);

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
$gender = $data['gender'] ?:  '';
$telephone = $data['telephone'] ?:  '';
$maritalStatus = $data['maritalStatus'] ?:  '';
$photo = 'lien_photo';
$type = $data['type'] ?:  'guest';

$city = $data['city'] ?: '';
$postal_code = $data['postal_code'] ?: '';
$address = $data['address'] ?: '';
$lat = $data['lat'] ?: 45.7;
$lng = $data['lng'] ?: 4.8;
$type_logement = $data['type_logement'] ?: 'Maison';
$size = $data['size'] ?: 0;
$description = $data['description'] ?: '';
$price = $data['price'] ?: 0;


$sd = $data['services'] ?:  [false, false, false, false, false, false, false, false];

$userInserted = $user->insertUser($email, $password, $firstName, $lastName, $birthDate, $gender, $telephone, $maritalStatus, $photo, $type, 1);
$idUser = $userInserted["id"];

if ($userInserted["status"]) {
    if ($type === 'host') {
        if($post->insertPost($city, $postal_code, $address, $lat, $lng, "host_photo".$idUser, $type_logement, $description, $price, $size, 4, 1, $idUser)
            && $services->insertServices($sd[0], $sd[1], $sd[2], $sd[3], $sd[4], $sd[5], $sd[6], $sd[7], $idUser)) {
            echo json_encode(["success" => true, "message" => "Utilisateur créé en tant que host"]);
        }
    } else if($services->insertServices($sd[0], $sd[1], $sd[2], $sd[3], $sd[4], $sd[5], $sd[6], $sd[7], $idUser)) {
        echo json_encode(["success" => true, "message" => "Utilisateur créé en tant que guest"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Utilisateur flingué en base"]);
}