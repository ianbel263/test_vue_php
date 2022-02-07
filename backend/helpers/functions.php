<?php

function filter_user_data($data): string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validate(array $data): array {
    $errors = [];
    foreach ($data as $key => $value) {
        switch ($key) {
            case 'username':
                $errors[$key] = empty($value) ? 'Введите логин' : null;
                break;
            case 'password':
                $errors[$key] = empty($value) ? 'Введите пароль' : null;
                break;
            case 'password1':
                $errors[$key] = empty($value) ? 'Введите пароль' : null;
                break;
            case 'password2':
                $errors[$key] = empty($value) ? 'Подтвердите пароль' : null;
                break;
            case 'nickname':
                $errors[$key] = empty($value) ? 'Введите никнэйм' : null;
                break;
            case 'lastName':
                $errors[$key] = empty($value) ? 'Введите фамилию' : null;
                break;
            case 'firstName':
                $errors[$key] = empty($value) ? 'Введите имя' : null;
                break;
            case 'heading':
                $errors[$key] = empty($value) ? 'Введите заголовок' : null;
                break;
            case 'body':
                $errors[$key] = empty($value) ? 'Введите текст' : null;
                break;
        }
    }
    return array_filter($errors);
}


function validate_file(bool $is_errors): array {
    $errors = [];
    $file_url = null;

    $file_mime_types = [
        'image/png',
        'image/jpg',
        'image/jpeg'
    ];

    if (!empty($_FILES['file']['name'])) {
        $tmp_name = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        $file_url = '/uploads/' . $file_name;

        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($file_info, $tmp_name);

        if (!in_array($file_type, $file_mime_types)) {
            $errors['file'] = 'Загрузите картинку в формате jpg/jpeg/png';
        } elseif (!$is_errors) {
            move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
        }
    }

    return [
        'errors' => $errors,
        'url' => $file_url
    ];
}
