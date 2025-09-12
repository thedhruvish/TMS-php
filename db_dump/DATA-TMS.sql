-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 05:31 AM
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
-- Database: `tms-php`
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
(5, 3, '2025-09-07', 'P'),
(6, 4, '2025-09-07', 'P'),
(7, 3, '2025-09-08', 'P'),
(8, 4, '2025-09-08', 'P');

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
(3, 'men releated attires', 'images/categories/1757241165_images__10_.jfif', 'men clothing'),
(4, 'semi prepared materials', 'images/categories/1757241262_download__19_.jfif', 'Yarn & Fabrics'),
(6, 'woman attires', 'images/categories/1757241435_download__20_.jfif', 'women clothing'),
(7, 'indian treditional attire', 'images/categories/1757242032_download__2_.jfif', 'Saree'),
(8, 'Woman marriage wear', 'images/categories/1757242139_images__9_.jfif', 'Lehenga');

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
(5, 'Avin', 'Prajapati', 'avin22@gmail.com', '7863039332', '2002-10-22', 'Male', '79,Prasthan bunglows,Kamrej', 'surat', 'gujarat', '395009', 'India', '', '', '', '0'),
(6, 'Pal', 'Balar', 'pal30@gmail.com', '9879882858', '2005-10-30', 'Male', 'A-74,River view,Mota varachha', 'surat', 'gujarat', '395007', 'India', '', '', '', '0'),
(8, 'Rakesh', 'Solanki', 'rakesh3@gmail.com', '7863212546', '1990-08-07', 'Male', 'a-9,Guruvilla', 'Rajkot', 'gujarat', '360005', 'India', '', '', '', '0'),
(9, 'Deep', 'Turakhiya', 'deep1608@gmail.com', '9081728923', '1982-05-08', 'Male', 'b-410,apple avenue', 'Vaodara', 'gujarat', '390006', 'India', '', '', '', '0'),
(10, 'Rahul ', 'Jain', 'rahul16@gmail.com', '9909140057', '1995-11-22', 'Male', 'C-9,adani tower', 'ahemdabad', 'gujarat', '380001', 'India', '', '', '', '0');

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
  `status` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiry`
--

INSERT INTO `inquiry` (`id`, `name`, `email`, `phone`, `message`, `status`, `created_at`, `created_by`, `updated_by`, `updated_at`) VALUES
(7, 'Rajesh Shah', 'rajesh90@gmail.com', '8980127413', 'custom\r\n 2000 meter  cotton fabric', 0, '2025-09-07 10:24:02', 1, NULL, '2025-09-07 10:31:46'),
(9, 'Chandresh', 'chandresh12@gmail.com', '927496767', '500 banarasi saree', 0, '2025-09-08 05:15:44', 1, NULL, '2025-09-08 05:15:44');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `customer_id`, `invoice_date`, `due_date`, `account_number`, `bank_name`, `swift_code`, `notes`, `subtotal`, `discount`, `tax`, `total`, `created_at`, `created_by`, `updated_by`) VALUES
(37, '5', '2025-09-07', '2025-09-22', '', '', '', '', 7000.00, 0.00, 700.00, 7700.00, '2025-09-07 12:18:08', 1, NULL),
(38, '5', '2025-09-07', '2025-09-23', '', '', '', '', 73000.00, 0.00, 0.00, 73000.00, '2025-09-07 12:19:39', 1, NULL),
(39, '6', '2025-09-01', '2025-09-22', '', '', '', '', 30000.00, 0.00, 3000.00, 33000.00, '2025-09-07 12:23:51', 3, NULL),
(41, '9', '2025-09-08', '2025-09-23', '', '', '', '', 280000.00, 0.00, 28000.00, 308000.00, '2025-09-08 05:10:37', 1, NULL),
(43, '6', '2025-09-08', '2025-09-23', '', '', '', '', 6000.00, 0.00, 600.00, 6600.00, '2025-09-08 07:58:49', 4, NULL);

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
(26, 37, '29', 70.00, 100, 7000.00, 0),
(31, 39, '30', 300.00, 100, 30000.00, 0),
(33, 38, '34', 2800.00, 20, 56000.00, 0),
(34, 38, '28', 350.00, 20, 7000.00, 0),
(35, 38, '27', 250.00, 20, 5000.00, 0),
(36, 38, '26', 250.00, 20, 5000.00, 0),
(37, 41, '33', 2800.00, 100, 280000.00, 0),
(39, 43, '31', 600.00, 10, 6000.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_date` date DEFAULT curdate(),
  `amount_paid` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','card','bank_transfer','upi','cheque') DEFAULT 'cash',
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `payment_date`, `amount_paid`, `payment_method`, `reference_number`, `notes`, `created_at`, `created_by`, `updated_by`) VALUES
(13, 37, '2025-09-07', 7700.00, 'cash', '', '', '2025-09-07 12:25:41', 1, NULL),
(14, 39, '2025-09-07', 30000.00, 'cash', '', '', '2025-09-07 12:31:15', 1, NULL),
(15, 38, '2025-09-07', 3000.00, 'cash', '', '', '2025-09-07 12:31:38', 1, NULL),
(16, 39, '2025-09-08', 3000.00, 'upi', '40071256489', '', '2025-09-08 05:12:17', 1, NULL),
(17, 41, '2025-09-08', 25000.00, 'cash', '', '', '2025-09-08 05:17:11', 1, NULL),
(19, 41, '2025-09-08', 158000.00, 'cash', '', '', '2025-09-08 05:19:04', 1, NULL),
(20, 41, '2025-09-08', 25000.00, 'cheque', '451250', '', '2025-09-08 08:01:18', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
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

INSERT INTO `products` (`id`, `name`, `description`, `category`, `regular_price`, `sale_price`, `includes_tax`, `in_stock`, `show_publicly`, `disabled`, `created_at`, `updated_at`, `images`) VALUES
(22, 'cotton', 'cotton plain material', 'Yarn & Fabrics', 100.00, 75.00, 0, 0, 1, 0, '2025-09-07 11:11:38', '2025-09-07 11:18:56', '[\"1757243498_0_images__11_.jfif\",\"1757243498_1_download__23_.jfif\",\"1757243498_2_download__22_.jfif\",\"1757243498_3_download__21_.jfif\"]'),
(24, 'Silk', 'silk fabric', 'Yarn & Fabrics', 100.00, 0.00, 0, 0, 1, 0, '2025-09-07 11:18:43', '2025-09-07 11:29:33', '[\"1757243923_0_download__27_.jfif\",\"1757243923_1_download__26_.jfif\",\"1757243923_2_download__25_.jfif\"]'),
(25, 'Linen', 'Linen Material', 'Yarn & Fabrics', 120.00, 0.00, 0, 0, 1, 0, '2025-09-07 11:31:29', '2025-09-07 11:31:29', '[\"1757244689_0_images__12_.jfif\",\"1757244689_1_download__30_.jfif\",\"1757244689_2_download__29_.jfif\",\"1757244689_3_download__28_.jfif\"]'),
(26, 'Shirt', 'plain men shirts', 'men clothing', 250.00, 0.00, 0, 0, 1, 0, '2025-09-07 11:36:05', '2025-09-07 11:36:05', '[\"1757244965_0_download__31_.jfif\"]'),
(27, 'Trousers', 'men formal wear', 'men clothing', 250.00, 0.00, 0, 0, 1, 0, '2025-09-07 11:37:35', '2025-09-07 11:37:35', '[\"1757245055_0_images__13_.jfif\"]'),
(28, 'Linen Pents', 'linen trousers', 'men clothing', 350.00, 0.00, 0, 0, 1, 0, '2025-09-07 11:45:19', '2025-09-07 12:06:00', '[\"1757245519_0_download__32_.jfif\"]'),
(29, 'Net duppatta', 'duppatta', 'women clothing', 70.00, 0.00, 0, 0, 1, 0, '2025-09-07 11:47:20', '2025-09-07 11:57:32', '[\"1757246252_0_images__21_.jfif\",\"1757246252_1_images__20_.jfif\",\"1757246252_2_download__35_.jfif\"]'),
(30, 'Gown', 'western woman attire', 'women clothing', 300.00, 0.00, 0, 0, 1, 0, '2025-09-07 11:50:48', '2025-09-07 11:50:48', '[\"1757245848_0_images__15_.jfif\",\"1757245848_1_images__14_.jfif\",\"1757245848_2_download__33_.jfif\"]'),
(31, 'Banrasi Saree', 'banarasi saree', 'Saree', 600.00, 0.00, 0, 0, 1, 0, '2025-09-07 11:52:52', '2025-09-07 11:52:52', '[\"1757245972_0_images__16_.jfif\",\"1757245972_1_download__34_.jfif\"]'),
(32, 'jacquard saree ', 'jacquard saree ', 'Saree', 500.00, 400.00, 0, 0, 1, 0, '2025-09-07 11:56:07', '2025-09-08 05:12:48', '[\"1757246167_0_images__19_.jfif\",\"1757246167_1_images__18_.jfif\",\"1757246167_2_images__17_.jfif\",\"1757246167_3_images__16_.jfif\"]'),
(33, 'Anarkali Lehenga', 'wedding attire', 'Lehenga', 2800.00, 0.00, 0, 0, 1, 0, '2025-09-07 12:00:24', '2025-09-07 12:00:24', '[\"1757246424_0_images__23_.jfif\",\"1757246424_1_images__22_.jfif\"]'),
(34, 'Banrasi Lehenga', 'Banarasi Lehenga', 'Lehenga', 2800.00, 0.00, 0, 0, 1, 0, '2025-09-07 12:02:41', '2025-09-07 12:02:41', '[\"1757246561_0_images__25_.jfif\",\"1757246561_1_images__24_.jfif\"]');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `product_id`, `product_name`, `current_stock`, `sold_stock`, `dead_stock`, `pending_stock`, `last_updated`) VALUES
(1, 22, 'cotton', 300, NULL, NULL, NULL, '2025-09-07 11:34:03'),
(2, NULL, 'Yarn Threads', 200, 0, 50, NULL, '2025-09-07 11:16:36'),
(4, 24, 'Silk', 400, NULL, NULL, NULL, '2025-09-07 11:20:08'),
(5, 25, 'Linen', 200, NULL, NULL, NULL, '2025-09-07 11:31:44'),
(6, 26, 'Shirt', 250, 140, NULL, NULL, '2025-09-07 12:33:04'),
(7, 27, 'Trousers', 250, 40, NULL, NULL, '2025-09-07 12:33:04'),
(8, 28, 'Linen Pents', 200, 40, NULL, NULL, '2025-09-07 12:33:04'),
(9, 29, 'Net duppatta', 200, 100, NULL, NULL, '2025-09-07 12:18:08'),
(10, 30, 'Gown', 200, 100, NULL, NULL, '2025-09-07 12:23:51'),
(11, 31, 'Banrasi Saree', 500, 10, NULL, NULL, '2025-09-08 07:58:49'),
(13, 33, 'Anarkali Lehenga', 200, 100, NULL, NULL, '2025-09-08 05:10:37'),
(14, 34, 'Banrasi Lehenga', 250, 40, NULL, NULL, '2025-09-07 12:33:04'),
(15, 32, 'jacquard saree ', 300, NULL, NULL, NULL, '2025-09-08 05:48:18');

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
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `is_verified`, `name`, `email`, `password`, `mobile_no`, `role`, `profile_picture`, `auth_provider`, `created_by`, `created_at`) VALUES
(1, 1, 'Maunish', 'maunish247@gmail.com', 'admin@123', 7863039332, 'admin', '1757240344_myphoto.jpg', 'local', 0, '2025-08-16 22:16:49'),
(3, 1, 'Rohan ', 'rohan24@gmail.com', '123', 8246156750, 'staff', '1757240059_8815077.png', 'local', 1, '2025-09-07 10:14:19'),
(4, 1, 'Rahul', 'rahul005@gmail.com', '123', 9978828300, 'staff', '1757240362_download (18).jfif', 'local', 1, '2025-09-07 10:15:37');

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
(17, 1, 'admin@gmail.com', 1, '2025-09-07 10:10:10'),
(18, 1, 'maunish247@gmail.com', 1, '2025-09-07 10:16:19'),
(19, 3, 'rohan24@gmail.com', 1, '2025-09-07 10:19:43'),
(20, 1, 'maunish247@gmail.com', 1, '2025-09-07 10:29:23'),
(21, 1, 'maunish247@gmail.com', 1, '2025-09-08 05:05:09'),
(22, 3, 'rohan24@gmail.com', 1, '2025-09-08 05:21:22'),
(23, 1, 'maunish247@gmail.com', 1, '2025-09-08 05:44:46'),
(24, 1, 'maunish247@gmail.com', 1, '2025-09-08 07:56:41'),
(25, 4, 'rahul005@gmail.com', 1, '2025-09-08 07:58:17'),
(26, 1, 'maunish247@gmail.com', 1, '2025-09-08 07:59:48'),
(27, 1, 'maunish247@gmail.com', 1, '2025-09-08 08:50:35'),
(28, 1, 'maunish247@gmail.com', 1, '2025-09-08 09:11:40'),
(29, 1, 'maunish247@gmail.com', 1, '2025-09-09 03:03:47');

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
(10, 1, '2025-09-07 11:28:37', '22'),
(11, 3, '2025-09-07 11:28:56', '24');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `weblink`
--
ALTER TABLE `weblink`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
