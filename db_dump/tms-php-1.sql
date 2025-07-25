-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 07:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tms-php`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
  `name` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `mobile_no` BIGINT DEFAULT NULL,
  `role` TEXT NOT NULL DEFAULT 'staff',
  `profile_picture` TEXT DEFAULT NULL,
  `auth_provider` TEXT NOT NULL DEFAULT 'local',
  `two_step_auth` TINYINT(1) NOT NULL DEFAULT 0,
  `created_by` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



--
-- AUTO_INCREMENT for dumped tables
--



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE reset_password (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    otp VARCHAR(10) NOT NULL,
    otp_expires DATETIME NOT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users(name,email,password,mobile_no,role,is_verified)values('admin','admin@gmail.com','admin@123',7894568756,'admin',1);
INSERT INTO users(name,email,password,mobile_no,role,is_verified)values('staff','staff@gmail.com','staff@123',7894568756,'staff',1);

-- user login log
CREATE TABLE IF NOT EXISTS `user_log` (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  email TEXT NOT NULL,
  is_success TINYINT(1) NOT NULL DEFAULT 0, 
  login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- attendance table


CREATE TABLE attendance (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT          NOT NULL,
  att_date    DATE         NOT NULL,
  status      ENUM('P','A') NOT NULL,
  UNIQUE KEY  uniq_user_date (user_id, att_date)
);

-- product table

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `product_code` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `regular_price` decimal(10,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `includes_tax` tinyint(1) DEFAULT 0,
  `in_stock` tinyint(1) DEFAULT 0,
  `show_publicly` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;


-- Invoices (main header)
CREATE TABLE  `invoices` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_label`   VARCHAR(255) DEFAULT 'Invoice',
  `client_name`     VARCHAR(255) NOT NULL,
  `client_email`    VARCHAR(255) DEFAULT NULL,
  `client_address`  TEXT         DEFAULT NULL,
  `client_phone`    VARCHAR(50)  DEFAULT NULL,
  `invoice_number`  VARCHAR(50)  NOT NULL,
  `invoice_date`    DATE         DEFAULT NULL,
  `due_date`        DATE         DEFAULT NULL,
  `account_number`  VARCHAR(50)  DEFAULT NULL,
  `bank_name`       VARCHAR(100) DEFAULT NULL,
  `swift_code`      VARCHAR(50)  DEFAULT NULL,
  `notes`           TEXT         DEFAULT NULL,
  `subtotal`        DECIMAL(12,2) DEFAULT 0.00,
  `discount`        DECIMAL(12,2) DEFAULT 0.00,
  `tax`             DECIMAL(12,2) DEFAULT 0.00,
  `total`           DECIMAL(12,2) DEFAULT 0.00,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Invoice line items
CREATE TABLE `invoice_items` (
  `id`                 INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_id`         INT NOT NULL,
  `description`        VARCHAR(255) NOT NULL,
  `additional_details` TEXT DEFAULT NULL,
  `rate`               DECIMAL(12,2) DEFAULT 0.00,
  `quantity`           INT           DEFAULT 1,
  `amount`             DECIMAL(12,2) DEFAULT 0.00,
  `taxable`            TINYINT(1)    DEFAULT 0,
  CONSTRAINT `fk_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- weblink

CREATE TABLE `weblink` (
    `id`         INT AUTO_INCREMENT PRIMARY KEY,
    `createby`   INT NOT NULL,
    `createat`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `productIds` TEXT,

    CONSTRAINT `fk_weblink_createby`
        FOREIGN KEY (`createby`) REFERENCES `users`(`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
--  category

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  description TEXT,
  image VARCHAR(255),
  tag VARCHAR(100)
);
