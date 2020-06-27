DROP DATABASE IF EXISTs `trial`;
CREATE DATABASE `trial`;
USE `trial`;

CREATE TABLE `property_types` (
    `id` INT(10) NOT NULL,
    `title` VARCHAR(50) NOT NULL,
    `description` VARCHAR(250),
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
);

CREATE TABLE `properties` (
    `id` INT(10) NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(50) NOT NULL UNIQUE,
    `county` VARCHAR(50) NOT NULL,
    `country` VARCHAR(50) NOT NULL,
    `town` VARCHAR(50) NOT NULL,
    `description` VARCHAR(250),
    `address` VARCHAR(100) NOT NULL,
    `image_full` VARCHAR(100),
    `image_thumbnail` VARCHAR(50),
    `latitude` DECIMAL(12, 8),
    `longitude` DECIMAL(12, 8),
    `num_bedrooms` INT(10),
    `num_bathrooms` INT(10),
    `price` INT(10),
    `type` VARCHAR(10),
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME,
    `property_type_id` INT(10),
    PRIMARY KEY (`id`),
    KEY `FK_property_type_id` (`property_type_id`),
    CONSTRAINT `FK_property_type_id` FOREIGN KEY (`property_type_id`) REFERENCES `property_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);


