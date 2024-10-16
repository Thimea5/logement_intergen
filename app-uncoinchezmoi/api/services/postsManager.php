<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Authorization");
  header('Content-Type: application/json');

  include_once '../config/database.php';
  include_once '../model/post.php';
  include_once '../model/host.php';

  $database = new Database();
  $db = $database->connect();

  $postModel = new Post($db);

  $posts = $postModel->getPostsWithHostInformations();

  //var_dump($posts);

  if ($posts) {
    echo json_encode(['success' => true, 'posts' => $posts]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Aucun post trouvé.']);
  }
?>
