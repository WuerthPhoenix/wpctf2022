-- CREATE DATABASE fileshare;
USE fileshare;

GRANT ALL PRIVILEGES ON fileshare.* TO 'fsuser'@'%';
FLUSH PRIVILEGES;

DROP TABLE users;
CREATE TABLE users (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user varchar(32) NOT NULL,
    pass varchar(255) NOT NULL,
    full_name varchar(255) NOT NULL,
    group_id int not null,
    permissions varchar(512) NOT NULL
);

INSERT INTO users VALUES (1, 'admin238', 'Y0uW1lln3v3rBrut3f0rc3th15on3', 'Root Smith', 1, '0|1|0|1|1');
INSERT INTO users VALUES (928476593, 'admin7321034747', 'asd', 'WP{R00t_Sm1th}', 928476593, '1|1|1|1|1');
INSERT INTO users VALUES (47, 'user047', 'asd', 'John Doe', 1234, '0|1|0|0|1');

DROP TABLE files;
CREATE TABLE files (
    id int auto_increment,
    name varchar(255) not null,
    size int not null,
    path varchar(512) not null,
    user_id varchar(32) not null,
    group_id varchar(32) not null,
    created_at timestamp default current_timestamp,
    primary key(id)
);

INSERT INTO files VALUES (NULL, 'WP{d0cum4nt5_l34k_s3nsitiv3_inf0}.pdf', 21700, '1234/WP{d0cum4nt5_l34k_s3nsitiv3_inf0}.pdf', 47, 1234, NULL);
INSERT INTO files VALUES (NULL, 'final-developer-documentation.pdf', 21700, '0/api-doc.pdf', 1, 1, NULL);

DROP TABLE mfa_checks;
CREATE TABLE mfa_checks (
   id int auto_increment,
   passed bool default false,
   user_id varchar(32) not null,
   primary key(id)
);

INSERT INTO mfa_checks VALUES (123, 1, 46);
INSERT INTO mfa_checks VALUES (1923, 1, 46);
DELETE FROM mfa_checks WHERE id = 1923;

DROP TABLE access_logs;
CREATE TABLE access_logs (
    id int auto_increment,
    ip varchar(16) not null,
    url varchar(64) not null,
    method varchar(16) not null,
    accessed timestamp default current_timestamp,
    primary key(id)
);

INSERT INTO access_logs VALUES (NULL, '1.2.3.4', '/api/login.php', 'GET', '2022-09-16 10:45:05');
INSERT INTO access_logs VALUES (NULL, '3.56.12.9', '/api/files.php', 'GET', '2022-09-16 10:51:15');
INSERT INTO access_logs VALUES (NULL, '19.43.12.32', '/api/users.php', 'GET', '2022-09-17 9:45:05');
INSERT INTO access_logs VALUES (NULL, '251.82.59.91', '/api/users.php', 'GET', '2022-09-17 14:31:01');
INSERT INTO access_logs VALUES (NULL, '251.82.59.91', '/api/filesdownloadall.php?flag=WP{pl34se_r3m0v3_l3gacy_stuff}', 'GET', '2022-09-18 00:47:47');
INSERT INTO access_logs VALUES (NULL, '31.47.238.1', '/api/fileupload.php', 'POST', '2022-10-01 01:04:05');
