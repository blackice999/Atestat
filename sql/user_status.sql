CREATE DATABASE IF NOT EXISTS `loan-crm`;
USE `loan-crm`;


CREATE TABLE IF NOT EXISTS `user_status`
(
    `ID` INT(4) NOT NULL AUTO_INCREMENT,
    `status` VARCHAR(20) NOT NULL,
    PRIMARY KEY (`ID`)
) ENGINE=INNODB;


INSERT INTO `user_status` (`status`) VALUES('active'),('updated')