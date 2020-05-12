CREATE TABLE IF NOT EXISTS passwords(
    ID int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Password varchar(255),
    MD5Hash varchar(255)
);