<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';
include_once '../model/user.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);

$users = $user->getUsers();
//var_dump($users);
echo json_encode([$users]);