-- MySQL Script generated by MySQL Workbench
-- 10/19/16 02:20:07
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema testdatabase
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema testdatabase
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `testdatabase` DEFAULT CHARACTER SET utf8 ;
USE `testdatabase` ;

-- -----------------------------------------------------
-- Table `testdatabase`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testdatabase`.`user` (
  `username` VARCHAR(20) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `firstname` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `email` VARCHAR(45) NOT NULL,
  `phone` VARCHAR(45) NULL,
  `address` VARCHAR(45) NULL,
  PRIMARY KEY (`username`),
  UNIQUE INDEX `email` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testdatabase`.`event`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testdatabase`.`event` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `header` VARCHAR(45) NOT NULL,
  `description` VARCHAR(45) NULL,
  `startDateTime` DATETIME NOT NULL,
  `endDateTime` DATETIME NOT NULL,
  `location` VARCHAR(60) NULL,
  `owner` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `Owner_idx` (`owner` ASC),
  CONSTRAINT `Owner`
    FOREIGN KEY (`owner`)
    REFERENCES `testdatabase`.`user` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testdatabase`.`sharedevent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testdatabase`.`sharedevent` (
  `ShareID` INT NOT NULL AUTO_INCREMENT,
  `eventID` INT NOT NULL,
  `username` VARCHAR(20) NOT NULL,
  UNIQUE INDEX `personUnique` (`eventID` ASC, `username` ASC),
  INDEX `shared_with_fk_idx` (`username` ASC),
  PRIMARY KEY (`ShareID`),
  CONSTRAINT `eventid`
    FOREIGN KEY (`eventID`)
    REFERENCES `testdatabase`.`event` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `shared_with_fk`
    FOREIGN KEY (`username`)
    REFERENCES `testdatabase`.`user` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testdatabase`.`group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testdatabase`.`group` (
  `groupName` VARCHAR(45) NOT NULL,
  `creationDate` DATETIME NULL,
  `owner` VARCHAR(20) NULL,
  PRIMARY KEY (`groupName`),
  INDEX `owner_fk_idx` (`owner` ASC),
  CONSTRAINT `owner_fk`
    FOREIGN KEY (`owner`)
    REFERENCES `testdatabase`.`user` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testdatabase`.`useringroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testdatabase`.`useringroup` (
  `inputID` INT NOT NULL AUTO_INCREMENT,
  `groupName` VARCHAR(45) NOT NULL,
  `user` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`inputID`),
  UNIQUE INDEX `OnceInGroup` (`groupName` ASC, `user` ASC),
  INDEX `GroupUser_fk_idx` (`user` ASC),
  CONSTRAINT `GroupName_fk`
    FOREIGN KEY (`groupName`)
    REFERENCES `testdatabase`.`group` (`groupName`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `GroupUser_fk`
    FOREIGN KEY (`user`)
    REFERENCES `testdatabase`.`user` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
