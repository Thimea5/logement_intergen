<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';
include_once '../model/user.php';
include_once '../model/post.php';

$database = new Database();
$db = $database->connect();

$post = new Post($db);

$id = $_GET["id"];

if ($id === null) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$post_data = $post->getPostByHostId($id);

echo json_encode($post_data);
