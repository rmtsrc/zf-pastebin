-- scripts/schema.mysql.sql
--
-- You will need load your database schema with this SQL.

CREATE TABLE IF NOT EXISTS `pastebin` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `short_id` VARCHAR( 6 ) NOT NULL ,
    `name` VARCHAR( 32 ) NOT NULL DEFAULT 'Anonymous',
    `code` TEXT NULL ,
    `language` VARCHAR( 32 ) NOT NULL DEFAULT 'php',
    `expires` DATETIME NULL ,
    `ip_address` VARCHAR( 32 ) NOT NULL ,
    `created` DATETIME NOT NULL
) ENGINE = MYISAM ;