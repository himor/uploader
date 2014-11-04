CREATE TABLE IF NOT EXISTS `user` (
`id` BIGINT AUTO_INCREMENT PRIMARY KEY,
`email` VARCHAR(255) NOT NULL,
`password` VARCHAR(128) NOT NULL,
`active` BOOL DEFAULT 0,
`is_deleted` BOOL DEFAULT 0,
`is_admin` BOOL DEFAULT 0,
`token` VARCHAR(64),
`created_at` BIGINT NOT NULL DEFAULT 0
) default charset=utf8;

CREATE TABLE IF NOT EXISTS `picture` (
`id` BIGINT AUTO_INCREMENT PRIMARY KEY,
`user_id` BIGINT NOT NULL,
`title` VARCHAR(255) NOT NULL,
`name` VARCHAR(255) NOT NULL,
`created_at` BIGINT NOT NULL DEFAULT 0,
FOREIGN KEY(user_id) REFERENCES user(id)
) default charset=utf8;

INSERT INTO `user`
(`email`, `password`, `is_admin`, `active`)
VALUES
('ADMIN@ADMIN.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 1, 1);