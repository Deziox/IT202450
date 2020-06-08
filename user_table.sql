CREATE TABLE Users (
    id int auto_increment not null,
    email varchar(100) not null unique,
    username varchar(100) not null unique,
    password varchar(32) not null,
    answered longtext not null,
    bio text,
    created timestamp default current_timestamp,
    modified timestamp default current_timestamp on update current_timestamp,
    PRIMARY KEY (id)
) CHARACTER SET utf8 COLLATE utf8_general_ci

CREATE TABLE Surveys (
    id int auto_increment not null,
    user_id int not null,
    title varchar(255) not null,
    slug varchar(255) not null,
    votes int(11) not null DEFAULT '0',
    top_1 varchar(255) not null,
    top_1_image BLOB not null,
    top_2 varchar(255) not null,
    top_2_image BLOB not null,
    bottom_1 varchar(255) not null,
    bottom_1_image BLOB not null,
    bottom_2 varchar(255) not null,
    bottom_2_image BLOB not null,
    top1_bottom1 int(11) not null DEFAULT '0',
    top1_bottom2 int(11) not null DEFAULT '0',
    top2_bottom1 int(11) not null DEFAULT '0',
    top2_bottom2 int(11) not null DEFAULT '0',
    published tinyint(1) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp,
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES Users(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci

