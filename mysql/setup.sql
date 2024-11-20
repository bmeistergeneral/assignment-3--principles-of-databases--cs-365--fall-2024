DROP DATABASE IF EXISTS student_passwords;
CREATE DATABASE student_passwords;
DROP USER IF EXISTS 'passwords_user'@'localhost';
CREATE USER 'passwords_user'@'localhost';
GRANT ALL ON student_passwords.* TO 'passwords_user'@'localhost';

-- SET block_encryption_mode = 'aes-256-cbc';
-- SET @key_str = UNHEX(SHA2('ThisIsARegularStatement', 512));
-- SET @init_vector = 1010101010101010;

USE student_passwords;

CREATE TABLE Users (
user_id MEDIUMINT NOT NULL AUTO_INCREMENT,
first_name varchar(32) NOT NULL,
last_name varchar(32) NOT NULL,
username varchar(32) NOT NULL,
email varchar(40) NOT NULL,
PRIMARY KEY (user_id)
);

CREATE TABLE Websites {
website_id MEDIUMINT NOT NULL AUTO_INCREMENT,
website_name varchar(255) NOT NULL UNIQUE,
website_url varchar(1000) NOT NULL UNIQUE,
PRIMARY KEY (website_id)
}

CREATE TABLE Passwords (
password_id MEDIUMINT NOT NULL AUTO_INCREMENT,
user_id MEDIUMINT NOT NULL,
website_id MEDIUMINT NOT NULL,
enc_password varchar(255) NOT NULL,
comment varchar(255),
create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (password_id),
FOREIGN KEY (website_id) REFERENCES Websites(website_id)
FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

insert into Users (first_name, last_name, username, email)
values
("Brecon", "Morgan", "bmeisteriscool", "breconiusmorg@gmail.com"),
('Dylan', 'Heinen', 'dheinen88', 'heinen@hotmail.com'),
('Bob', 'Johnson', 'bjohnsonFTW', 'bob@hotmail.com');

insert into Websites(website_name, website_url) values
('Facebook', 'https://www.facebook.com'),
('Instagram', 'https://www.instagram.com'),
('Google', 'https://www.google.com')

insert into Passwords (user_id, website_id, enc_password, comment)
values
(1, 1, 'google123', 'My Google account'),
(1, 2, 'facebook149', 'My Facebook login'),
(2, 3, 'forthegram789', 'Social media IG'),
(3, 1, 'googlepwd', 'Just a Google account'),
(2, 2, 'fblogin756', 'Personal Facebook');

SELECT w.website_name, w.website_url, u.username, u.email,
       p.enc_password, p.comment, p.create_time
FROM Passwords p
JOIN Users u ON p.user_id = u.user_id
JOIN Websites w ON p.website_id = w.website_id
WHERE u.username = 'bmeisteriscool';

-- UPDATE example
UPDATE Passwords p
JOIN Websites w ON p.website_id = w.website_id
SET p.enc_password = 'newpassword123'
WHERE w.website_name = 'Google'
AND p.user_id = (SELECT user_id FROM Users WHERE username = 'bjohnsonFTW');

-- DELETE example
DELETE p FROM Passwords p
JOIN Websites w ON p.website_id = w.website_id
WHERE w.website_name = 'Facebook'
AND p.user_id = (SELECT user_id FROM Users WHERE username = 'dheinen88');
