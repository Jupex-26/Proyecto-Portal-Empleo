-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mydb` ;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema empleo
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `empleo` ;

-- -----------------------------------------------------
-- Schema empleo
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `empleo` ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`table1`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`table1` ;

USE `empleo` ;

-- -----------------------------------------------------
-- Table `empleo`.`rol`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`rol` ;

CREATE TABLE IF NOT EXISTS `empleo`.`rol` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`token` ;

CREATE TABLE IF NOT EXISTS `empleo`.`token` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(45) NOT NULL,
  `fecha_caducidad` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`user` ;

CREATE TABLE IF NOT EXISTS `empleo`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `correo` VARCHAR(100) NOT NULL UNIQUE,
  `passwd` VARCHAR(45) NOT NULL,
  `rol_id` INT NOT NULL,
  `direccion` VARCHAR(45) NULL,
  `foto` VARCHAR(45) NULL,
  `token_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_rol_idx` (`rol_id` ASC) VISIBLE,
  INDEX `fk_user_token1_idx` (`token_id` ASC) VISIBLE,
  CONSTRAINT `fk_user_rol`
    FOREIGN KEY (`rol_id`)
    REFERENCES `empleo`.`rol` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_token1`
    FOREIGN KEY (`token_id`)
    REFERENCES `empleo`.`token` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`alumno`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`alumno` ;

CREATE TABLE IF NOT EXISTS `empleo`.`alumno` (
  `id` INT NOT NULL,
  `ap1` VARCHAR(45) NOT NULL,
  `ap2` VARCHAR(45) NOT NULL,
  `cv` VARCHAR(45) NULL,
  `fecha_nacimiento` DATETIME NOT NULL,
  `descripcion` VARCHAR(500) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_copy1_user1_idx` (`id` ASC) VISIBLE,
  CONSTRAINT `fk_user_copy1_user1`
    FOREIGN KEY (`id`)
    REFERENCES `empleo`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`empresa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`empresa` ;

CREATE TABLE IF NOT EXISTS `empleo`.`empresa` (
  `id` INT NOT NULL,
  `correoContacto` VARCHAR(45) NOT NULL,
  `telefonoContacto` VARCHAR(45) NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 0,
  `descripcion` VARCHAR(500) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_empresa_user1_idx` (`id` ASC) VISIBLE,
  CONSTRAINT `fk_empresa_user1`
    FOREIGN KEY (`id`)
    REFERENCES `empleo`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`oferta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`oferta` ;

CREATE TABLE IF NOT EXISTS `empleo`.`oferta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `empresa_id` INT NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `descripcion` VARCHAR(100) NOT NULL,
  `fecha_inicio` DATETIME NOT NULL,
  `fecha_fin` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_oferta_empresa1_idx` (`empresa_id` ASC) VISIBLE,
  CONSTRAINT `fk_oferta_empresa1`
    FOREIGN KEY (`empresa_id`)
    REFERENCES `empleo`.`empresa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`solicitud`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`solicitud` ;

CREATE TABLE IF NOT EXISTS `empleo`.`solicitud` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `alumno_id` INT NOT NULL,
  `oferta_id` INT NOT NULL,
  `estado` ENUM('PROCESO', 'ACEPTADO', 'DENEGADO') NULL DEFAULT 'PROCESO',
  PRIMARY KEY (`id`),
  INDEX `fk_solicitud_alumno1_idx` (`alumno_id` ASC) VISIBLE,
  INDEX `fk_solicitud_oferta1_idx` (`oferta_id` ASC) VISIBLE,
  CONSTRAINT `fk_solicitud_alumno1`
    FOREIGN KEY (`alumno_id`)
    REFERENCES `empleo`.`alumno` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_solicitud_oferta1`
    FOREIGN KEY (`oferta_id`)
    REFERENCES `empleo`.`oferta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`familia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`familia` ;

CREATE TABLE IF NOT EXISTS `empleo`.`familia` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`ciclo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`ciclo` ;

CREATE TABLE IF NOT EXISTS `empleo`.`ciclo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `familia_id` INT NOT NULL,
  `nivel` ENUM('BASICO', 'MEDIO', 'SUPERIOR', 'ESPECIALIZACION') NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ciclo_familia1_idx` (`familia_id` ASC) VISIBLE,
  CONSTRAINT `fk_ciclo_familia1`
    FOREIGN KEY (`familia_id`)
    REFERENCES `empleo`.`familia` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`alum_cursado_ciclo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`alum_cursado_ciclo` ;

CREATE TABLE IF NOT EXISTS `empleo`.`alum_cursado_ciclo` (
  `alumno_id` INT NOT NULL,
  `ciclo_id` INT NOT NULL,
  `fecha_inicio` DATETIME NOT NULL,
  `fecha_fin` DATETIME NULL,
  `id` INT NOT NULL AUTO_INCREMENT,
  INDEX `fk_alum-cursado-ciclo_alumno1_idx` (`alumno_id` ASC) VISIBLE,
  INDEX `fk_alum-cursado-ciclo_ciclo1_idx` (`ciclo_id` ASC) VISIBLE,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_alum-cursado-ciclo_alumno1`
    FOREIGN KEY (`alumno_id`)
    REFERENCES `empleo`.`alumno` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alum-cursado-ciclo_ciclo1`
    FOREIGN KEY (`ciclo_id`)
    REFERENCES `empleo`.`ciclo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empleo`.`ciclo-tiene-oferta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empleo`.`ciclo-tiene-oferta` ;

CREATE TABLE IF NOT EXISTS `empleo`.`ciclo-tiene-oferta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ciclo_id` INT NOT NULL,
  `oferta_id` INT NOT NULL,
  `requerido` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_ciclo-tiene-oferta_ciclo1_idx` (`ciclo_id` ASC) VISIBLE,
  INDEX `fk_ciclo-tiene-oferta_oferta1_idx` (`oferta_id` ASC) VISIBLE,
  CONSTRAINT `fk_ciclo-tiene-oferta_ciclo1`
    FOREIGN KEY (`ciclo_id`)
    REFERENCES `empleo`.`ciclo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ciclo-tiene-oferta_oferta1`
    FOREIGN KEY (`oferta_id`)
    REFERENCES `empleo`.`oferta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
