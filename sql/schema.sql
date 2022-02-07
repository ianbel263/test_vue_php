DROP DATABASE IF EXISTS first_bit;
CREATE DATABASE first_bit
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE first_bit;

CREATE TABLE user (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(255) NOT NULL UNIQUE,
    password      CHAR(255)    NOT NULL,
    nickname      VARCHAR(255),
    is_active     BOOLEAN   DEFAULT 1,
    email         VARCHAR(255),
    phone         CHAR(11),
    first_name    VARCHAR(64) NOT NULL,
    middle_name   VARCHAR(64),
    last_name     VARCHAR(64) NOT NULL,
    gender        enum ('m', 'f'),
    birthday_at   DATE      DEFAULT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login_at DATETIME  DEFAULT NULL
);

CREATE TABLE post (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_id  INT UNSIGNED NOT NULL,
    image_url  VARCHAR(255),
    heading    VARCHAR(64) NOT NULL,
    body       TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE post
    ADD CONSTRAINT post_author_id_fk
        FOREIGN KEY (author_id) REFERENCES user(id)
            ON DELETE CASCADE;
