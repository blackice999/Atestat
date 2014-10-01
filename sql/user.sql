CREATE TABLE IF NOT EXISTS `user`
(
    `ID` INT(4) NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(100) NOT NULL,
    `statusID` INT(4) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `addressID` INT(4) NOT NULL,
    PRIMARY KEY (`ID`)

    FOREIGN KEY(`statusID`)
        REFERENCES user_status(`ID`)

    FOREIGN KEY(`addressID`)
        REFERENCES user_address(`ID`)
) ENGINE=INNODB;
