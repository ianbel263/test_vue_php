<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/post.php';

$database = new Database();
$db = $database->getConnection();

$post = new Post($db);

$stmt = $post->read_all();
$num = $stmt->rowCount();

if ($num > 0) {
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(['posts'=> $posts]);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Посты не найдены."), JSON_UNESCAPED_UNICODE);
}
