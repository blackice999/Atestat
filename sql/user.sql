CREATE DATABASE IF NOT EXISTS `loan-crm`;
USE `loan-crm`;

CREATE TABLE IF NOT EXISTS `user`
(
    `ID` INT(4) NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(100) NOT NULL,
    `statusID` INT(4) NOT NULL DEFAULT 1,
    `password` VARCHAR(255) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `date_registered` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`ID`),

    FOREIGN KEY(`statusID`)
        REFERENCES user_status(`ID`)
) ENGINE=INNODB;


INSERT INTO `user` (`email`, `password`)
    VALUES('admin@prototype.com', '$2y$10$LhBsAfP7prznlErnqDo3EuE5eeH8qPEn6ZgaNoNQkgVzRFaxurI0e')