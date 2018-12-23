DROP DATABASE IF EXISTS `doingsdone`;

CREATE DATABASE `doingsdone`
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE `doingsdone`;


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` CHAR(255) NOT NULL,
  `email` CHAR(255) NOT NULL UNIQUE,
  `password` CHAR(64) NOT NULL,
  `register_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX `users_name_index` ON `users`(`name`);


DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` CHAR(255) NOT NULL,
  `author_id` INT(11) UNSIGNED,
   CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX `projects_name_index` ON `projects`(`author_id`, `name`);


DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `creation_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_date` DATETIME,
  `deadline` DATETIME,
  `status` ENUM('0', '1') DEFAULT '0',
  `name` TEXT NOT NULL,
  `file` VARCHAR(1000),
  `author_id` INT(11) UNSIGNED,
  `project_id` INT(11) UNSIGNED,
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users`(`id`),
  CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX `tasks_deadline_index` ON `tasks`(`deadline`);
CREATE INDEX `tasks_status_deadline_index` ON `tasks`(`status`, `deadline`);
CREATE FULLTEXT INDEX `tasks_name_index` ON `tasks`(`name`);
