INSERT INTO user (username, password, first_name, middle_name, last_name)
VALUES ('test', 'secret', 'Иван', 'Иванович', 'Васильев'),
       ('test2', 'secret', 'Петр', 'Григорьевич', 'Иванов'),
       ('test3', 'secret', 'Алексей', 'Игоревич', 'Петров'),
       ('test4', 'secret', 'Андрей', 'Васильевич', 'Антонов'),
       ('test5', 'secret', 'Василий', 'Леонидович', 'Григорьев'),
       ('test6', 'secret', 'Дарья', 'Петровна', 'Лобанова');

INSERT INTO post (author_id, image_url, heading, body)
VALUES (3, '/img', 'Заголовок поста1', 'ыво лыфола лыовалф лаофлв лфвоыалы'),
       (6, '/img', 'Заголовок поста2', 'Григорьфывафыевич'),
       (6, '/img', 'Заголовок поста3', 'Игоревич'),
       (1, '/img', 'Заголовок поста4', 'Васильевич'),
       (6, '/img', 'Заголовок поста5', 'Леонидович'),
       (1, '/img', 'Заголовок поста6', 'Петровна');

# ALTER TABLE user
#     ADD (birthday_at DATE DEFAULT NULL,
#          gender enum ('m', 'f'),
#          email VARCHAR(255),
#          phone CHAR(11)
#         );

UPDATE user
SET gender = 'm'
WHERE id BETWEEN 1 AND 5;

UPDATE user
SET gender = 'f'
WHERE id = 6;

UPDATE user
SET birthday_at = '1970-09-22', email = '1@mail.ru', phone = '79261112233'
WHERE id = 1;

UPDATE user
SET birthday_at = '1980-03-02', email = '2@mail.ru', phone = '79161112233'
WHERE id = 2;

UPDATE user
SET birthday_at = '1986-04-24', email = '3@mail.ru', phone = '79561112233'
WHERE id = 3;

UPDATE user
SET birthday_at = '1990-01-12', email = '4@mail.ru', phone = '79051112233'
WHERE id = 4;

UPDATE user
SET birthday_at = '1992-06-04', email = '5@mail.ru', phone = '79011112233'
WHERE id = 5;

UPDATE user
SET birthday_at = '1966-05-05', email = '6@mail.ru', phone = '79031112233'
WHERE id = 6;

ALTER TABLE user
    ADD (last_login_at DATETIME DEFAULT NULL,
         is_active BOOLEAN DEFAULT 1,
         nickname VARCHAR(255)
        );

TRUNCATE user;

SELECT u.nickname, p.created_at, p.image_url, p.heading, p.body
FROM post p
JOIN user u
ON p.author_id = u.id
ORDER BY p.created_at DESC;

DELETE FROM post WHERE id = 1;