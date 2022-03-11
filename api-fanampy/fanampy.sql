-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 01 nov. 2021 à 07:50
-- Version du serveur :  10.3.31-MariaDB-0ubuntu0.20.04.1
-- Version de PHP : 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+03:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Base de données: `FANAMPY`
CREATE DATABASE IF NOT EXISTS `fanampy`;
USE `fanampy`;

-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS `membres`(
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255) NOT NULL,
    `prenoms` VARCHAR(255) NOT NULL,
    `adresse` VARCHAR(255) NOT NULL,
    `phone1` VARCHAR(255) NOT NULL,
    `phone2` VARCHAR(255),
    `email` VARCHAR(255) NOT NULL,
    `dateNaissance` DATE NOT NULL,
    `lieuNaissance` VARCHAR(255) NOT NULL,
    `villeOrigine` VARCHAR(255),
    `date_debut` DATETIME DEFAULT NOW(),
    `date_fin` DATETIME DEFAULT NULL,
    `keypass` VARCHAR(255) DEFAULT NULL,
    `active` INT(11) DEFAULT 1
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `formations`(
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255) NOT NULL,
    `etablissement` VARCHAR(255) NOT NULL,
    `descriptions` VARCHAR(255),
    `id_membres` INT(11) NOT NULL,
    CONSTRAINT `fk_membres_id_formations` FOREIGN KEY(`id_membres`) REFERENCES `membres`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `branches`(
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `branches`(`id`,`nom`) VALUES
(1, 'pédagogique'),
(2, 'sociale'),
(3, 'sensibilisation');

CREATE TABLE IF NOT EXISTS `fonctions`(
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255),
    `id_branches` INT(11) NOT NULL,
    `id_membres` INT(11) NOT NULL, 
    CONSTRAINT `fk_membres_id_fonctions` FOREIGN KEY(`id_membres`) REFERENCES `membres`(`id`),
    CONSTRAINT `fk_branches_id_fonctions` FOREIGN KEY(`id_branches`) REFERENCES `branches`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
