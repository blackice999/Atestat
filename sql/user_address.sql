CREATE TABLE IF NOT EXISTS `user_address`
(
    `ID` INT(4) NOT NULL AUTO_INCREMENT,
    `user_ID` INT(4) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `street` VARCHAR(50) NOT NULL,
    `zip` INT(9) NOT NULL,
    `country` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`ID`) 
    FOREIGN KEY (`user_ID`)
        REFERENCES `user`(`ID`)
        ON DELETE CASCADE

)