# PHP_Calendar
Pure PHP Calendar with no libraries

*Creating database

CREATE SCHEMA `calendar` ;

*Creating main tables

CREATE TABLE `calendar`.`events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description` TEXT NULL,
  `event_date` DATE NULL,
  `event_time` TIME NULL,
  `event_create` DATETIME NULL,
  `event_last_update` DATETIME NULL,
  `event_owner` INT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `calendar`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NULL,
  `lastname` TEXT NULL,
  `email` TEXT NULL,
  `password` TEXT NULL,
  `user_create_date` DATETIME NULL,
  `user_last_update` DATETIME NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `calendar`.`event_participants` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event` INT NULL,
  `user` INT NULL,
  PRIMARY KEY (`id`));

*Add foreign key to some columns

ALTER TABLE events ADD CONSTRAINT FOREIGN KEY (event_owner) REFERENCES users (id);

ALTER TABLE event_participants ADD CONSTRAINT FOREIGN KEY (event) REFERENCES events (id);

ALTER TABLE event_participants ADD CONSTRAINT FOREIGN KEY (user) REFERENCES users (id);

*Insert users to test

INSERT INTO `calendar`.`users` (`name`, `lastname`, `email`, `password`, `user_create_date`, `user_last_update`) VALUES ('Rafael', 'Castro', 'rafael@usuario.com', '123123', '2018-05-15 09:12:22', '');

INSERT INTO `calendar`.`users` (`name`, `lastname`, `email`, `password`, `user_create_date`) VALUES ('Verônica', 'Castro', 'veronica@usuario.com', '321321', '2018-05-15 09:13:45');
