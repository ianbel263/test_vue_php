<?php
require_once('../../helpers/functions.php');

if (session_id() == '') {
    session_start();
}

class User {
    private $conn;
    private $table_name = 'user';
    private $data;
    private $errors = array();

    public function __construct($db) {
        $this->conn = $db;
    }

    public function get_data() {
        return [
            'id' => $this->data['id'],
            'username' => $this->data['username'],
            'nickname' => $this->data['nickname'],
            'email' => $this->data['email'],
            'phone' => $this->data['phone'],
            'birthday' => $this->data['birthday_at'],
            'firstName' => $this->data['first_name'],
            'middleName' => $this->data['middle_name'],
            'lastName' => $this->data['last_name'],
        ];
    }

    public function set_data($data) {
        $this->data = $data;
    }

    public static function is_auth(): bool {
        return isset($_SESSION['userID']) && $_SESSION['userID'] != '';
    }

    public function get_errors() {
        return $this->errors;
    }

    function register(): bool {
        $res = null;
        $this->errors = validate($this->data);

        if (count($this->errors)) {
            return false;
        }

        $user = $this->get_user();

        if ($user) {
            $this->errors['username'] = 'Пользователь с таким логином уже зарегистрирован';
            return false;
        } elseif ($this->data['password1'] != $this->data['password2']) {
            $this->errors['password'] = 'Введенные пароли не совпадают';
            return false;
        } else {
            $password = password_hash($this->data['password1'], PASSWORD_DEFAULT);
            $query = "INSERT INTO " . $this->table_name . "
                        SET 
                            username=:username, 
                            password=:password, 
                            nickname=:nickname,
                            email=:email,
                            phone=:phone,
                            first_name=:firstName,
                            last_name=:lastName,
                            middle_name=:middleName";
            $stmt = $this->conn->prepare($query);

            foreach ($this->data as $key => $value) {
                $this->data[$key] = htmlspecialchars(strip_tags($value));
            }

            $stmt->bindParam(":username", $this->data['username']);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":nickname", $this->data['nickname']);
            $stmt->bindParam(":email", $this->data['email']);
            $stmt->bindParam(":phone", $this->data['phone']);
            $stmt->bindParam(":firstName", $this->data['firstName']);
            $stmt->bindParam(":lastName", $this->data['lastName']);
            $stmt->bindParam(":middleName", $this->data['middleName']);
            if ($stmt->execute()) {
                $_SESSION['userID'] = 'newUser'; // Получить id нового юзера
                return true;
            }
            return false;
        }
    }

    public function login(): bool {
        $user = $this->check_credentials();
        if ($user) {
            $this->data = $user;
            $_SESSION['userID'] = $this->data['id'];
            $this->update_last_login();
            return true;
        }
        return false;
    }

    public static function logout() {
        $_SESSION = [];
        header('Location: /');
    }

    private function check_credentials() {
        $user = $this->get_user();
        $this->errors = validate($this->data);
        if (count($this->errors)) {
            return false;
        }

        if ($user) {
            if (password_verify($this->data['password'], $user['password'])) {
                return $user;
            } else {
                $this->errors['password'] = 'Неверный пароль';
                return false;
            }
        } else {
            $this->errors['username'] = 'Такой пользователь не найден';
            return false;
        }
    }

    private function get_user() {
        $query = 'SELECT * FROM user WHERE username = ?';
        $stmt = $this->conn->prepare($query);
        $this->data['username'] = htmlspecialchars(strip_tags($this->data['username']));
        $stmt->bindParam(1, $this->data['username']);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function update_last_login() {
        $query = 'UPDATE user SET last_login_at = NOW() WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $this->data['id'] = htmlspecialchars(strip_tags($this->data['id']));
        $stmt->bindParam(1, $this->data['id']);
        $stmt->execute();
    }
}