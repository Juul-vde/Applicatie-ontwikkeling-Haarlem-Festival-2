-- Table: `User`
CREATE TABLE `User` (
    `id` INT PRIMARY KEY,
    `role` VARCHAR(50),
    `username` VARCHAR(100),
    `password` VARCHAR(100),
    `email` VARCHAR(100),
    `image` VARCHAR(255),
    `phone` VARCHAR(20),
    `fullname` VARCHAR(100),
    `registration_date` DATE
);

-- Table: `Event`
CREATE TABLE `Event` (
    `id` INT PRIMARY KEY,
    `type` VARCHAR(50)
);

-- Table: `Page`
CREATE TABLE `Page` (
    `id` INT PRIMARY KEY,
    `header` VARCHAR(255),
    `footer` VARCHAR(255),
    `image` INT,
    `content` TEXT,
    `title` VARCHAR(255),
    FOREIGN KEY (`image`) REFERENCES `Image`(`id`)
);

-- Table: `Artist`
CREATE TABLE `Artist` (
    `id` INT PRIMARY KEY,
    `name` VARCHAR(100),
    `hits` VARCHAR(255)
);

-- Table: `Venue`
CREATE TABLE `Venue` (
    `id` INT PRIMARY KEY,
    `name` VARCHAR(100),
    `about` TEXT,
    `type` VARCHAR(50),
    `headerimg` VARCHAR(255),
    `thumbnaimg` VARCHAR(255)
);

-- Table: `Ticket`
CREATE TABLE `Ticket` (
    `id` INT PRIMARY KEY,
    `session` INT,
    `user` INT,
    `qr_code` VARCHAR(255),
    `isScanned` BOOLEAN,
    FOREIGN KEY (`session`) REFERENCES `Session`(`id`),
    FOREIGN KEY (`user`) REFERENCES `User`(`id`)
);

-- Table: `Tour`
CREATE TABLE `Tour` (
    `id` INT PRIMARY KEY,
    `ticket` INT,
    `about` TEXT,
    `language` VARCHAR(50),
    `type` VARCHAR(50),
    FOREIGN KEY (`ticket`) REFERENCES `Ticket`(`id`)
);

-- Table: `Session`
CREATE TABLE `Session` (
    `id` INT PRIMARY KEY,
    `venue` INT,
    `capacity` INT,
    `starttime` TIME,
    `endtime` TIME,
    FOREIGN KEY (`venue`) REFERENCES `Venue`(`id`)
);

-- Table: `Music_session`
CREATE TABLE `Music_session` (
    `id` INT PRIMARY KEY,
    `ticket` INT,
    `type` VARCHAR(50),
    `artist` INT,
    FOREIGN KEY (`ticket`) REFERENCES `Ticket`(`id`),
    FOREIGN KEY (`artist`) REFERENCES `Artist`(`id`)
);

-- Table: `Restaurant_session`
CREATE TABLE `Restaurant_session` (
    `id` INT PRIMARY KEY,
    `name` VARCHAR(100),
    `email` VARCHAR(100),
    `phone` VARCHAR(20),
    `reservation_date` DATE,
    `adultamount` INT,
    `childrenamount` INT,
    `comment` TEXT
);

-- Table: `Image`
CREATE TABLE `Image` (
    `id` INT PRIMARY KEY,
    `image` VARCHAR(255)
);

-- Table: `Order`
CREATE TABLE `Order` (
    `id` INT PRIMARY KEY,
    `orderline` INT,
    `paymentstatus` VARCHAR(50),
    `user` INT,
    `date` DATE,
    `totalamount` DECIMAL(10, 2),
    FOREIGN KEY (`user`) REFERENCES `User`(`id`)
);

-- Table: `Orderline`
CREATE TABLE `Orderline` (
    `id` INT PRIMARY KEY,
    `music_session` INT,
    `restaurant_session` INT,
    `tour` INT,
    FOREIGN KEY (`music_session`) REFERENCES `Music_session`(`id`),
    FOREIGN KEY (`restaurant_session`) REFERENCES `Restaurant_session`(`id`),
    FOREIGN KEY (`tour`) REFERENCES `Tour`(`id`)
);
