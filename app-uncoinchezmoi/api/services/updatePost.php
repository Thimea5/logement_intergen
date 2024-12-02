<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';
include_once '../model/service.php';
include_once '../model/post.php';

$database = new Database();
$db = $database->connect();

$services = new Service($db);
$post = new Post($db);

$data = json_decode(file_get_contents('php://input'), associative: true);

if ($data === null) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$idPost = isset($data['idPost']) ? $data['idPost'] : null;
$city = isset($data['city']) ? $data['city'] : null;
$address = isset($data['address']) ? $data['address'] : null;
$postalCode = isset($data['postalCode']) ? $data['postalCode'] : null;
$lat = isset($data['lat']) ? $data['lat'] : null;
$lng = isset($data['lng']) ? $data['lng'] : null;
$price = isset($data['price']) ? $data['price'] : null;
$size = isset($data['size']) ? $data['size'] : null;
$type_logement = isset($data['type_logement']) ? $data['type_logement'] : null;
$description = isset($data['description']) ? $data['description'] : null;

if ($post->updatePost($city, $postalCode, $address, $lat, $lng, $type_logement, $description, $price, $size, $idPost)) {
    echo json_encode(["success" => true, "message" => "OH YEAH BABY"]);
} else {
    echo json_encode(["success" => false, "message" => "Why are we still here... just to suffer..."]);
}
