CREATE TABLE IF NOT EXISTS `user_address`
(
    `ID` INT(4) NOT NULL AUTO_INCREMENT,
    `city` VARCHAR(50) NOT NULL,
    `street` VARCHAR(50) NOT NULL,
    `zip` INT(9) NOT NULL,
    `country` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`ID`)
)