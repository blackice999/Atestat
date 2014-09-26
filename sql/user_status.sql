CREATE TABLE IF NOT EXISTS `user_status`
(
    `ID` INT(4) NOT NULL AUTO_INCREMENT,
    `user_ID` INT(4) NOT NULL,
    `status` VARCHAR(20) NOT NULL,
    PRIMARY KEY (`ID`)
    FOREIGN KEY (`status`, `user_ID`)
        REFERENCES `user`(`status`, `ID`)
        ON DELETE CASCADE		
)