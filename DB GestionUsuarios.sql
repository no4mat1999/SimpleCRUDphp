CREATE DATABASE gestionusuarios;

USE gestionusuarios;

CREATE TABLE `usuarios` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(100) NOT NULL
);