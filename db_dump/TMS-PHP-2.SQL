-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2025 at 04:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tms-phps`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `att_date` date NOT NULL,
  `status` enum('P','A') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `att_date`, `status`) VALUES
(1, 1, '2025-08-22', 'P'),
(2, 2, '2025-08-22', 'P'),
(3, 3, '2025-08-22', 'P'),
(4, 1, '2025-08-23', 'A'),
(5, 2, '2025-08-23', 'P'),
(6, 3, '2025-08-23', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `description`, `image`, `tag`) VALUES
(1, 'female outfit ', 'images/categories/1755868267_download__12_.jfif', 'Duppatta fabrics'),
(2, 'female traditional attire', 'images/categories/1755868316_download__7_.jfif', 'Lahenga'),
(3, 'male traditional attire', 'images/categories/1755868353_download__4_.jfif', 'Kurta fabric'),
(4, 'design dyeable fabric', 'images/categories/1755868388_download__5_.jfif', 'embroidery material');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `first_name` text DEFAULT NULL,
  `last_name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `dob` text DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `state` text DEFAULT NULL,
  `zip` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `reference_name` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `profile_image` text DEFAULT 'f',
  `total_amount` text DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `first_name`, `last_name`, `email`, `phone`, `dob`, `gender`, `address`, `city`, `state`, `zip`, `country`, `reference_name`, `notes`, `profile_image`, `total_amount`) VALUES
(1, 'Maunish', 'Prajapati', 'cdmi.m24@gmail.com', '8238129140', '2005-11-24', 'Male', 'a-9,vihalnagar', 'surat', 'gujarat', '395006', 'India', '', '', '1755869286_myphoto.jpg', '0'),
(2, 'Avin', '', 'maunish247@gmail.com', '7863039332', '2002-10-22', 'Male', '84,Prasthan bunglows', 'surat', 'gujarat', '395006', '', '', '', '1755869391_photo_2025-08-02_15-01-43.jpg', '0'),
(3, 'Raj', '', 'datascience2411@gmail.com', '7983213456', '1994-06-28', 'Male', '', '', '', '', '', '', '', '1755869471_download (15).jfif', '0'),
(4, 'Rohan', '', 'rohan23@gmail.com', '9878563214', '1997-07-09', 'Male', '', '', '', '', '', '', '', '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','in_progress','resolved','closed') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiry`
--

INSERT INTO `inquiry` (`id`, `name`, `email`, `phone`, `message`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Raju', 'rohixak620@baxima.com', '7890232456', 'inquired for dupatta', 'in_progress', '2025-08-22 15:50:46', '2025-08-22 15:50:46'),
(3, 'ssahil', 'sahill@gmail.com', '9081729041', '200 dupatta need', 'new', '2025-08-23 03:40:33', '2025-08-23 03:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_label` varchar(255) DEFAULT 'Invoice',
  `customer_id` varchar(100) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `swift_code` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `discount` decimal(12,2) DEFAULT 0.00,
  `tax` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_label`, `customer_id`, `invoice_date`, `due_date`, `account_number`, `bank_name`, `swift_code`, `notes`, `subtotal`, `discount`, `tax`, `total`, `created_at`) VALUES
(2, 'Invoice', '1', '2025-08-22', '2025-09-06', '256479612', 'kotak', 'kotak7835', 'thanks for choosing us', 37500.00, 0.00, 0.00, 37500.00, '2025-08-22 14:28:25'),
(3, 'Invoice', '2', '2025-08-22', '2025-09-06', '', '', '', 'thank you', 28000.00, 0.00, 0.00, 28000.00, '2025-08-22 14:29:37'),
(4, 'Invoice', '4', '2025-08-22', '2025-09-06', '464323566', 'HDFC BANK', 'HDFC9786', 'Thank you', 6700.00, 0.00, 0.00, 6700.00, '2025-08-22 15:48:44'),
(5, 'Invoice', '4', '2025-08-23', '2025-09-07', '', '', '', '', 50000.00, 0.00, 0.00, 50000.00, '2025-08-23 03:01:53'),
(6, 'Invoice', '1', '2025-08-23', '2025-09-07', '', '', '', '', 0.00, 0.00, 0.00, 0.00, '2025-08-23 03:11:45'),
(7, 'Invoice', '3', '2025-08-23', '2025-09-07', '45698756', 'sbi', 'sbi98987', 'thank you', 300000.00, 0.00, 0.00, 300000.00, '2025-08-23 03:12:55'),
(8, 'Invoice', '1', '2025-08-23', '2025-09-07', '', '', '', '', 6700000.00, 0.00, 0.00, 6700000.00, '2025-08-23 03:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `rate` decimal(12,2) DEFAULT 0.00,
  `quantity` int(11) DEFAULT 1,
  `amount` decimal(12,2) DEFAULT 0.00,
  `taxable` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `rate`, `quantity`, `amount`, `taxable`) VALUES
(3, 2, '12', 250.00, 150, 37500.00, 0),
(4, 3, '15', 700.00, 40, 28000.00, 0),
(5, 4, '16', 670.00, 10, 6700.00, 0),
(6, 5, '12', 250.00, 200, 50000.00, 0),
(7, 6, '0', 0.00, 0, 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_date` date DEFAULT curdate(),
  `amount_paid` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','card','bank_transfer','upi','paypal') DEFAULT 'cash',
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `payment_date`, `amount_paid`, `payment_method`, `reference_number`, `notes`, `created_at`) VALUES
(1, 2, '2025-08-22', 30000.00, 'upi', 'admin', 'payment done ', '2025-08-22 14:30:37'),
(2, 3, '2025-08-22', 10000.00, 'cash', 'admin', '', '2025-08-22 14:31:30'),
(3, 4, '2025-08-23', 500.00, 'cash', '', 'test', '2025-08-23 03:09:50');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

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
  `disabled` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `images` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `product_code`, `category`, `tags`, `regular_price`, `sale_price`, `includes_tax`, `in_stock`, `show_publicly`, `disabled`, `created_at`, `updated_at`, `images`) VALUES
(12, 'Butterfly Net Dupatta', 'lightweight design dupatta', '1', 'Duppatta fabrics', '', 250.00, 200.00, 0, 0, 1, 0, '2025-08-22 13:15:05', '2025-08-22 13:15:05', '[\"1755868505_0_download__13_.jfif\",\"1755868505_1_download__12_.jfif\",\"1755868505_2_download__11_.jfif\",\"1755868505_3_download__10_.jfif\"]'),
(13, 'Chikankari Embroidery Work Dupatta', 'Pure Chikankari Fabrics, Width:54′, You Can Use In , Kurta And You Can Use Also Ladies Garment\r\n', '2', 'Duppatta fabrics', '', 600.00, 0.00, 0, 0, 1, 0, '2025-08-22 13:17:26', '2025-08-22 13:17:26', '[\"1755868646_0_images__8_.jfif\",\"1755868646_1_images__7_.jfif\",\"1755868646_2_images__6_.jfif\",\"1755868646_3_download__16_.jfif\"]'),
(14, 'Embroidered Sequence Dupatta', 'Lehenga & Dupatta  Fabrics  Dyable Fabrics ', '3', 'Duppatta fabrics', '', 550.00, 0.00, 0, 0, 1, 0, '2025-08-22 13:19:09', '2025-08-22 13:21:08', '[\"1755868749_0_download__6_.jfif\"]'),
(15, 'Fancy Lahanga Fabrics', 'Multy Colour Thread ', '4', 'Lahenga', '', 700.00, 0.00, 0, 0, 1, 0, '2025-08-22 13:20:56', '2025-08-22 13:20:56', '[\"1755868856_0_images__4_.jfif\"]'),
(16, 'Embroidery Fancy Lehenga Fabrics', 'Nylone Orgenza Fabrics Dyable Fabrics ', '5', 'embroidery material', '', 670.00, 0.00, 0, 0, 1, 0, '2025-08-22 13:22:56', '2025-08-22 13:22:56', '[\"1755868976_0_images__9_.jfif\",\"1755868976_1_download__17_.jfif\"]'),
(17, 'Kurta material', 'traditional men attire', '6', 'Kurta fabric', '', 300.00, 0.00, 0, 0, 1, 1, '2025-08-22 13:24:05', '2025-08-22 13:37:52', '[\"1755869045_0_download__9_.jfif\"]');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `otp_expires` datetime NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `sold_stock` int(11) DEFAULT NULL,
  `dead_stock` int(11) DEFAULT NULL,
  `pending_stock` int(11) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `product_id`, `product_name`, `current_stock`, `sold_stock`, `dead_stock`, `pending_stock`, `last_updated`) VALUES
(1, 12, 'Butterfly Net Dupatta', 400, 350, NULL, NULL, '2025-08-23 03:01:54'),
(2, 13, 'Chikankari Embroidery Work Dupatta', 300, 0, 20, NULL, '2025-08-22 14:17:02'),
(3, 14, 'Embroidered Sequence Dupatta', 250, NULL, NULL, NULL, '2025-08-22 13:25:00'),
(4, 16, 'Embroidery Fancy Lehenga Fabrics', 100, 10, NULL, NULL, '2025-08-22 15:48:45'),
(5, 15, 'Fancy Lahanga Fabrics', 150, 40, 0, NULL, '2025-08-22 14:29:37'),
(6, 17, 'Kurta material', 500, NULL, NULL, NULL, '2025-08-22 13:25:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `mobile_no` bigint(20) DEFAULT NULL,
  `role` text NOT NULL DEFAULT 'staff',
  `profile_picture` text DEFAULT 'avatar.png',
  `auth_provider` text NOT NULL DEFAULT 'local',
  `two_step_auth` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `is_verified`, `name`, `email`, `password`, `mobile_no`, `role`, `profile_picture`, `auth_provider`, `two_step_auth`, `created_by`, `created_at`) VALUES
(1, 1, 'admin', 'admin@gmail.com', 'admin@123', 7894568756, 'admin', 'avatar.png', 'local', 0, 0, '2025-08-22 13:09:02'),
(2, 1, 'staff', 'staff@gmail.com', 'staff@123', 7894568756, 'staff', 'avatar.png', 'local', 0, 0, '2025-08-22 13:09:02'),
(3, 1, 'rahul', 'demo1@gmail.com', '123', 9727496767, 'staff', '', 'local', 0, 1, '2025-08-22 14:34:08'),
(4, 0, 'cdmi', 'cdmi.m24@gmail.com', '', NULL, 'staff', 'https://lh3.googleusercontent.com/a/ACg8ocKQUrjlRb2lFzJrajwWk1I3s24YY3IEjQN_mcMnLVJO9Pbuhg=s96-c', 'google', 0, 0, '2025-08-23 03:24:20');

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `is_success` tinyint(1) NOT NULL DEFAULT 0,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`id`, `user_id`, `email`, `is_success`, `login_time`) VALUES
(1, 1, 'admin@gmail.com', 1, '2025-08-22 14:16:06'),
(2, 1, 'admin@gmail.com', 1, '2025-08-22 14:26:49'),
(3, 3, 'demo1@gmail.com', 1, '2025-08-22 14:37:39'),
(4, 1, 'admin@gmail.com', 1, '2025-08-22 15:33:11'),
(5, 3, 'demo1@gmail.com', 1, '2025-08-22 15:38:35'),
(6, 1, 'admin@gmail.com', 1, '2025-08-22 15:42:25'),
(7, 3, 'demo1@gmail.com', 1, '2025-08-22 15:43:11'),
(8, 1, 'admin@gmail.com', 1, '2025-08-22 15:44:39'),
(9, 3, 'demo1@gmail.com', 1, '2025-08-22 15:45:46'),
(10, 1, 'admin@gmail.com', 1, '2025-08-22 15:53:15'),
(11, 1, 'admin@gmail.com', 1, '2025-08-23 02:59:11'),
(12, 3, 'demo1@gmail.com', 1, '2025-08-23 03:03:57'),
(13, 1, 'admin@gmail.com', 1, '2025-08-23 03:05:45'),
(14, 3, 'demo1@gmail.com', 1, '2025-08-23 03:21:39'),
(15, 3, 'demo1@gmail.com', 1, '2025-08-23 03:26:49'),
(16, 3, 'demo1@gmail.com', 1, '2025-08-23 03:35:01'),
(17, 1, 'admin@gmail.com', 1, '2025-08-23 03:42:03'),
(18, 3, 'demo1@gmail.com', 1, '2025-08-23 05:48:22'),
(19, 1, 'admin@gmail.com', 1, '2025-08-23 05:55:53'),
(20, 1, 'admin@gmail.com', 1, '2025-08-28 01:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `weblink`
--

CREATE TABLE `weblink` (
  `id` int(11) NOT NULL,
  `createby` int(11) NOT NULL,
  `createat` timestamp NOT NULL DEFAULT current_timestamp(),
  `productIds` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `weblink`
--

INSERT INTO `weblink` (`id`, `createby`, `createat`, `productIds`) VALUES
(1, 1, '2025-08-22 13:32:12', '17,16'),
(2, 3, '2025-08-22 14:38:01', '17,16'),
(3, 3, '2025-08-23 03:04:14', '17,16'),
(4, 1, '2025-08-23 03:13:56', '17,16'),
(5, 3, '2025-08-23 03:36:25', '17,16,15,14'),
(6, 3, '2025-08-23 03:41:19', '17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_date` (`user_id`,`att_date`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_items_invoice` (`invoice_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stock_product` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `weblink`
--
ALTER TABLE `weblink`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_weblink_createby` (`createby`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `weblink`
--
ALTER TABLE `weblink`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `fk_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD CONSTRAINT `reset_password_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_stock_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `user_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `weblink`
--
ALTER TABLE `weblink`
  ADD CONSTRAINT `fk_weblink_createby` FOREIGN KEY (`createby`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;





-- run

ALTER TABLE `payments` ADD `created_by` INT NOT NULL AFTER `created_at`, ADD `updated_by` INT NULL DEFAULT NULL AFTER `created_by`;
ALTER TABLE `inquiry` ADD `created_by` INT NOT NULL AFTER `created_at`, ADD `updated_by` INT NULL DEFAULT NULL AFTER `created_by`;
ALTER TABLE `invoices` ADD `created_by` INT NOT NULL AFTER `created_at`, ADD `updated_by` INT NULL DEFAULT NULL AFTER `created_by`;

