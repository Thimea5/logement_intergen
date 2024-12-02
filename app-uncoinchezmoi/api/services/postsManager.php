<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Authorization");
  header('Content-Type: application/json');

  include_once '../config/database.php';
  include_once '../model/post.php';
  include_once '../model/service.php';

  $database = new Database();
  $db = $database->connect();

  $postModel = new Post($db);
  $serviceModel = new Service($db);

  $posts = $postModel->getPostsWithHostInformations();
  
  //var_dump($posts);
  $services = [];
  for ($i=0; $i<count($posts); ++$i) {
    array_push($services, $serviceModel->getServicesByPost($posts[$i]['post_id']));
  }

  //var_dump($services);

  if ($posts) {
    echo json_encode(['success' => true, 'posts' => $posts, 'services' => $services]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Aucun post trouvé.']);
  }
?>
