-- DROP DATABASE IF EXISTS student_passwords;

CREATE DATABASE student_passwords;

-- DROP USER IF EXISTS 'passwords_user'@'localhost';

CREATE USER 'passwords_user'@'localhost';
GRANT ALL ON student_passwords.* TO 'passwords_user'@'localhost';

SET block_encryption_mode = 'aes-256-cbc';
SET @key_str = UNHEX(SHA2('ThisIsARegularStatement', 512));
SET @init_vector = 1010101010101010;

USE student_passwords;

