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
    stars int unsigned DEFAULT 0,
    user_id int unsigned NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE posts
ADD FOREIGN KEY (user_id)
REFERENCES users (id)
ON DELETE CASCADE;

create table replies (
    id int unsigned AUTO_INCREMENT PRIMARY KEY,
    content text NOT NULL,
    stars int unsigned DEFAULT 0,
    user_id int unsigned NOT NULL,
    post_id int unsigned NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE replies
ADD FOREIGN KEY (user_id)
REFERENCES users (id)
ON DELETE CASCADE;

ALTER TABLE replies
ADD FOREIGN KEY (post_id)
REFERENCES posts (id)
ON DELETE CASCADE;
