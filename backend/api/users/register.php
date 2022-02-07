<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../config/database.php');
require_once('../objects/user.php');

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$user->set_data($data['user']);

if ($user->register()) {
    http_response_code(201);
    echo json_encode(['user' => $user->get_data()]);

} else {
    http_response_code(403);
    echo json_encode(['errors' => $user->get_errors()]);
}
