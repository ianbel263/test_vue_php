<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../config/database.php');
require_once('../objects/post.php');
require_once('../objects/user.php');

if (!User::is_auth()) {
    http_response_code(403);
    die();
}

$database = new Database();
$db = $database->getConnection();

$post = new Post($db);
$post_data = $_POST;
$post_data += ['userID' => $_SESSION['userID']];
$post->set_data($post_data);

if ($post->create()) {
    http_response_code(201);
    echo json_encode(['result' => 'success']);

} else {
    http_response_code(403);
    echo json_encode(['errors' => $post->get_errors()]);
}
