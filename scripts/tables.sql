create table users (
    id int unsigned AUTO_INCREMENT PRIMARY KEY,
    pseudo varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    photo varchar(255),
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

create table posts (
    id int unsigned AUTO_INCREMENT PRIMARY KEY,
    title varchar(255) NOT NULL,
    content text NOT NULL,
    user_id int unsigned NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

create table replies (
    id int unsigned AUTO_INCREMENT PRIMARY KEY,
    content text NOT NULL,
    type int,
    user_id int unsigned NOT NULL,
    post_id int unsigned NOT NULL,
    reply_id int unsigned DEFAULT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);
