<?php
require_once('../../helpers/functions.php');

class Post {
    private $conn;
    private $table_name = "post";
    private $data;
    private $errors;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function set_data($data) {
        $this->data = $data;
    }

    public function get_errors() {
        return $this->errors;
    }

    function read_all() {
        $query = "SELECT p.id, u.nickname, p.image_url, p.heading, p.body, p.created_at
                    FROM " . $this->table_name . " p
                    JOIN user u
                    ON p.author_id = u.id
                    ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function create() {
        $this->errors = validate($this->data);
        $file_data = validate_file(count($this->errors));
        $this->errors = array_merge($this->errors, $file_data['errors']);
        $file_url = $file_data['url'];

        if (count($this->errors)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . "
                    SET author_id=:author_id, image_url=:image_url, heading=:heading, body=:body";
        $stmt = $this->conn->prepare($query);

        foreach ($this->data as $key => $value) {
            $this->data[$key] = htmlspecialchars(strip_tags($value));
        }

        $stmt->bindParam(":author_id", $this->data['userID']);
        $stmt->bindParam(":image_url", $file_url);
        $stmt->bindParam(":heading", $this->data['heading']);
        $stmt->bindParam(":body", $this->data['body']);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

//    function delete() {
//        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
//        $stmt = $this->conn->prepare($query);
//        $this->id = htmlspecialchars(strip_tags($this->data['id']));
//        $stmt->bindParam(1, $this->data['id']);
//        if ($stmt->execute()) {
//            return true;
//        }
//        return false;
//    }
}