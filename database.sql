CREATE DATABASE php_mvc;

CREATE TABLE users(
    id VARCHAR(255) PRIMARY KEY ,
    name VARCHAR(255) NOT NULL ,
    password VARCHAR(255) NOT NULL,
    role INT NOT NULL
) ENGINE InnoDB;

CREATE TABLE sessions(
    id VARCHAR(255) PRIMARY KEY ,
    user_id VARCHAR(255) NOT NULL
)ENGINE InnoDB;

ALTER TABLE sessions
ADD CONSTRAINT fk_sessions_user
    FOREIGN KEY (user_id)
        REFERENCES users(id);