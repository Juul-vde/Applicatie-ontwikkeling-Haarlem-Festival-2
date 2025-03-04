USE webshop;

-- Drop tables in correct order
DROP TABLE IF EXISTS `Orderline`;
DROP TABLE IF EXISTS `Tour`;
DROP TABLE IF EXISTS `Music_session`;
DROP TABLE IF EXISTS `Ticket`;
DROP TABLE IF EXISTS `Order`;
DROP TABLE IF EXISTS `Session`;
DROP TABLE IF EXISTS `User`;
DROP TABLE IF EXISTS `Event`;
DROP TABLE IF EXISTS `Page`;
DROP TABLE IF EXISTS `Artist`;
DROP TABLE IF EXISTS `Venue`;
DROP TABLE IF EXISTS `Image`;
DROP TABLE IF EXISTS `Restaurant_session`;

-- Table: `Image`
CREATE TABLE `Image` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `image` VARCHAR(255)
);

-- Table: `User`
CREATE TABLE `User` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `role` INT DEFAULT 1,
    `username` VARCHAR(100) DEFAULT NULL,
    `password` VARCHAR(100) DEFAULT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `fullname` VARCHAR(100) DEFAULT NULL,
    `registration_date` DATE DEFAULT CURRENT_TIMESTAMP
);

-- Table: `Event`
CREATE TABLE `Event` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(50)
);

-- Table: `Artist`
CREATE TABLE `Artist` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100),
    `hits` VARCHAR(255)
);

-- Table: `Venue`
CREATE TABLE `Venue` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100),
    `about` TEXT,
    `type` VARCHAR(50),
    `headerimg` VARCHAR(255),
    `thumbnaimg` VARCHAR(255)
);

-- Table: `Session`
CREATE TABLE `Session` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `venue` INT,
    `capacity` INT,
    `starttime` TIME,
    `endtime` TIME,
    FOREIGN KEY (`venue`) REFERENCES `Venue`(`id`) ON DELETE CASCADE
);

-- Table: `Ticket`
CREATE TABLE `Ticket` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `session` INT,
    `user` INT,
    `qr_code` VARCHAR(255),
    `isScanned` BOOLEAN,
    FOREIGN KEY (`session`) REFERENCES `Session`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user`) REFERENCES `User`(`id`) ON DELETE CASCADE
);

-- Table: `Tour`
CREATE TABLE `Tour` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ticket` INT,
    `about` TEXT,
    `language` VARCHAR(50),
    `type` VARCHAR(50),
    FOREIGN KEY (`ticket`) REFERENCES `Ticket`(`id`) ON DELETE CASCADE
);

-- Table: `Music_session`
CREATE TABLE `Music_session` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ticket` INT,
    `type` VARCHAR(50),
    `artist` INT,
    FOREIGN KEY (`ticket`) REFERENCES `Ticket`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`artist`) REFERENCES `Artist`(`id`) ON DELETE CASCADE
);

-- Table: `Restaurant_session`
CREATE TABLE `Restaurant_session` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100),
    `email` VARCHAR(100),
    `phone` VARCHAR(20),
    `reservation_date` DATE,
    `adultamount` INT,
    `childrenamount` INT,
    `comment` TEXT
);

-- Table: `Order`
CREATE TABLE `Order` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `orderline` INT,
    `paymentstatus` VARCHAR(50),
    `user` INT,
    `date` DATE,
    `totalamount` DECIMAL(10, 2),
    FOREIGN KEY (`user`) REFERENCES `User`(`id`) ON DELETE CASCADE
);

-- Table: `Orderline`
CREATE TABLE `Orderline` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `music_session` INT,
    `restaurant_session` INT,
    `tour` INT,
    FOREIGN KEY (`music_session`) REFERENCES `Music_session`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`restaurant_session`) REFERENCES `Restaurant_session`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tour`) REFERENCES `Tour`(`id`) ON DELETE CASCADE
);

-- Table: `Page`
CREATE TABLE `Page` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `header` VARCHAR(255),
    `footer` VARCHAR(255),
    `image` INT,
    `content` TEXT,
    `title` VARCHAR(255),
    FOREIGN KEY (`image`) REFERENCES `Image`(`id`) ON DELETE CASCADE
);

-- Insert Data into the tables (Users, Artists, Venues, Sessions, etc.)

-- Insert Users
INSERT INTO User (id, role, username, password, email, image, phone, fullname, registration_date) VALUES
(1, 0, 'admin', '$2y$10$e6M10y/PcucGVjlHW1ZnGeyX1.PW2AcDtyXTg5I5W2ePRYwW1J9Pa', 'admin@admin.com', NULL, NULL, 'Admin User', NULL),
(5, 1, 'testuser', '$2y$12$xd5MRhwnkmv556RLNZt3MOdHGL9HsZX7kgXtsJDi0IsYX5anmqBXm', 'user@user.com', NULL, '0611111112', 'User Account', '2025-03-04');

-- Insert Artists
INSERT INTO `Artist` (`id`, `name`, `hits`) VALUES
(1, 'Nicky Romero', 'Toulouse'),
(2, 'Afrojack', 'Take Over Control'),
(3, 'Tiësto', 'Adagio for Strings'),
(4, 'Hardwell', 'Spaceman'),
(5, 'Armin van Buuren', 'Blah Blah Blah'),
(6, 'Martin Garrix', 'Animals');

-- Insert Venues
INSERT INTO `Venue` (`id`, `name`, `about`, `type`, `headerimg`, `thumbnaimg`) VALUES
(1, 'Lichtfabriek', 'Industrial music venue', 'Concert Hall', NULL, NULL),
(2, 'Slachthuis', 'Underground club', 'Club', NULL, NULL),
(3, 'Jopenkerk', 'Historic church turned club', 'Club', NULL, NULL),
(4, 'XO the Club', 'High-energy nightclub', 'Club', NULL, NULL),
(5, 'Puncher comedy club', 'Small intimate venue', 'Club', NULL, NULL),
(6, 'Caprera Openluchttheater', 'Outdoor music venue', 'Concert Hall', NULL, NULL);

-- Insert Sessions
INSERT INTO `Session` (`id`, `venue`, `capacity`, `starttime`, `endtime`) VALUES
(1, 1, 1500, '20:00:00', '23:59:00'),
(2, 2, 200, '22:00:00', '23:30:00'),
(3, 3, 300, '23:00:00', '00:30:00'),
(4, 4, 200, '22:00:00', '23:30:00'),
(5, 5, 200, '22:00:00', '23:30:00'),
(6, 6, 2000, '14:00:00', '18:00:00'),
(7, 3, 300, '22:00:00', '23:30:00'),
(8, 1, 1500, '21:00:00', '23:30:00'),
(9, 2, 200, '23:00:00', '00:30:00'),
(10, 6, 2000, '14:00:00', '18:00:00'),
(11, 3, 300, '19:00:00', '21:00:00'),
(12, 4, 1500, '21:00:00', '23:00:00'),
(13, 2, 200, '18:00:00', '19:30:00');

-- Insert Tickets (Assuming a dummy user with ID 1 exists)
INSERT INTO `Ticket` (`id`, `session`, `user`, `qr_code`, `isScanned`) VALUES
(1, 1, 1, 'QR123', FALSE),
(2, 2, 1, 'QR124', FALSE),
(3, 3, 1, 'QR125', FALSE),
(4, 4, 1, 'QR126', FALSE),
(5, 5, 1, 'QR127', FALSE),
(6, 6, 1, 'QR128', FALSE),
(7, 7, 1, 'QR129', FALSE),
(8, 8, 1, 'QR130', FALSE),
(9, 9, 1, 'QR131', FALSE),
(10, 10, 1, 'QR132', FALSE),
(11, 11, 1, 'QR133', FALSE),
(12, 12, 1, 'QR134', FALSE),
(13, 13, 1, 'QR135', FALSE);

-- Insert Music Sessions
INSERT INTO `Music_session` (`id`, `ticket`, `type`, `artist`) VALUES
(1, 1, 'Back2Back', 1),
(2, 1, 'Back2Back', 2),
(3, 2, 'Club', 3),
(4, 3, 'Club', 4),
(5, 4, 'Club', 5),
(6, 5, 'Club', 6),
(7, 6, 'Back2Back', 4),
(8, 6, 'Back2Back', 5),
(9, 6, 'Back2Back', 6),
(10, 7, 'Club', 2),
(11, 8, 'TiëstoWorld', 3),
(12, 9, 'Club', 4),
(13, 9, 'Club', 5),
(14, 9, 'Club', 6),
(15, 10, 'Back2Back', 2),
(16, 10, 'Back2Back', 3),
(17, 10, 'Back2Back', 1),
(18, 11, 'Club', 5),
(19, 12, 'Club', 4),
(20, 13, 'Club', 6);