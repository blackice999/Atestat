CREATE DATABASE IF NOT EXISTS `loan-crm`;
USE `loan-crm`;


CREATE TABLE IF NOT EXISTS `user_address`
(
    `ID` INT(4) NOT NULL AUTO_INCREMENT,
    `userID` INT(4) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `street` VARCHAR(50) NOT NULL,
    `zip` INT(9) NOT NULL,
    `country` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`ID`),

    FOREIGN KEY (`userID`)
        REFERENCES `user`(`ID`)
) ENGINE=INNODB;

INSERT INTO `user_address` (`userID`, `city`, `street`, `zip`, `country`)
    VALUES(1, 'Baia Mare', 'Nothing', 123456789, 'Romania')
