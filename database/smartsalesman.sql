-- phpMyAdmin SQL Dump
-- version 4.9.7deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 30, 2023 at 11:43 AM
-- Server version: 8.0.25-0ubuntu0.20.10.1
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lakmuthu_smartsalesman`
--

-- --------------------------------------------------------

--
-- Table structure for table `distributor_records`
--

CREATE TABLE `distributor_records` (
  `distributor_record_id` int NOT NULL,
  `distributor_id` varchar(255) DEFAULT NULL,
  `record` mediumtext,
  `record_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `outlet_records`
--

CREATE TABLE `outlet_records` (
  `outlet_record_id` int NOT NULL,
  `outlet_id` varchar(255) DEFAULT NULL,
  `record` mediumtext,
  `record_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `sales_rep_records`
--

CREATE TABLE `sales_rep_records` (
  `sales_rep_record_id` int NOT NULL,
  `rep_id` varchar(255) DEFAULT NULL,
  `record` mediumtext,
  `record_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_has_category`
--

CREATE TABLE `supplier_has_category` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `supplier_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `supplier_has_category`
--

INSERT INTO `supplier_has_category` (`id`, `category_id`, `supplier_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_records`
--

CREATE TABLE `supplier_records` (
  `supplier_record_id` int NOT NULL,
  `supplier_id` varchar(255) DEFAULT NULL,
  `record` mediumtext,
  `record_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_app_restrictions`
--

CREATE TABLE `tbl_app_restrictions` (
  `summary_view` int NOT NULL DEFAULT '0',
  `expenses_adding` int NOT NULL DEFAULT '0',
  `messages_view` int NOT NULL DEFAULT '0',
  `pending_orders_view` int NOT NULL DEFAULT '0',
  `invoice_history_view` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_app_restrictions`
--

INSERT INTO `tbl_app_restrictions` (`summary_view`, `expenses_adding`, `messages_view`, `pending_orders_view`, `invoice_history_view`) VALUES
(1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_app_update`
--

CREATE TABLE `tbl_app_update` (
  `id` int NOT NULL,
  `version` varchar(10) NOT NULL,
  `app_location` text NOT NULL,
  `uploaded_date_time` datetime NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tbl_app_update`
--

INSERT INTO `tbl_app_update` (`id`, `version`, `app_location`, `uploaded_date_time`, `status`) VALUES
(1, '3.2.3', 'app/smartsalesman.apk', '2022-08-05 05:43:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance`
--

CREATE TABLE `tbl_attendance` (
  `attendance_id` int NOT NULL,
  `date_time` double NOT NULL,
  `lat` double NOT NULL,
  `lon` double NOT NULL,
  `location_type` varchar(45) NOT NULL,
  `status` int NOT NULL,
  `millage` double NOT NULL,
  `user_id` int NOT NULL,
  `session` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tbl_attendance`
--

INSERT INTO `tbl_attendance` (`attendance_id`, `date_time`, `lat`, `lon`, `location_type`, `status`, `millage`, `user_id`, `session`) VALUES
(1, 1677496203691, 6.8963678, 79.9417075, '0', 0, 1000, 1, '78oif5lm8vl5jau0r6lqgh0phb'),
(2, 1677501837339, 6.8966397, 79.9413975, '0', 0, 1000, 1, '01i2v0uetu83mo027rc18gih3f'),
(3, 1677557003761, 6.8963552, 79.9417091, '0', 0, 0, 2, 'c74up23k85fpenbl43hsb7fd6s'),
(4, 1677588612526, 6.8963546, 79.9416932, '0', 1, 0, 2, 'c74up23k85fpenbl43hsb7fd6s');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `category_id` int NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`category_id`, `name`) VALUES
(1, 'Demo Category');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_city`
--

CREATE TABLE `tbl_city` (
  `id` int NOT NULL,
  `city_name` varchar(45) NOT NULL,
  `route_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tbl_city`
--

INSERT INTO `tbl_city` (`id`, `city_name`, `route_id`) VALUES
(1, 'Colombo', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_credit_orders`
--

CREATE TABLE `tbl_credit_orders` (
  `credit_order_id` int NOT NULL,
  `order_id` int NOT NULL,
  `fixed_total` double NOT NULL,
  `editable_total` double NOT NULL,
  `outlet_id` int NOT NULL,
  `route_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tbl_credit_orders`
--

INSERT INTO `tbl_credit_orders` (`credit_order_id`, `order_id`, `fixed_total`, `editable_total`, `outlet_id`, `route_id`, `user_id`) VALUES
(1, 2, 2200, 2000, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_distributor`
--

CREATE TABLE `tbl_distributor` (
  `distributor_id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tbl_distributor`
--

INSERT INTO `tbl_distributor` (`distributor_id`, `name`, `address`, `contact`) VALUES
(1, 'Demo Distributor', 'Demo Address', '123123');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_distributor_account`
--

CREATE TABLE `tbl_distributor_account` (
  `account_id` int NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `created_date_time` datetime NOT NULL,
  `status` int NOT NULL,
  `distributor_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_distributor_account`
--

INSERT INTO `tbl_distributor_account` (`account_id`, `username`, `password`, `created_date_time`, `status`, `distributor_id`) VALUES
(1, 'demodis', '$2y$10$z0FGQ/OghawKZ.zQouyUOeyIK2ASjsHgu7mHi0fb0owEisI7UrphC', '2023-02-27 16:35:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_distributor_has_products`
--

CREATE TABLE `tbl_distributor_has_products` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `distributor_id` int NOT NULL,
  `qty` int NOT NULL,
  `cost_price` double NOT NULL,
  `assigned_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_distributor_has_products`
--

INSERT INTO `tbl_distributor_has_products` (`id`, `item_id`, `distributor_id`, `qty`, `cost_price`, `assigned_date`) VALUES
(1, 1, 1, 217, 400, '2023-02-27 17:31:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_distributor_has_route`
--

CREATE TABLE `tbl_distributor_has_route` (
  `id` int NOT NULL,
  `route_id` int DEFAULT NULL,
  `distributor_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_distributor_has_tbl_user`
--

CREATE TABLE `tbl_distributor_has_tbl_user` (
  `id` int NOT NULL,
  `distributor_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_date` datetime NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tbl_distributor_has_tbl_user`
--

INSERT INTO `tbl_distributor_has_tbl_user` (`id`, `distributor_id`, `user_id`, `created_date`, `status`) VALUES
(1, 1, 1, '2023-02-27 04:35:59', 1),
(2, 1, 2, '2023-02-28 09:31:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_distributor_product_history`
--

CREATE TABLE `tbl_distributor_product_history` (
  `history_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` int NOT NULL,
  `cost_price` double NOT NULL,
  `assigned_date` datetime NOT NULL,
  `distributor_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_distributor_product_invoice`
--

CREATE TABLE `tbl_distributor_product_invoice` (
  `distributor_invoice_id` int NOT NULL,
  `distributor_id` varchar(255) DEFAULT NULL,
  `admin_id` varchar(255) DEFAULT NULL,
  `note` mediumtext,
  `stat` varchar(3) DEFAULT NULL,
  `pay` varchar(3) DEFAULT NULL,
  `grand_total` varchar(255) DEFAULT NULL,
  `distributor_product_invoice_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_distributor_product_invoice`
--

INSERT INTO `tbl_distributor_product_invoice` (`distributor_invoice_id`, `distributor_id`, `admin_id`, `note`, `stat`, `pay`, `grand_total`, `distributor_product_invoice_datetime`) VALUES
(1, '1', '1', '', '1', '0', '100000', '2023-02-27 17:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_distributor_product_invoice_items`
--

CREATE TABLE `tbl_distributor_product_invoice_items` (
  `distributor_product_invoice_items_id` int NOT NULL,
  `distributor_invoice_id` varchar(255) DEFAULT NULL,
  `item_id` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `cost_price` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_distributor_product_invoice_items`
--

INSERT INTO `tbl_distributor_product_invoice_items` (`distributor_product_invoice_items_id`, `distributor_invoice_id`, `item_id`, `qty`, `cost_price`) VALUES
(1, '1', '1', '250', '400');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_distributor_remove_products_details`
--

CREATE TABLE `tbl_distributor_remove_products_details` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `distributor_id` int NOT NULL,
  `removed_qty` int NOT NULL,
  `remove_person_id` int NOT NULL,
  `remove_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses_types`
--

CREATE TABLE `tbl_expenses_types` (
  `id` int NOT NULL,
  `type` text NOT NULL,
  `status` int NOT NULL,
  `added_date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_expenses_types`
--

INSERT INTO `tbl_expenses_types` (`id`, `type`, `status`, `added_date_time`) VALUES
(1, 'Fuel', 1, '2023-02-27 19:55:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_free_issue_scheme`
--

CREATE TABLE `tbl_free_issue_scheme` (
  `id` int NOT NULL,
  `margin` double NOT NULL,
  `free_qty` double NOT NULL,
  `item_id` int NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_free_issue_scheme`
--

INSERT INTO `tbl_free_issue_scheme` (`id`, `margin`, `free_qty`, `item_id`, `status`) VALUES
(1, 4, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_free_issue_scheme_price_batch`
--

CREATE TABLE `tbl_free_issue_scheme_price_batch` (
  `id` int NOT NULL,
  `margin` double NOT NULL,
  `free_qty` double NOT NULL,
  `item_id` int NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gps_track`
--

CREATE TABLE `tbl_gps_track` (
  `id` int NOT NULL,
  `lon` double NOT NULL,
  `lat` double NOT NULL,
  `speed` varchar(45) NOT NULL,
  `accuracy` double NOT NULL,
  `provider` varchar(45) NOT NULL,
  `date` varchar(45) NOT NULL,
  `time` varchar(45) NOT NULL,
  `bearing` varchar(45) NOT NULL,
  `battery_level` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tbl_gps_track`
--

INSERT INTO `tbl_gps_track` (`id`, `lon`, `lat`, `speed`, `accuracy`, `provider`, `date`, `time`, `bearing`, `battery_level`, `user_id`) VALUES
(1, 79.9417076, 6.8963644, '0.0', 20, 'network', '2023-02-27', '16:38:52', '0.0', 47, 1),
(2, 79.9417006, 6.8963707, '0.0', 19.082, 'network', '2023-02-27', '16:40:55', '0.0', 47, 1),
(3, 79.941186666667, 6.8964466666667, '0.0', 6.9, 'gps', '2023-02-27', '16:41:23', '280.99', 47, 1),
(4, 79.9417099, 6.896348, '0.0', 20, 'network', '2023-02-27', '16:41:24', '0.0', 47, 1),
(5, 79.9417085, 6.8963594, '0.0', 20, 'network', '2023-02-27', '16:41:55', '0.0', 47, 1),
(6, 79.941558333333, 6.8964883333333, '0.0', 13, 'gps', '2023-02-27', '16:42:32', '0.0', 47, 1),
(7, 79.941704, 6.8963514, '0.0', 20, 'network', '2023-02-27', '16:44:35', '0.0', 46, 1),
(8, 79.9417067, 6.8963695, '0.0', 20, 'network', '2023-02-27', '17:02:31', '0.0', 48, 1),
(9, 79.9417039, 6.8963537, '0.0', 20, 'network', '2023-02-27', '17:02:52', '0.0', 48, 1),
(10, 79.9417103, 6.8963645, '0.0', 20, 'network', '2023-02-27', '17:03:22', '0.0', 48, 1),
(11, 79.94138, 6.8965066666667, '1.3773967', 7.6, 'gps', '2023-02-27', '17:04:22', '222.3', 48, 1),
(12, 79.9417093, 6.8963559, '0.0', 20.33, 'network', '2023-02-27', '17:05:29', '0.0', 48, 1),
(13, 79.9417084, 6.8963419, '0.0', 20.145, 'network', '2023-02-27', '17:05:31', '0.0', 48, 1),
(14, 79.941793333333, 6.8964266666667, '0.0', 8.2, 'gps', '2023-02-27', '17:06:06', '0.0', 48, 1),
(15, 79.9417096, 6.8963616, '0.0', 20, 'network', '2023-02-27', '17:06:33', '0.0', 49, 1),
(16, 79.941556666667, 6.8964616666667, '0.0', 7.3, 'gps', '2023-02-27', '17:06:44', '0.0', 49, 1),
(17, 79.9417066, 6.8963704, '0.0', 20, 'network', '2023-02-27', '17:07:29', '0.0', 49, 1),
(18, 79.941621666667, 6.896415, '0.0', 6.5, 'gps', '2023-02-27', '17:08:21', '0.0', 49, 1),
(19, 79.941711, 6.8963416, '0.0', 20, 'network', '2023-02-27', '17:08:35', '0.0', 49, 1),
(20, 79.941613333333, 6.8965383333333, '0.0', 7.6, 'gps', '2023-02-27', '17:09:18', '0.0', 49, 1),
(21, 79.9417113, 6.8963512, '0.0', 20, 'network', '2023-02-27', '17:09:52', '0.0', 49, 1),
(22, 79.941755, 6.8964183333333, '0.0', 13.4, 'gps', '2023-02-27', '17:10:05', '0.0', 49, 1),
(23, 79.9417123, 6.8963398, '0.0', 20, 'network', '2023-02-27', '17:10:41', '0.0', 49, 1),
(24, 79.941707, 6.8963525, '0.0', 20, 'network', '2023-02-27', '17:11:20', '0.0', 49, 1),
(25, 79.9413, 6.89637, '0.0', 6.7, 'gps', '2023-02-27', '17:11:23', '0.0', 49, 1),
(26, 79.9417059, 6.8963694, '0.0', 20, 'network', '2023-02-27', '17:11:50', '0.0', 49, 1),
(27, 79.941721666667, 6.8965033333333, '0.0', 7.1, 'gps', '2023-02-27', '17:12:23', '124.4', 50, 1),
(28, 79.941336666667, 6.8965516666667, '0.0', 10.2, 'gps', '2023-02-27', '17:12:54', '0.0', 50, 1),
(29, 79.941703333333, 6.896725, '8.384311', 7.1, 'gps', '2023-02-27', '17:13:34', '163.89', 50, 1),
(30, 79.9417146, 6.8963392, '0.0', 20, 'network', '2023-02-27', '17:14:47', '0.0', 50, 1),
(31, 79.94167, 6.8964016666667, '0.0', 9.4, 'gps', '2023-02-27', '17:14:52', '0.0', 50, 1),
(32, 79.9417069, 6.8963708, '0.0', 20, 'network', '2023-02-27', '17:15:37', '0.0', 50, 1),
(33, 79.94143, 6.8965883333333, '0.0', 6.5, 'gps', '2023-02-27', '17:16:03', '243.85', 50, 1),
(34, 79.941631666667, 6.89657, '0.0', 6.3, 'gps', '2023-02-27', '17:17:42', '0.0', 51, 1),
(35, 79.9417095, 6.8963406, '0.0', 20, 'network', '2023-02-27', '17:18:19', '0.0', 51, 1),
(36, 79.941603333333, 6.89651, '0.0', 8.1, 'gps', '2023-02-27', '17:18:33', '0.0', 51, 1),
(37, 79.9417055, 6.8963713, '0.0', 20, 'network', '2023-02-27', '17:27:18', '0.0', 52, 1),
(38, 79.9417063, 6.8963475, '0.0', 20, 'network', '2023-02-27', '17:27:19', '0.0', 52, 1),
(39, 79.94144, 6.896785, '0.0', 12.1, 'gps', '2023-02-27', '17:27:35', '0.0', 52, 1),
(40, 79.941313333333, 6.8962183333333, '0.0', 10.4, 'gps', '2023-02-27', '17:28:44', '0.0', 53, 1),
(41, 79.941495, 6.896485, '0.0', 7.7, 'gps', '2023-02-27', '17:29:27', '0.0', 53, 1),
(42, 79.941391666667, 6.8964066666667, '0.0', 8.1, 'gps', '2023-02-27', '17:30:20', '0.0', 53, 1),
(43, 79.9417066, 6.8963585, '0.0', 20, 'network', '2023-02-27', '17:30:58', '0.0', 53, 1),
(44, 79.94145, 6.8964316666667, '0.0', 7.5, 'gps', '2023-02-27', '17:31:22', '0.0', 53, 1),
(45, 79.9417039, 6.896344, '0.0', 20, 'network', '2023-02-27', '17:32:19', '0.0', 53, 1),
(46, 79.94131, 6.8962783333333, '0.0', 7.4, 'gps', '2023-02-27', '17:32:21', '0.0', 53, 1),
(47, 79.9417032, 6.8963533, '0.0', 20, 'network', '2023-02-27', '17:32:50', '0.0', 53, 1),
(48, 79.941668333333, 6.89632, '0.0', 10.5, 'gps', '2023-02-27', '17:33:01', '0.0', 53, 1),
(49, 79.9417115, 6.8963405, '0.0', 20, 'network', '2023-02-27', '17:33:44', '0.0', 54, 1),
(50, 79.94158, 6.896555, '0.0', 7.6, 'gps', '2023-02-27', '17:34:00', '0.0', 54, 1),
(51, 79.9417096, 6.8963616, '0.0', 20, 'network', '2023-02-27', '17:34:46', '0.0', 54, 1),
(52, 79.941518333333, 6.8964616666667, '0.0', 8, 'gps', '2023-02-27', '17:34:55', '0.0', 54, 1),
(53, 79.9417066, 6.8963527, '0.0', 20, 'network', '2023-02-27', '17:35:36', '0.0', 54, 1),
(54, 79.9413, 6.8964216666667, '0.0', 7.7, 'gps', '2023-02-27', '17:35:56', '133.1', 54, 1),
(55, 79.9417063, 6.8963629, '0.0', 20, 'network', '2023-02-27', '17:36:36', '0.0', 54, 1),
(56, 79.941638333333, 6.8965966666667, '0.0', 7.4, 'gps', '2023-02-27', '17:36:59', '0.0', 54, 1),
(57, 79.9417111, 6.8963429, '0.0', 20, 'network', '2023-02-27', '17:37:07', '0.0', 54, 1),
(58, 79.9417079, 6.8963554, '0.0', 20, 'network', '2023-02-27', '17:37:37', '0.0', 54, 1),
(59, 79.941595, 6.8965416666667, '0.0', 6.6, 'gps', '2023-02-27', '17:37:50', '0.0', 54, 1),
(60, 79.941661666667, 6.8963733333333, '0.0', 11.4, 'gps', '2023-02-27', '17:38:35', '0.0', 54, 1),
(61, 79.9417092, 6.8963437, '0.0', 20, 'network', '2023-02-27', '17:38:46', '0.0', 54, 1),
(62, 79.9417057, 6.8963588, '0.0', 20, 'network', '2023-02-27', '17:39:16', '0.0', 55, 1),
(63, 79.941456666667, 6.8965333333333, '0.0', 8.6, 'gps', '2023-02-27', '17:39:40', '0.0', 55, 1),
(64, 79.9417051, 6.8963417, '0.0', 20, 'network', '2023-02-27', '17:39:48', '0.0', 55, 1),
(65, 79.9417051, 6.8963691, '0.0', 20, 'network', '2023-02-27', '17:40:18', '0.0', 55, 1),
(66, 79.941343333333, 6.89665, '0.0', 7.1, 'gps', '2023-02-27', '17:40:38', '0.0', 55, 1),
(67, 79.941396666667, 6.89664, '0.0', 9.5, 'gps', '2023-02-27', '18:14:03', '0.0', 82, 1),
(68, 79.941378333333, 6.8966083333333, '0.0', 8.5, 'gps', '2023-02-27', '18:16:32', '0.0', 81, 1),
(69, 79.941396666667, 6.89664, '0.0', 7.7, 'gps', '2023-02-27', '18:17:14', '0.0', 81, 1),
(70, 79.941681666667, 6.8964, '0.0', 8.2, 'gps', '2023-02-27', '19:49:08', '0.0', 79, 1),
(71, 79.9417054, 6.8963646, '0.0', 20, 'network', '2023-02-27', '19:49:51', '0.0', 79, 1),
(72, 79.941705, 6.8963416666667, '0.0', 9.8, 'gps', '2023-02-27', '19:50:03', '0.0', 79, 1),
(73, 79.9417107, 6.896341, '0.0', 20, 'network', '2023-02-27', '19:50:34', '0.0', 79, 1),
(74, 79.9417084, 6.8963612, '0.0', 20, 'network', '2023-02-27', '19:55:40', '0.0', 79, 1),
(75, 79.941708333333, 6.8963616666667, '0.0', 7, 'gps', '2023-02-27', '19:55:57', '0.0', 79, 1),
(76, 79.9417038, 6.8963438, '0.0', 20, 'network', '2023-02-27', '19:56:16', '0.0', 79, 1),
(77, 79.9417051, 6.8963577, '0.0', 20, 'network', '2023-02-27', '19:59:35', '0.0', 79, 1),
(78, 79.941705, 6.8963701, '0.0', 20, 'network', '2023-02-27', '20:00:16', '0.0', 79, 1),
(79, 79.9417044, 6.8963433, '0.0', 20, 'network', '2023-02-27', '20:40:33', '0.0', 78, 1),
(80, 79.941705, 6.8963433333333, '0.0', 10, 'gps', '2023-02-27', '20:40:36', '0.0', 78, 1),
(81, 79.9417063, 6.8963658, '0.0', 21.6, 'network', '2023-02-28', '09:32:58', '0.0', 49, 2),
(82, 79.941498333333, 6.8965266666667, '0.0', 12.6, 'gps', '2023-02-28', '09:33:34', '0.0', 49, 2),
(83, 79.9417111, 6.8963486, '0.0', 20.4, 'network', '2023-02-28', '09:33:45', '0.0', 49, 2),
(84, 79.941523333333, 6.89647, '0.0', 9, 'gps', '2023-02-28', '09:34:15', '0.0', 49, 2),
(85, 79.941231666667, 6.896475, '0.0', 7.6, 'gps', '2023-02-28', '09:35:04', '0.0', 49, 2),
(86, 79.941313333333, 6.8961233333333, '0.0', 7.4, 'gps', '2023-02-28', '09:35:54', '0.0', 48, 2),
(87, 79.941591666667, 6.8960066666667, '0.0', 7.5, 'gps', '2023-02-28', '09:36:55', '0.0', 48, 2),
(88, 79.9417067, 6.8963675, '0.0', 20, 'network', '2023-02-28', '09:37:12', '0.0', 48, 2),
(89, 79.941697, 6.8963512, '0.0', 19.899, 'network', '2023-02-28', '17:46:13', '0.0', 44, 2),
(90, 79.941108333333, 6.8966583333333, '0.0', 12.1, 'gps', '2023-02-28', '17:46:19', '0.0', 44, 2),
(91, 79.941405, 6.896525, '0.0', 9.1, 'gps', '2023-02-28', '17:47:01', '0.0', 44, 2),
(92, 79.9417149, 6.8963394, '0.0', 20, 'network', '2023-02-28', '17:47:18', '0.0', 44, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_grn_details`
--

CREATE TABLE `tbl_grn_details` (
  `grn_detail_id` int NOT NULL,
  `supplier_id` varchar(255) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `grn_number` varchar(255) DEFAULT NULL,
  `goods_received_date` varchar(255) DEFAULT NULL,
  `note` mediumtext,
  `stat` varchar(3) DEFAULT NULL,
  `grn_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_grn_items`
--

CREATE TABLE `tbl_grn_items` (
  `grn_items_id` int NOT NULL,
  `grn_detail_id` varchar(255) DEFAULT NULL,
  `item_detail_id` varchar(255) DEFAULT NULL,
  `price_batch_id` varchar(255) DEFAULT NULL,
  `cost_price` varchar(255) DEFAULT NULL,
  `selling_price` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `stat` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoices_for_returns`
--

CREATE TABLE `tbl_invoices_for_returns` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `return_order_id` int NOT NULL,
  `note` text NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

CREATE TABLE `tbl_item` (
  `itemId` int NOT NULL,
  `itemCode` varchar(45) NOT NULL,
  `itemDescription` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `packSize` int NOT NULL,
  `stock` int NOT NULL,
  `rp_id` int DEFAULT NULL,
  `genaricName` varchar(100) NOT NULL,
  `re_price` double DEFAULT NULL,
  `minimumQty` int NOT NULL,
  `itemWeight` double DEFAULT NULL,
  `sequenceId` int DEFAULT NULL,
  `maximumQty` int DEFAULT NULL,
  `brand_name` varchar(45) NOT NULL,
  `category_id` int NOT NULL,
  `distributor_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `item_img` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_item`
--

INSERT INTO `tbl_item` (`itemId`, `itemCode`, `itemDescription`, `price`, `packSize`, `stock`, `rp_id`, `genaricName`, `re_price`, `minimumQty`, `itemWeight`, `sequenceId`, `maximumQty`, `brand_name`, `category_id`, `distributor_id`, `supplier_id`, `item_img`) VALUES
(1, 'DP001', 'Demo Product', 440, 0, 250, 0, '1', 400, 1, 0, 1, 1000, 'Demo Brand', 1, 0, 1, 'product_images/1677498952.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item_buying_history`
--

CREATE TABLE `tbl_item_buying_history` (
  `item_buying_history_id` int NOT NULL,
  `item_id` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `cost` varchar(255) DEFAULT NULL,
  `item_buying_history_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item_details`
--

CREATE TABLE `tbl_item_details` (
  `item_detail_Id` int NOT NULL,
  `itemCode` varchar(45) NOT NULL,
  `itemDescription` varchar(100) NOT NULL,
  `packSize` int NOT NULL,
  `rp_id` int DEFAULT NULL,
  `genaricName` varchar(100) NOT NULL,
  `minimumQty` int NOT NULL,
  `itemWeight` double DEFAULT NULL,
  `sequenceId` int DEFAULT NULL,
  `maximumQty` int DEFAULT NULL,
  `brand_name` varchar(45) NOT NULL,
  `category_id` int NOT NULL,
  `distributor_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `item_img` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_item_details`
--

INSERT INTO `tbl_item_details` (`item_detail_Id`, `itemCode`, `itemDescription`, `packSize`, `rp_id`, `genaricName`, `minimumQty`, `itemWeight`, `sequenceId`, `maximumQty`, `brand_name`, `category_id`, `distributor_id`, `supplier_id`, `item_img`) VALUES
(1, 'DP001', 'Demo Product', 0, 0, 'DP001-Demo Product', 1, 0, 1, 1000, 'Demo Brand', 1, 0, 1, 'product_images/1677498952.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item_other_prices`
--

CREATE TABLE `tbl_item_other_prices` (
  `id` int NOT NULL,
  `return_price` double NOT NULL,
  `distributor_price` double NOT NULL,
  `item_id` int NOT NULL,
  `mrp` double NOT NULL,
  `price_batch_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_item_other_prices`
--

INSERT INTO `tbl_item_other_prices` (`id`, `return_price`, `distributor_price`, `item_id`, `mrp`, `price_batch_code`) VALUES
(1, 150, 400, 1, 500, 'PB001');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_office_msgs`
--

CREATE TABLE `tbl_office_msgs` (
  `msg_id` int NOT NULL,
  `subject` text NOT NULL,
  `msg` text NOT NULL,
  `added_date_time` datetime NOT NULL,
  `status` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int NOT NULL,
  `order_id` varchar(45) NOT NULL,
  `order_type` int NOT NULL,
  `invoice_date` varchar(45) NOT NULL,
  `invoice_time` varchar(45) NOT NULL,
  `lon` double NOT NULL,
  `lat` double NOT NULL,
  `battery_level` int NOT NULL,
  `timestamp` double NOT NULL,
  `payment_status` int NOT NULL,
  `optional_discount_amount` double DEFAULT NULL,
  `optional_discount_percentage` double DEFAULT NULL,
  `payment_method` int NOT NULL,
  `app_version` varchar(45) NOT NULL,
  `session_id` varchar(45) NOT NULL,
  `outlet_id` int NOT NULL,
  `route_id` int NOT NULL,
  `distributor_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `order_id`, `order_type`, `invoice_date`, `invoice_time`, `lon`, `lat`, `battery_level`, `timestamp`, `payment_status`, `optional_discount_amount`, `optional_discount_percentage`, `payment_method`, `app_version`, `session_id`, `outlet_id`, `route_id`, `distributor_id`, `user_id`) VALUES
(1, 'INV/SO/1/000001', 0, '2023-02-27', '17:32:11', 79.94145, 6.8964316666667, 53, 1677499288273, 1, 0, 0, 0, '3.2.3', '78oif5lm8vl5jau0r6lqgh0phb', 1, 1, 1, 1),
(2, 'INV/SO/1/000002', 0, '2023-02-27', '17:33:36', 79.941668333333, 6.89632, 53, 1677499392927, 0, 0, 0, 2, '3.2.3', '78oif5lm8vl5jau0r6lqgh0phb', 1, 1, 1, 1),
(3, 'INV/SO/1/000003', 0, '2023-02-27', '18:30:19', 79.941396666667, 6.89664, 81, 1677502792020, 1, 0, 0, 0, '3.2.3', '01i2v0uetu83mo027rc18gih3f', 1, 1, 1, 1),
(4, 'INV/SO/WEB/1/000001', 0, '2023-02-27', '20:13:56', -1, -1, -1, 1677509036, 1, 0, 0, 0, 'web', 't1gpsee45jtp6dqm07htncr0ac', 1, 1, 1, 0),
(5, 'INV/SO/2/000001', 0, '2023-02-28', '09:34:17', 79.941498333333, 6.8965266666667, 49, 1677557022559, 1, 0, 0, 0, '3.2.3', 'c74up23k85fpenbl43hsb7fd6s', 1, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_cheque_payment_details`
--

CREATE TABLE `tbl_order_cheque_payment_details` (
  `id` int NOT NULL,
  `cheque_no` text NOT NULL,
  `bank` text NOT NULL,
  `date_to_cash` date NOT NULL,
  `amount` double NOT NULL,
  `is_cleared` int NOT NULL,
  `added_date` datetime NOT NULL,
  `invoice_id` int NOT NULL,
  `added_user_id` int NOT NULL,
  `payment_history_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_cheque_payment_realized_date`
--

CREATE TABLE `tbl_order_cheque_payment_realized_date` (
  `id` int NOT NULL,
  `cheque_id` varchar(255) DEFAULT NULL,
  `cheque_realized_date` datetime NOT NULL,
  `cheque_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_delivery`
--

CREATE TABLE `tbl_order_delivery` (
  `delivery_id` int NOT NULL,
  `order_id` int NOT NULL,
  `delivery_status` int NOT NULL,
  `delivered_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_order_delivery`
--

INSERT INTO `tbl_order_delivery` (`delivery_id`, `order_id`, `delivery_status`, `delivered_datetime`) VALUES
(1, 1, 0, '1999-01-01 12:00:00'),
(2, 2, 0, '1999-01-01 12:00:00'),
(3, 3, 0, '1999-01-01 12:00:00'),
(4, 4, 0, '1999-01-01 12:00:00'),
(5, 5, 0, '1999-01-01 12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_detail_packs`
--

CREATE TABLE `tbl_order_detail_packs` (
  `id` int NOT NULL,
  `unit_per_pack` int NOT NULL,
  `order_item_details_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_order_detail_packs`
--

INSERT INTO `tbl_order_detail_packs` (`id`, `unit_per_pack`, `order_item_details_id`) VALUES
(1, 10, 1),
(2, 10, 2),
(3, 10, 3),
(4, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_free_issues`
--

CREATE TABLE `tbl_order_free_issues` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `item_name` text NOT NULL,
  `item_price` double NOT NULL,
  `free_qty` int NOT NULL,
  `order_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `distributor_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_order_free_issues`
--

INSERT INTO `tbl_order_free_issues` (`id`, `item_id`, `item_name`, `item_price`, `free_qty`, `order_id`, `supplier_id`, `distributor_id`) VALUES
(1, 1, 'Demo Product', 440, 1, 2, 0, 0),
(2, 1, 'Demo Product', 440, 2, 3, 0, 0),
(3, 1, 'Demo Product', 440, 3, 5, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_item_details`
--

CREATE TABLE `tbl_order_item_details` (
  `id` int NOT NULL,
  `itemId` int NOT NULL,
  `order_id` int NOT NULL,
  `qty` int NOT NULL,
  `discounted_price` double DEFAULT NULL,
  `discounted_value` double DEFAULT NULL,
  `price` double DEFAULT NULL,
  `rpId` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_order_item_details`
--

INSERT INTO `tbl_order_item_details` (`id`, `itemId`, `order_id`, `qty`, `discounted_price`, `discounted_value`, `price`, `rpId`) VALUES
(1, 1, 1, 3, 0, 0, 440, 0),
(2, 1, 2, 5, 0, 0, 440, 0),
(3, 1, 3, 9, 0, 0, 440, 0),
(4, 1, 4, 1, 0, 0, 440, 0),
(5, 1, 5, 15, 0, 0, 440, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_other_item_details`
--

CREATE TABLE `tbl_other_item_details` (
  `id` int NOT NULL,
  `pack_size` double NOT NULL,
  `item_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_other_item_details`
--

INSERT INTO `tbl_other_item_details` (`id`, `pack_size`, `item_id`) VALUES
(1, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_outlet`
--

CREATE TABLE `tbl_outlet` (
  `outlet_id` int NOT NULL,
  `outlet_name` varchar(100) NOT NULL,
  `owner_name` varchar(45) NOT NULL,
  `contact` varchar(45) NOT NULL,
  `address` text NOT NULL,
  `lat` varchar(100) NOT NULL,
  `lon` varchar(100) NOT NULL,
  `image` text,
  `outlet_type` int DEFAULT NULL,
  `outlet_discount` double DEFAULT NULL,
  `last_order_value` double DEFAULT NULL,
  `current_month_purchase` double DEFAULT NULL,
  `avarage_purchases` double DEFAULT NULL,
  `outstanding` double DEFAULT NULL,
  `category` int NOT NULL,
  `sequence` int DEFAULT NULL,
  `grade` int DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `route_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_outlet`
--

INSERT INTO `tbl_outlet` (`outlet_id`, `outlet_name`, `owner_name`, `contact`, `address`, `lat`, `lon`, `image`, `outlet_type`, `outlet_discount`, `last_order_value`, `current_month_purchase`, `avarage_purchases`, `outstanding`, `category`, `sequence`, `grade`, `created_date`, `route_id`) VALUES
(1, 'Demo Shop', 'Demo Owner', '123123', 'Demo Address', '6.8963679', '79.9417078', 'outlet_images/1677498161.jpeg', 0, 0, 0, 0, 0, 0, 6, 0, 0, '2023-02-27 05:12:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_outlet_unproductive_remarks`
--

CREATE TABLE `tbl_outlet_unproductive_remarks` (
  `id` int NOT NULL,
  `reason_id` int NOT NULL,
  `remark` text NOT NULL,
  `outlet_id` int NOT NULL,
  `route_id` int NOT NULL,
  `user_id` int NOT NULL,
  `server_date` datetime NOT NULL,
  `device_date` datetime NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `image_path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_outstanding_payments`
--

CREATE TABLE `tbl_outstanding_payments` (
  `id` int NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `amount` double NOT NULL,
  `date_time` datetime NOT NULL,
  `sales_user` int NOT NULL,
  `admin_user` int NOT NULL,
  `payment_method` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_outstanding_payments`
--

INSERT INTO `tbl_outstanding_payments` (`id`, `order_id`, `amount`, `date_time`, `sales_user`, `admin_user`, `payment_method`) VALUES
(1, '2', 200, '2023-02-27 18:14:41', 1, 0, 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_outstanding_payments_history_remove_data`
--

CREATE TABLE `tbl_outstanding_payments_history_remove_data` (
  `id` int NOT NULL,
  `web_user_id` int NOT NULL,
  `order_id` int NOT NULL,
  `sales_rep_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `amount` int NOT NULL,
  `remove_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_batch`
--

CREATE TABLE `tbl_product_batch` (
  `batch_id` int NOT NULL,
  `batch_name` varchar(45) NOT NULL,
  `selling_price` double NOT NULL,
  `buying_price` double NOT NULL,
  `stock` int NOT NULL,
  `pack_size` int NOT NULL,
  `itemId` int NOT NULL,
  `created_date` datetime NOT NULL,
  `status` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rep_target`
--

CREATE TABLE `tbl_rep_target` (
  `id` int NOT NULL,
  `target_amount` double NOT NULL,
  `target_qty` int NOT NULL,
  `validity_period` date NOT NULL,
  `created_date` datetime NOT NULL,
  `status` int NOT NULL,
  `userId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rep_target_amount_wise`
--

CREATE TABLE `tbl_rep_target_amount_wise` (
  `id` int NOT NULL,
  `amount` double NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rep_target_qty_wise`
--

CREATE TABLE `tbl_rep_target_qty_wise` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` int NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_return_order`
--

CREATE TABLE `tbl_return_order` (
  `id` int NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `return_type` int NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_time` time NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `battery_level` int NOT NULL,
  `timestamp` double NOT NULL,
  `app_version` varchar(45) NOT NULL,
  `session_id` varchar(45) NOT NULL,
  `outlet_id` int NOT NULL,
  `route_id` int NOT NULL,
  `distributor_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_return_order`
--

INSERT INTO `tbl_return_order` (`id`, `order_id`, `return_type`, `invoice_date`, `invoice_time`, `lat`, `lng`, `battery_level`, `timestamp`, `app_version`, `session_id`, `outlet_id`, `route_id`, `distributor_id`, `user_id`) VALUES
(1, 'INV/RET/1/000001', 1, '2023-02-27', '19:48:53', 6.89664, 79.941396666667, 80, 1677507504670, '3.2.3', '01i2v0uetu83mo027rc18gih3f', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_return_order_item_details`
--

CREATE TABLE `tbl_return_order_item_details` (
  `id` int NOT NULL,
  `itemId` int NOT NULL,
  `order_id` int NOT NULL,
  `qty` int NOT NULL,
  `discounted_price` double DEFAULT NULL,
  `discounted_value` double DEFAULT NULL,
  `price` double DEFAULT NULL,
  `rpId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_return_order_item_details`
--

INSERT INTO `tbl_return_order_item_details` (`id`, `itemId`, `order_id`, `qty`, `discounted_price`, `discounted_value`, `price`, `rpId`) VALUES
(1, 1, 1, 1, 0, 0, 150, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_route`
--

CREATE TABLE `tbl_route` (
  `route_id` int NOT NULL,
  `route_name` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_route`
--

INSERT INTO `tbl_route` (`route_id`, `route_name`, `created_date`, `status`) VALUES
(1, 'Demo Route', '2023-02-27 04:38:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_rep_expenses`
--

CREATE TABLE `tbl_sales_rep_expenses` (
  `id` int NOT NULL,
  `type_id` int NOT NULL,
  `amount` double NOT NULL,
  `remark` text NOT NULL,
  `date_time` datetime NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sales_rep_expenses`
--

INSERT INTO `tbl_sales_rep_expenses` (`id`, `type_id`, `amount`, `remark`, `date_time`, `user_id`) VALUES
(1, 1, 1000, 'demo remark', '2023-02-27 20:00:07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shop_category`
--

CREATE TABLE `tbl_shop_category` (
  `category_id` int NOT NULL,
  `category_name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_shop_category`
--

INSERT INTO `tbl_shop_category` (`category_id`, `category_name`) VALUES
(1, 'Super Market'),
(2, 'Retail Shop'),
(3, 'Mini Super'),
(4, 'Wholesaler'),
(5, 'Large Stores / Grocery'),
(6, 'Grocery'),
(7, 'Cooperative Shop'),
(8, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_staff`
--

CREATE TABLE `tbl_staff` (
  `staff_id` int NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `address` mediumtext,
  `birthdate` varchar(255) DEFAULT NULL,
  `nic_number` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `job_description` varchar(255) DEFAULT NULL,
  `salary` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `stat` varchar(3) DEFAULT NULL,
  `staff_reg_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_add_details`
--

CREATE TABLE `tbl_stock_add_details` (
  `stock_add_id` int NOT NULL,
  `admin_id` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `note` mediumtext,
  `stat` int DEFAULT NULL,
  `stock_add_details_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_stock_add_details`
--

INSERT INTO `tbl_stock_add_details` (`stock_add_id`, `admin_id`, `supplier_id`, `note`, `stat`, `stock_add_details_datetime`) VALUES
(1, 1, 1, '', 1, '2023-02-27 17:30:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_add_items`
--

CREATE TABLE `tbl_stock_add_items` (
  `stock_add_items_id` int NOT NULL,
  `stock_add_id` varchar(255) DEFAULT NULL,
  `item_id` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_stock_add_items`
--

INSERT INTO `tbl_stock_add_items` (`stock_add_items_id`, `stock_add_id`, `item_id`, `qty`) VALUES
(2, '1', '1', '500');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `supplier_id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `distributor_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_supplier`
--

INSERT INTO `tbl_supplier` (`supplier_id`, `name`, `distributor_id`) VALUES
(1, 'Demo Supplier', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier_account`
--

CREATE TABLE `tbl_supplier_account` (
  `id` int NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `status` int NOT NULL,
  `created_date` datetime NOT NULL,
  `supplier_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier_details`
--

CREATE TABLE `tbl_supplier_details` (
  `supplier_details_id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `nic_number` varchar(255) DEFAULT NULL,
  `br_number` varchar(255) DEFAULT NULL,
  `contact_person_name` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` mediumtext,
  `supplier_reg_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_supplier_details`
--

INSERT INTO `tbl_supplier_details` (`supplier_details_id`, `supplier_id`, `supplier_name`, `nic_number`, `br_number`, `contact_person_name`, `contact_number`, `address`, `supplier_reg_datetime`) VALUES
(1, 1, 'Demo Supplier', '123X', '123BR', 'Demo Contact Person', '123123', 'Demo Supplier Address', '2023-02-27 17:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier_has_products`
--

CREATE TABLE `tbl_supplier_has_products` (
  `supplier_has_products_id` int NOT NULL,
  `supplier_id` varchar(255) DEFAULT NULL,
  `item_detail_Id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_supplier_has_products`
--

INSERT INTO `tbl_supplier_has_products` (`supplier_has_products_id`, `supplier_id`, `item_detail_Id`) VALUES
(1, '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_system_check`
--

CREATE TABLE `tbl_system_check` (
  `id` int NOT NULL,
  `check_system` int NOT NULL,
  `last_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_system_check`
--

INSERT INTO `tbl_system_check` (`id`, `check_system`, `last_updated`) VALUES
(1, 1, '2023-02-27 16:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unproductive_reasons`
--

CREATE TABLE `tbl_unproductive_reasons` (
  `id` int NOT NULL,
  `reason` text NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `created_date` datetime NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `status` int NOT NULL,
  `login_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `name`, `username`, `password`, `created_date`, `address`, `contact_no`, `status`, `login_status`) VALUES
(1, 'Demo Representative', 'demo_rep', '123', '2023-02-27 04:35:59', 'Demo Address', '123123', 1, 0),
(2, 'test', 'test', '123123', '2023-02-28 09:31:10', 'colombo', '0764415555', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_agent_details`
--

CREATE TABLE `tbl_user_agent_details` (
  `id` int NOT NULL,
  `details` text NOT NULL,
  `date_time` datetime NOT NULL,
  `user_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_agent_details`
--

INSERT INTO `tbl_user_agent_details` (`id`, `details`, `date_time`, `user_id`) VALUES
(1, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 16:27:16', 1),
(2, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 16:27:26', 1),
(3, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 16:33:03', 1),
(4, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 17:16:54', 1),
(5, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 17:17:23', 1),
(6, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 17:23:58', 1),
(7, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 18:12:38', 1),
(8, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 19:29:11', 1),
(9, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 20:12:17', 1),
(10, 'INVOICE_SAVE : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 20:13:56', 1),
(11, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 20:28:22', 1),
(12, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 20:46:47', 1),
(13, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-27 20:47:19', 1),
(14, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-28 00:34:13', 1),
(15, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-28 00:34:31', 1),
(16, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-28 02:14:49', 1),
(17, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-28 08:32:58', 1),
(18, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-02-28 09:04:34', 1),
(19, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-03-01 01:15:37', 1),
(20, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-03-08 02:55:58', 1),
(21, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-03-08 02:56:02', 1),
(22, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36', '2023-03-08 02:56:05', 1),
(23, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36', '2023-05-22 15:40:40', 1),
(24, 'DASHBOARD : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36', '2023-05-30 11:42:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_has_routes`
--

CREATE TABLE `tbl_user_has_routes` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `route_id` int NOT NULL,
  `date` date NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_has_routes`
--

INSERT INTO `tbl_user_has_routes` (`id`, `user_id`, `route_id`, `date`, `status`) VALUES
(1, 1, 1, '2023-02-27', 1),
(2, 2, 1, '2023-02-28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_van_loading_history`
--

CREATE TABLE `tbl_van_loading_history` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` double NOT NULL,
  `supplier_id` int NOT NULL,
  `dist_id` int NOT NULL,
  `date_time` datetime NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_web_console_user_account`
--

CREATE TABLE `tbl_web_console_user_account` (
  `user_id` int NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `created_date` datetime NOT NULL,
  `active_status` int NOT NULL,
  `stat` int NOT NULL,
  `name` text NOT NULL,
  `dist_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_web_console_user_account`
--

INSERT INTO `tbl_web_console_user_account` (`user_id`, `username`, `password`, `created_date`, `active_status`, `stat`, `name`, `dist_id`) VALUES
(1, 'admin', 'admin*321', '2023-02-27 16:24:51', 1, 1, 'Super Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_web_console_user_account_login_history`
--

CREATE TABLE `tbl_web_console_user_account_login_history` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `distributor_id` int NOT NULL,
  `loged_ip` varchar(255) NOT NULL,
  `logged_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_web_console_user_account_login_history`
--

INSERT INTO `tbl_web_console_user_account_login_history` (`id`, `admin_id`, `distributor_id`, `loged_ip`, `logged_datetime`) VALUES
(1, 1, 0, '124.43.67.44', '2023-02-27 04:27:16'),
(2, 1, 0, '124.43.67.44', '2023-02-27 06:12:38'),
(3, 1, 0, '124.43.67.44', '2023-02-27 07:29:11'),
(4, 1, 0, '124.43.67.44', '2023-02-27 08:12:17'),
(5, 1, 0, '124.43.67.44', '2023-02-27 08:28:22'),
(6, 1, 0, '124.43.67.44', '2023-02-27 08:46:47'),
(7, 1, 0, '124.43.67.44', '2023-02-28 12:34:13'),
(8, 1, 0, '124.43.67.44', '2023-02-28 02:14:49'),
(9, 1, 0, '124.43.67.44', '2023-02-28 08:32:58'),
(10, 1, 0, '124.43.67.44', '2023-02-28 09:04:34'),
(11, 1, 0, '112.135.77.23', '2023-03-01 01:15:37'),
(12, 1, 0, '124.43.67.44', '2023-03-08 02:55:58'),
(13, 1, 0, '124.43.67.44', '2023-05-22 03:40:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_working_days`
--

CREATE TABLE `tbl_working_days` (
  `id` int NOT NULL,
  `year` varchar(45) NOT NULL,
  `month` varchar(45) NOT NULL,
  `days_count` int NOT NULL,
  `starting_from` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_working_days`
--

INSERT INTO `tbl_working_days` (`id`, `year`, `month`, `days_count`, `starting_from`) VALUES
(1, '2023', 'February', 28, '2023-02-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `distributor_records`
--
ALTER TABLE `distributor_records`
  ADD PRIMARY KEY (`distributor_record_id`);

--
-- Indexes for table `outlet_records`
--
ALTER TABLE `outlet_records`
  ADD PRIMARY KEY (`outlet_record_id`);

--
-- Indexes for table `sales_rep_records`
--
ALTER TABLE `sales_rep_records`
  ADD PRIMARY KEY (`sales_rep_record_id`);

--
-- Indexes for table `supplier_has_category`
--
ALTER TABLE `supplier_has_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_records`
--
ALTER TABLE `supplier_records`
  ADD PRIMARY KEY (`supplier_record_id`);

--
-- Indexes for table `tbl_app_restrictions`
--
ALTER TABLE `tbl_app_restrictions`
  ADD PRIMARY KEY (`summary_view`);

--
-- Indexes for table `tbl_app_update`
--
ALTER TABLE `tbl_app_update`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_city`
--
ALTER TABLE `tbl_city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_credit_orders`
--
ALTER TABLE `tbl_credit_orders`
  ADD PRIMARY KEY (`credit_order_id`);

--
-- Indexes for table `tbl_distributor`
--
ALTER TABLE `tbl_distributor`
  ADD PRIMARY KEY (`distributor_id`);

--
-- Indexes for table `tbl_distributor_account`
--
ALTER TABLE `tbl_distributor_account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `tbl_distributor_has_products`
--
ALTER TABLE `tbl_distributor_has_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_distributor_has_route`
--
ALTER TABLE `tbl_distributor_has_route`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_distributor_has_tbl_user`
--
ALTER TABLE `tbl_distributor_has_tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_distributor_product_history`
--
ALTER TABLE `tbl_distributor_product_history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `tbl_distributor_product_invoice`
--
ALTER TABLE `tbl_distributor_product_invoice`
  ADD PRIMARY KEY (`distributor_invoice_id`);

--
-- Indexes for table `tbl_distributor_product_invoice_items`
--
ALTER TABLE `tbl_distributor_product_invoice_items`
  ADD PRIMARY KEY (`distributor_product_invoice_items_id`);

--
-- Indexes for table `tbl_distributor_remove_products_details`
--
ALTER TABLE `tbl_distributor_remove_products_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_expenses_types`
--
ALTER TABLE `tbl_expenses_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_free_issue_scheme`
--
ALTER TABLE `tbl_free_issue_scheme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_free_issue_scheme_price_batch`
--
ALTER TABLE `tbl_free_issue_scheme_price_batch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_gps_track`
--
ALTER TABLE `tbl_gps_track`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_grn_details`
--
ALTER TABLE `tbl_grn_details`
  ADD PRIMARY KEY (`grn_detail_id`);

--
-- Indexes for table `tbl_grn_items`
--
ALTER TABLE `tbl_grn_items`
  ADD PRIMARY KEY (`grn_items_id`);

--
-- Indexes for table `tbl_invoices_for_returns`
--
ALTER TABLE `tbl_invoices_for_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD PRIMARY KEY (`itemId`);

--
-- Indexes for table `tbl_item_buying_history`
--
ALTER TABLE `tbl_item_buying_history`
  ADD PRIMARY KEY (`item_buying_history_id`);

--
-- Indexes for table `tbl_item_details`
--
ALTER TABLE `tbl_item_details`
  ADD PRIMARY KEY (`item_detail_Id`);

--
-- Indexes for table `tbl_item_other_prices`
--
ALTER TABLE `tbl_item_other_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_office_msgs`
--
ALTER TABLE `tbl_office_msgs`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_cheque_payment_details`
--
ALTER TABLE `tbl_order_cheque_payment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_cheque_payment_realized_date`
--
ALTER TABLE `tbl_order_cheque_payment_realized_date`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_delivery`
--
ALTER TABLE `tbl_order_delivery`
  ADD PRIMARY KEY (`delivery_id`);

--
-- Indexes for table `tbl_order_detail_packs`
--
ALTER TABLE `tbl_order_detail_packs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_free_issues`
--
ALTER TABLE `tbl_order_free_issues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_item_details`
--
ALTER TABLE `tbl_order_item_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_other_item_details`
--
ALTER TABLE `tbl_other_item_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_outlet`
--
ALTER TABLE `tbl_outlet`
  ADD PRIMARY KEY (`outlet_id`);

--
-- Indexes for table `tbl_outlet_unproductive_remarks`
--
ALTER TABLE `tbl_outlet_unproductive_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_outstanding_payments`
--
ALTER TABLE `tbl_outstanding_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_outstanding_payments_history_remove_data`
--
ALTER TABLE `tbl_outstanding_payments_history_remove_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_batch`
--
ALTER TABLE `tbl_product_batch`
  ADD PRIMARY KEY (`batch_id`);

--
-- Indexes for table `tbl_rep_target`
--
ALTER TABLE `tbl_rep_target`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rep_target_amount_wise`
--
ALTER TABLE `tbl_rep_target_amount_wise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rep_target_qty_wise`
--
ALTER TABLE `tbl_rep_target_qty_wise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_return_order`
--
ALTER TABLE `tbl_return_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_return_order_item_details`
--
ALTER TABLE `tbl_return_order_item_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_route`
--
ALTER TABLE `tbl_route`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `tbl_sales_rep_expenses`
--
ALTER TABLE `tbl_sales_rep_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_shop_category`
--
ALTER TABLE `tbl_shop_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_staff`
--
ALTER TABLE `tbl_staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `tbl_stock_add_details`
--
ALTER TABLE `tbl_stock_add_details`
  ADD PRIMARY KEY (`stock_add_id`);

--
-- Indexes for table `tbl_stock_add_items`
--
ALTER TABLE `tbl_stock_add_items`
  ADD PRIMARY KEY (`stock_add_items_id`);

--
-- Indexes for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tbl_supplier_account`
--
ALTER TABLE `tbl_supplier_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_supplier_details`
--
ALTER TABLE `tbl_supplier_details`
  ADD PRIMARY KEY (`supplier_details_id`);

--
-- Indexes for table `tbl_supplier_has_products`
--
ALTER TABLE `tbl_supplier_has_products`
  ADD PRIMARY KEY (`supplier_has_products_id`);

--
-- Indexes for table `tbl_system_check`
--
ALTER TABLE `tbl_system_check`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_unproductive_reasons`
--
ALTER TABLE `tbl_unproductive_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_agent_details`
--
ALTER TABLE `tbl_user_agent_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_has_routes`
--
ALTER TABLE `tbl_user_has_routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_van_loading_history`
--
ALTER TABLE `tbl_van_loading_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_web_console_user_account`
--
ALTER TABLE `tbl_web_console_user_account`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_web_console_user_account_login_history`
--
ALTER TABLE `tbl_web_console_user_account_login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_working_days`
--
ALTER TABLE `tbl_working_days`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `distributor_records`
--
ALTER TABLE `distributor_records`
  MODIFY `distributor_record_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outlet_records`
--
ALTER TABLE `outlet_records`
  MODIFY `outlet_record_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_rep_records`
--
ALTER TABLE `sales_rep_records`
  MODIFY `sales_rep_record_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_has_category`
--
ALTER TABLE `supplier_has_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_records`
--
ALTER TABLE `supplier_records`
  MODIFY `supplier_record_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_app_update`
--
ALTER TABLE `tbl_app_update`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  MODIFY `attendance_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_city`
--
ALTER TABLE `tbl_city`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_credit_orders`
--
ALTER TABLE `tbl_credit_orders`
  MODIFY `credit_order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_distributor`
--
ALTER TABLE `tbl_distributor`
  MODIFY `distributor_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_distributor_account`
--
ALTER TABLE `tbl_distributor_account`
  MODIFY `account_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_distributor_has_products`
--
ALTER TABLE `tbl_distributor_has_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_distributor_has_route`
--
ALTER TABLE `tbl_distributor_has_route`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_distributor_has_tbl_user`
--
ALTER TABLE `tbl_distributor_has_tbl_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_distributor_product_history`
--
ALTER TABLE `tbl_distributor_product_history`
  MODIFY `history_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_distributor_product_invoice`
--
ALTER TABLE `tbl_distributor_product_invoice`
  MODIFY `distributor_invoice_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_distributor_product_invoice_items`
--
ALTER TABLE `tbl_distributor_product_invoice_items`
  MODIFY `distributor_product_invoice_items_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_distributor_remove_products_details`
--
ALTER TABLE `tbl_distributor_remove_products_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_expenses_types`
--
ALTER TABLE `tbl_expenses_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_free_issue_scheme`
--
ALTER TABLE `tbl_free_issue_scheme`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_free_issue_scheme_price_batch`
--
ALTER TABLE `tbl_free_issue_scheme_price_batch`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_gps_track`
--
ALTER TABLE `tbl_gps_track`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `tbl_grn_details`
--
ALTER TABLE `tbl_grn_details`
  MODIFY `grn_detail_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_grn_items`
--
ALTER TABLE `tbl_grn_items`
  MODIFY `grn_items_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_invoices_for_returns`
--
ALTER TABLE `tbl_invoices_for_returns`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_item`
--
ALTER TABLE `tbl_item`
  MODIFY `itemId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_item_buying_history`
--
ALTER TABLE `tbl_item_buying_history`
  MODIFY `item_buying_history_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_item_details`
--
ALTER TABLE `tbl_item_details`
  MODIFY `item_detail_Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_item_other_prices`
--
ALTER TABLE `tbl_item_other_prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_office_msgs`
--
ALTER TABLE `tbl_office_msgs`
  MODIFY `msg_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_order_cheque_payment_details`
--
ALTER TABLE `tbl_order_cheque_payment_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_cheque_payment_realized_date`
--
ALTER TABLE `tbl_order_cheque_payment_realized_date`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_delivery`
--
ALTER TABLE `tbl_order_delivery`
  MODIFY `delivery_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_order_detail_packs`
--
ALTER TABLE `tbl_order_detail_packs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_order_free_issues`
--
ALTER TABLE `tbl_order_free_issues`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_order_item_details`
--
ALTER TABLE `tbl_order_item_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_other_item_details`
--
ALTER TABLE `tbl_other_item_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_outlet`
--
ALTER TABLE `tbl_outlet`
  MODIFY `outlet_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_outlet_unproductive_remarks`
--
ALTER TABLE `tbl_outlet_unproductive_remarks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_outstanding_payments`
--
ALTER TABLE `tbl_outstanding_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_outstanding_payments_history_remove_data`
--
ALTER TABLE `tbl_outstanding_payments_history_remove_data`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_batch`
--
ALTER TABLE `tbl_product_batch`
  MODIFY `batch_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rep_target`
--
ALTER TABLE `tbl_rep_target`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rep_target_amount_wise`
--
ALTER TABLE `tbl_rep_target_amount_wise`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rep_target_qty_wise`
--
ALTER TABLE `tbl_rep_target_qty_wise`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_return_order`
--
ALTER TABLE `tbl_return_order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_return_order_item_details`
--
ALTER TABLE `tbl_return_order_item_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_route`
--
ALTER TABLE `tbl_route`
  MODIFY `route_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_sales_rep_expenses`
--
ALTER TABLE `tbl_sales_rep_expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_shop_category`
--
ALTER TABLE `tbl_shop_category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_staff`
--
ALTER TABLE `tbl_staff`
  MODIFY `staff_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_stock_add_details`
--
ALTER TABLE `tbl_stock_add_details`
  MODIFY `stock_add_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_stock_add_items`
--
ALTER TABLE `tbl_stock_add_items`
  MODIFY `stock_add_items_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  MODIFY `supplier_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_supplier_account`
--
ALTER TABLE `tbl_supplier_account`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_supplier_details`
--
ALTER TABLE `tbl_supplier_details`
  MODIFY `supplier_details_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_supplier_has_products`
--
ALTER TABLE `tbl_supplier_has_products`
  MODIFY `supplier_has_products_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_system_check`
--
ALTER TABLE `tbl_system_check`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_unproductive_reasons`
--
ALTER TABLE `tbl_unproductive_reasons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user_agent_details`
--
ALTER TABLE `tbl_user_agent_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_user_has_routes`
--
ALTER TABLE `tbl_user_has_routes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_van_loading_history`
--
ALTER TABLE `tbl_van_loading_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_web_console_user_account`
--
ALTER TABLE `tbl_web_console_user_account`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_web_console_user_account_login_history`
--
ALTER TABLE `tbl_web_console_user_account_login_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_working_days`
--
ALTER TABLE `tbl_working_days`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
