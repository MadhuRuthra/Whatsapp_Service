-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 11, 2024 at 04:58 AM
-- Server version: 8.0.39
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `whatsapp_messenger_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `compose_whatsapp_2`
--

CREATE TABLE `compose_whatsapp_2` (
  `compose_whatsapp_id` int NOT NULL,
  `user_id` int NOT NULL,
  `store_id` int NOT NULL,
  `whatspp_config_id` int NOT NULL,
  `mobile_nos` longblob NOT NULL,
  `sender_mobile_nos` longblob NOT NULL,
  `whatsapp_content` varchar(1000) NOT NULL,
  `message_type` varchar(50) NOT NULL,
  `total_mobileno_count` int DEFAULT NULL,
  `content_char_count` int NOT NULL,
  `content_message_count` int NOT NULL,
  `campaign_name` varchar(30) DEFAULT NULL,
  `whatsapp_status` char(1) NOT NULL,
  `whatsapp_entry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `compose_whatsapp_status_2`
--

CREATE TABLE `compose_whatsapp_status_2` (
  `comwtap_status_id` int NOT NULL,
  `compose_whatsapp_id` int NOT NULL,
  `country_code` int DEFAULT NULL,
  `mobile_no` varchar(13) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `comments` varchar(100) NOT NULL,
  `comwtap_status` char(1) NOT NULL,
  `comwtap_entry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `response_status` char(1) DEFAULT NULL,
  `response_message` varchar(100) DEFAULT NULL,
  `response_id` varchar(100) DEFAULT NULL,
  `response_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `delivery_status` char(1) DEFAULT NULL,
  `delivery_date` timestamp NULL DEFAULT NULL,
  `read_date` timestamp NULL DEFAULT NULL,
  `read_status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `compose_whatsapp_status_tmpl_2`
--

CREATE TABLE `compose_whatsapp_status_tmpl_2` (
  `comwtap_status_id` int NOT NULL,
  `compose_whatsapp_id` int NOT NULL,
  `country_code` int DEFAULT NULL,
  `mobile_no` varchar(13) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `comments` varchar(100) NOT NULL,
  `comwtap_status` char(1) NOT NULL,
  `comwtap_entry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `response_status` char(1) DEFAULT NULL,
  `response_message` varchar(100) DEFAULT NULL,
  `response_id` varchar(100) DEFAULT NULL,
  `response_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `delivery_status` char(1) DEFAULT NULL,
  `delivery_date` timestamp NULL DEFAULT NULL,
  `read_date` timestamp NULL DEFAULT NULL,
  `read_status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `compose_whatsapp_status_tmpl_2`
--

INSERT INTO `compose_whatsapp_status_tmpl_2` (`comwtap_status_id`, `compose_whatsapp_id`, `country_code`, `mobile_no`, `comments`, `comwtap_status`, `comwtap_entry_date`, `response_status`, `response_message`, `response_id`, `response_date`, `delivery_status`, `delivery_date`, `read_date`, `read_status`) VALUES
(1, 1, NULL, '919894876392', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5ODk0ODc2MzkyFQIAERgSQkU1MDc4RkVGNDBGMTRCMThEAA==', '2024-03-30 12:22:05', 'Y', '2024-03-30 12:46:34', '2024-03-30 12:46:48', 'Y'),
(2, 2, NULL, '919840170487', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5ODQwMTcwNDg3FQIAERgSQTkwOUVERkQ3RjlGNDYyNUZFAA==', '2024-03-30 12:22:05', 'Y', '2024-03-30 12:23:03', '2024-03-30 12:50:32', 'Y'),
(3, 3, NULL, '919052341985', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5MDUyMzQxOTg1FQIAERgSMTEzRDM5NkYyRTYxNDVEQzkxAA==', '2024-03-30 12:22:06', 'Y', '2024-03-30 12:23:12', '2024-03-30 12:52:45', 'Y'),
(4, 4, NULL, '919487628129', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5NDg3NjI4MTI5FQIAERgSRDczRjdFRjAzOTAzMUIyQ0E0AA==', '2024-03-30 12:22:06', 'Y', '2024-03-30 12:23:06', '2024-03-30 12:48:36', 'Y'),
(5, 5, NULL, '919940755577', '916381778162', 'Y', '2024-03-30 12:22:04', 'F', 'Failed', 'wamid.HBgMOTE5OTQwNzU1NTc3FQIAERgSMEZEQzc5NTU2MzQ0MzRBRkRCAA==', '2024-03-30 12:22:07', NULL, NULL, NULL, NULL),
(6, 6, NULL, '919944247800', '916381778162', 'Y', '2024-03-30 12:22:04', 'F', 'Failed', 'wamid.HBgMOTE5OTQ0MjQ3ODAwFQIAERgSQjVCMkM3MUFBQzU5NEM0NDFCAA==', '2024-03-30 12:22:07', NULL, NULL, NULL, NULL),
(7, 7, NULL, '918341393493', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE4MzQxMzkzNDkzFQIAERgSMjk0NTg2OUMxOTRDMDZCOTk1AA==', '2024-03-30 12:22:08', 'Y', '2024-03-30 12:23:07', '2024-03-30 12:36:50', 'Y'),
(8, 8, NULL, '917981460969', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE3OTgxNDYwOTY5FQIAERgSOEE3OEJCRjg5NjBFRUMxMDY2AA==', '2024-03-30 12:22:08', 'Y', '2024-03-30 12:23:09', '2024-03-30 12:37:28', 'Y'),
(9, 9, NULL, '919686193535', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5Njg2MTkzNTM1FQIAERgSNEZFNzRDQUZERUY3RjhFMzRGAA==', '2024-03-30 12:22:09', 'Y', '2024-03-30 12:23:03', '2024-03-30 12:23:47', 'Y'),
(10, 10, NULL, '919000012231', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5MDAwMDEyMjMxFQIAERgSNzg0NTRCQUYwRERFNUMxNkMxAA==', '2024-03-30 12:22:09', 'Y', '2024-03-30 12:23:14', NULL, NULL),
(11, 11, NULL, '919885339919', '916381778162', 'Y', '2024-03-30 12:22:04', 'F', 'Failed', 'wamid.HBgMOTE5ODg1MzM5OTE5FQIAERgSNjRCQTNFMzg4MjY4NDREN0VGAA==', '2024-03-30 12:22:10', NULL, NULL, NULL, NULL),
(12, 12, NULL, '919445603329', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5NDQ1NjAzMzI5FQIAERgSODQ5OThBRkNGQTcxNUMwRjlDAA==', '2024-03-30 12:22:10', 'Y', '2024-03-30 12:23:10', '2024-03-30 13:00:38', 'Y'),
(13, 13, NULL, '919894606748', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5ODk0NjA2NzQ4FQIAERgSMUQxNzc5QkE0MDA1Q0NBMDE4AA==', '2024-03-30 12:22:11', 'Y', '2024-03-30 12:23:11', '2024-03-30 12:23:24', 'Y'),
(14, 14, NULL, '916380885546', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE2MzgwODg1NTQ2FQIAERgSQzQyOUZEOTU1MDRDMkIxREEyAA==', '2024-03-30 12:22:11', 'Y', '2024-03-30 12:23:13', '2024-03-30 12:23:34', 'Y'),
(15, 15, NULL, '916380885546', '916381778162', 'Y', '2024-03-31 06:14:31', 'S', 'SUCCESS', 'wamid.HBgMOTE2MzgwODg1NTQ2FQIAERgSQzg5RkFCOTg0NzM0MjYzNkRCAA==', '2024-03-31 06:14:31', 'Y', '2024-03-31 06:15:31', '2024-03-31 06:15:41', 'Y'),
(16, 16, NULL, '916380885546', '916381778162', 'Y', '2024-03-31 06:14:31', 'S', 'SUCCESS', 'wamid.HBgMOTE2MzgwODg1NTQ2FQIAERgSQzg5RkFCOTg0NzM0MjYzNkRCAA==', '2024-03-31 06:14:31', 'Y', '2024-03-31 06:15:31', '2024-03-31 06:15:41', 'Y'),
(17, 17, NULL, '916380885546', '916381778162', 'Y', '2024-03-31 06:14:31', 'S', 'SUCCESS', 'wamid.HBgMOTE2MzgwODg1NTQ2FQIAERgSQzg5RkFCOTg0NzM0MjYzNkRCAA==', '2024-03-31 06:14:31', 'Y', '2024-03-31 06:15:31', '2024-03-31 06:15:41', 'Y'),
(18, 18, NULL, '916380885546', '916381778162', 'Y', '2024-03-31 06:14:31', 'S', 'SUCCESS', 'wamid.HBgMOTE2MzgwODg1NTQ2FQIAERgSQzg5RkFCOTg0NzM0MjYzNkRCAA==', '2024-03-31 06:14:31', 'Y', '2024-03-31 06:15:31', '2024-03-31 06:15:41', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `compose_whatsapp_tmpl_2`
--

CREATE TABLE `compose_whatsapp_tmpl_2` (
  `compose_whatsapp_id` int NOT NULL,
  `user_id` int NOT NULL,
  `store_id` int NOT NULL,
  `whatspp_config_id` int NOT NULL,
  `mobile_nos` longblob NOT NULL,
  `sender_mobile_nos` longblob NOT NULL,
  `whatsapp_content` varchar(1000) NOT NULL,
  `message_type` varchar(50) NOT NULL,
  `total_mobileno_count` int DEFAULT NULL,
  `content_char_count` int NOT NULL,
  `content_message_count` int NOT NULL,
  `campaign_name` varchar(30) DEFAULT NULL,
  `whatsapp_status` char(1) NOT NULL,
  `whatsapp_entry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `compose_whatsapp_tmpl_2`
--

INSERT INTO `compose_whatsapp_tmpl_2` (`compose_whatsapp_id`, `user_id`, `store_id`, `whatspp_config_id`, `mobile_nos`, `sender_mobile_nos`, `whatsapp_content`, `message_type`, `total_mobileno_count`, `content_char_count`, `content_message_count`, `campaign_name`, `whatsapp_status`, `whatsapp_entry_date`) VALUES
(1, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323635383437352e637376, 0x393139363332353837343139, 'te_apdcl1_l00v00000_24511_034', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_1', 'Y', '2024-05-07 07:30:58'),
(2, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323731323639322e637376, 0x393139363332353837343139, 'te_apdcl1_l00v00000_24511_035', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_2', 'Y', '2024-05-08 07:31:52'),
(3, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323733393639362e637376, 0x393139363332353837343139, 'te_apdcl1_l0i000000_24511_036', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_3', 'Y', '2024-05-09 07:32:20'),
(4, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323736333435362e637376, 0x393139363332353837343139, 'te_apdcl1_l00v00000_24511_037', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_4', 'Y', '2024-05-09 07:32:43'),
(5, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323738323631302e637376, 0x393139363332353837343139, 'te_apdcl1_l00v00000_24511_038', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_5', 'Y', '2024-05-09 07:33:02'),
(6, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323830303030382e637376, 0x393139363332353837343139, 'te_apdcl1_l00000000_24511_039', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_6', 'Y', '2024-05-09 07:33:20'),
(7, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323831363637372e637376, 0x393139363332353837343139, 'te_apdcl1_l00000000_24511_040', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_7', 'Y', '2024-05-09 07:33:36'),
(8, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323834313035372e637376, 0x393139363332353837343139, 'te_apdcl1_l0i000000_24511_041', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_8', 'Y', '2024-05-10 07:34:01'),
(9, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323835353530302e637376, 0x393139363332353837343139, 'te_apdcl1_l00000000_24511_042', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_9', 'Y', '2024-05-10 07:34:15'),
(10, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323837393333372e637376, 0x393139363332353837343139, 'te_apdcl1_l00v00000_24511_043', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_10', 'Y', '2024-05-10 07:34:39'),
(11, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353432303439343632302e637376, 0x393139363332353837343139, 'te_apdcl1_l0i000000_24511_044', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_11', 'Y', '2024-05-10 09:41:34'),
(12, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343236343634352e637376, 0x393139363332353837343139, 'te_apdcl1_l00000000_24511_046', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_12', 'Y', '2024-05-11 16:17:44'),
(13, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343333333931372e637376, 0x393139363332353837343139, 'te_apdcl1_l00000000_24511_047', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_13', 'Y', '2024-05-11 16:18:54'),
(14, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343335323833312e637376, 0x393139363332353837343139, 'te_apdcl1_l00v00000_24511_048', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_14', 'Y', '2024-05-11 16:19:13'),
(15, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343337303534352e637376, 0x393139363332353837343139, 'te_apdcl1_l00v00000_24511_049', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_15', 'Y', '2024-05-11 16:19:30'),
(16, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343338373432302e637376, 0x393139363332353837343139, 'te_apdcl1_l00v00000_24511_050', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_16', 'Y', '2024-05-11 16:19:47'),
(17, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343430353432302e637376, 0x393139363332353837343139, 'te_apdcl1_l00v00000_24511_051', 'TEXT', 1, 1, 1, 'ca_apdcl1_132_17', 'Y', '2024-05-11 16:20:05'),
(18, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353439343035343436332e637376, 0x393139363332353837343139, 'te_apdcl1_l0i000000_24512_052', 'TEXT', 1, 1, 1, 'ca_apdcl1_133_18', 'Y', '2024-05-11 06:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `compose_whatsapp_tmpl_2_tdp`
--

CREATE TABLE `compose_whatsapp_tmpl_2_tdp` (
  `compose_whatsapp_id` int NOT NULL,
  `user_id` int NOT NULL,
  `store_id` int NOT NULL,
  `whatspp_config_id` int NOT NULL,
  `mobile_nos` longblob NOT NULL,
  `sender_mobile_nos` longblob NOT NULL,
  `whatsapp_content` varchar(1000) NOT NULL,
  `message_type` varchar(50) NOT NULL,
  `total_mobileno_count` int DEFAULT NULL,
  `content_char_count` int NOT NULL,
  `content_message_count` int NOT NULL,
  `campaign_name` varchar(30) DEFAULT NULL,
  `whatsapp_status` char(1) NOT NULL,
  `whatsapp_entry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `compose_whatsapp_tmpl_2_tdp`
--

INSERT INTO `compose_whatsapp_tmpl_2_tdp` (`compose_whatsapp_id`, `user_id`, `store_id`, `whatspp_config_id`, `mobile_nos`, `sender_mobile_nos`, `whatsapp_content`, `message_type`, `total_mobileno_count`, `content_char_count`, `content_message_count`, `campaign_name`, `whatsapp_status`, `whatsapp_entry_date`) VALUES
(1, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323635383437352e637376, 0x393139363332353837343139, 'te_tdp1_l00v00000_24511_034', 'TEXT', 1, 1, 1, 'ca_tdp1_132_1', 'Y', '2024-05-07 07:30:58'),
(2, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323731323639322e637376, 0x393139363332353837343139, 'te_tdp1_l00v00000_24511_035', 'TEXT', 1, 1, 1, 'ca_tdp1_132_2', 'Y', '2024-05-08 07:31:52'),
(3, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323733393639362e637376, 0x393139363332353837343139, 'te_tdp1_l0i000000_24511_036', 'TEXT', 1, 1, 1, 'ca_tdp1_132_3', 'Y', '2024-05-09 07:32:20'),
(4, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323736333435362e637376, 0x393139363332353837343139, 'te_tdp1_l00v00000_24511_037', 'TEXT', 1, 1, 1, 'ca_tdp1_132_4', 'Y', '2024-05-09 07:32:43'),
(5, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323738323631302e637376, 0x393139363332353837343139, 'te_tdp1_l00v00000_24511_038', 'TEXT', 1, 1, 1, 'ca_tdp1_132_5', 'Y', '2024-05-09 07:33:02'),
(6, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323830303030382e637376, 0x393139363332353837343139, 'te_tdp1_l00000000_24511_039', 'TEXT', 1, 1, 1, 'ca_tdp1_132_6', 'Y', '2024-05-09 07:33:20'),
(7, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323831363637372e637376, 0x393139363332353837343139, 'te_tdp1_l00000000_24511_040', 'TEXT', 1, 1, 1, 'ca_tdp1_132_7', 'Y', '2024-05-09 07:33:36'),
(8, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323834313035372e637376, 0x393139363332353837343139, 'te_tdp1_l0i000000_24511_041', 'TEXT', 1, 1, 1, 'ca_tdp1_132_8', 'Y', '2024-05-10 07:34:01'),
(9, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323835353530302e637376, 0x393139363332353837343139, 'te_tdp1_l00000000_24511_042', 'TEXT', 1, 1, 1, 'ca_tdp1_132_9', 'Y', '2024-05-10 07:34:15'),
(10, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353431323837393333372e637376, 0x393139363332353837343139, 'te_tdp1_l00v00000_24511_043', 'TEXT', 1, 1, 1, 'ca_tdp1_132_10', 'Y', '2024-05-10 07:34:39'),
(11, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353432303439343632302e637376, 0x393139363332353837343139, 'te_tdp1_l0i000000_24511_044', 'TEXT', 1, 1, 1, 'ca_tdp1_132_11', 'Y', '2024-05-10 09:41:34'),
(12, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343236343634352e637376, 0x393139363332353837343139, 'te_tdp1_l00000000_24511_046', 'TEXT', 1, 1, 1, 'ca_tdp1_132_12', 'Y', '2024-05-11 16:17:44'),
(13, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343333333931372e637376, 0x393139363332353837343139, 'te_tdp1_l00000000_24511_047', 'TEXT', 1, 1, 1, 'ca_tdp1_132_13', 'Y', '2024-05-11 16:18:54'),
(14, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343335323833312e637376, 0x393139363332353837343139, 'te_tdp1_l00v00000_24511_048', 'TEXT', 1, 1, 1, 'ca_tdp1_132_14', 'Y', '2024-05-11 16:19:13'),
(15, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343337303534352e637376, 0x393139363332353837343139, 'te_tdp1_l00v00000_24511_049', 'TEXT', 1, 1, 1, 'ca_tdp1_132_15', 'Y', '2024-05-11 16:19:30'),
(16, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343338373432302e637376, 0x393139363332353837343139, 'te_tdp1_l00v00000_24511_050', 'TEXT', 1, 1, 1, 'ca_tdp1_132_16', 'Y', '2024-05-11 16:19:47'),
(17, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353434343430353432302e637376, 0x393139363332353837343139, 'te_tdp1_l00v00000_24511_051', 'TEXT', 1, 1, 1, 'ca_tdp1_132_17', 'Y', '2024-05-11 16:20:05'),
(18, 2, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f325f6373765f313731353439343035343436332e637376, 0x393139363332353837343139, 'te_tdp1_l0i000000_24512_052', 'TEXT', 1, 1, 1, 'ca_tdp1_133_18', 'Y', '2024-05-11 06:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_text_2`
--

CREATE TABLE `whatsapp_text_2` (
  `whatsapp_text_id` int NOT NULL,
  `compose_whatsapp_id` int NOT NULL,
  `sms_type` varchar(50) NOT NULL,
  `whatsapp_text_title` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `text_data` varchar(2000) DEFAULT NULL,
  `text_reply` varchar(50) DEFAULT NULL,
  `text_number` varchar(13) DEFAULT NULL,
  `text_address` varchar(100) DEFAULT NULL,
  `text_name` varchar(30) DEFAULT NULL,
  `text_url` varchar(100) DEFAULT NULL,
  `text_title` varchar(200) DEFAULT NULL,
  `text_description` varchar(200) DEFAULT NULL,
  `text_start_time` timestamp NULL DEFAULT NULL,
  `text_end_time` timestamp NULL DEFAULT NULL,
  `carousel_fileurl` varchar(100) DEFAULT NULL,
  `carousel_srno` int DEFAULT NULL,
  `whatsapp_text_status` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `whatsapp_text_entry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_text_tmpl_2`
--

CREATE TABLE `whatsapp_text_tmpl_2` (
  `compose_whatsapp_msgid` int NOT NULL,
  `compose_whatsapp_id` int NOT NULL,
  `whatspp_template` varchar(600) NOT NULL,
  `whatsapp_tmpl_category` varchar(50) NOT NULL,
  `whatsapp_tmpl_name` varchar(500) NOT NULL,
  `whatsapp_tmpl_language` varchar(20) NOT NULL,
  `whatsapp_tmpl_hdtext` varchar(60) DEFAULT NULL,
  `whatsapp_tmpl_body` varchar(2000) NOT NULL,
  `whatsapp_tmpl_footer` varchar(60) DEFAULT NULL,
  `whatsapp_tmpl_status` char(1) NOT NULL,
  `whatsapp_tmpl_entrydate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `whatsapp_text_tmpl_2`
--

INSERT INTO `whatsapp_text_tmpl_2` (`compose_whatsapp_msgid`, `compose_whatsapp_id`, `whatspp_template`, `whatsapp_tmpl_category`, `whatsapp_tmpl_name`, `whatsapp_tmpl_language`, `whatsapp_tmpl_hdtext`, `whatsapp_tmpl_body`, `whatsapp_tmpl_footer`, `whatsapp_tmpl_status`, `whatsapp_tmpl_entrydate`) VALUES
(1, 1, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '\"\"', '', 'Y', '2023-03-01 14:52:01'),
(2, 2, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '\"\"', '', 'Y', '2023-03-02 14:49:11'),
(3, 3, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '\"\"', '', 'Y', '2023-03-02 14:48:39'),
(4, 4, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '\"\"', '', 'Y', '2023-03-02 15:34:23'),
(5, 5, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '\"\"', '', 'Y', '2023-03-02 15:40:57'),
(6, 6, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '\"\"', '', 'Y', '2023-03-02 16:03:30'),
(7, 7, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '\"\"', '', 'Y', '2023-03-02 16:59:23'),
(8, 8, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '\"\"', '', 'Y', '2023-03-03 10:46:30'),
(9, 9, 'temp!en_US', 'MARKETING', 'temp_1', 'en_US', '', '\"\"', '', 'Y', '2023-03-04 16:48:04'),
(10, 10, 'admin_1_footer_call_to_url!en_US', '', '', '', '', '', '', 'Y', '2023-03-05 17:48:16'),
(11, 11, 'admin_1_header_image!en_US', '', '', '', '', '', '', 'Y', '2023-03-05 17:51:12'),
(12, 12, 'admin_1_footer_call_to_url!en_US', '', '', '', '', '', '', 'Y', '2023-03-05 17:51:55'),
(13, 13, 'admin_1_footer_quick_reply!en_US', '', '', '', '', '', '', 'Y', '2023-03-05 18:01:28'),
(14, 16, 'admin_1_footer_call_to_url!en_US', 'MARKETING', 'admin_1_footer_call_to_url', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 18:39:52'),
(15, 17, 'admin_1_footer_call_to_url!en_US', 'MARKETING', 'admin_1_footer_call_to_url', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 18:43:09'),
(16, 18, 'admin_1_footer_quick_reply!en_US', 'MARKETING', 'admin_1_footer_quick_reply', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 18:44:16'),
(17, 19, 'admin_1_footer_call_to_url!en_US', 'MARKETING', 'admin_1_footer_call_to_url', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 18:45:07'),
(18, 20, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678022301458_20_1677494866393.png\"\n																														} }\n																			]\n																	}', '', 'Y', '2023-03-05 18:48:21'),
(19, 21, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678022869117_20_128712578123.png\"\n																														} }\n																			]\n																	}\n																]', '', 'Y', '2023-03-05 18:57:49'),
(20, 22, 'admin_1_footer_call_to_action!en_US', 'MARKETING', 'admin_1_footer_call_to_action', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 18:59:27'),
(21, 23, 'admin_1_footer_quick_reply!en_US', 'MARKETING', 'admin_1_footer_quick_reply', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 18:59:59'),
(22, 24, 'admin_1_footer_text!en_US', 'MARKETING', 'admin_1_footer_text', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:01:46'),
(23, 25, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"\n																]', '', 'Y', '2023-03-05 19:02:06'),
(24, 26, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"\n																	]', '', 'Y', '2023-03-05 19:21:00'),
(25, 27, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"\n																	]', '', 'Y', '2023-03-05 19:22:35'),
(26, 28, 'admin_1_footer_call_to_action!en_US', 'MARKETING', 'admin_1_footer_call_to_action', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:23:50'),
(27, 29, 'admin_1_footer_call_to_url!en_US', 'MARKETING', 'admin_1_footer_call_to_url', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:24:22'),
(28, 30, 'admin_1_simple_body!en_US', 'MARKETING', 'admin_1_simple_body', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:24:46'),
(29, 31, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"\n																	]', '', 'Y', '2023-03-05 19:26:02'),
(30, 32, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"\n												', '', 'Y', '2023-03-05 19:30:56'),
(31, 33, 'admin_1_footer_call_to_action!en_US', 'MARKETING', 'admin_1_footer_call_to_action', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:31:41'),
(32, 34, 'admin_1_footer_call_to_url!en_US', 'MARKETING', 'admin_1_footer_call_to_url', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:31:56'),
(33, 35, 'admin_1_footer_quick_reply!en_US', 'MARKETING', 'admin_1_footer_quick_reply', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:32:06'),
(34, 36, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"\n												', '', 'Y', '2023-03-05 19:32:28'),
(35, 37, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678024982212_20_128712578123.png\"\n																														} }\n																			]\n																	}\n												', '', 'Y', '2023-03-05 19:33:02'),
(36, 38, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678025191422_20_128712578123.png\"\n																														} }\n																			]\n																	}\n												', '', 'Y', '2023-03-05 19:36:31'),
(37, 39, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678025302813_20_1677494866393.png\"\n																														} }\n																			]\n																	}\n												]', '', 'Y', '2023-03-05 19:38:22'),
(38, 40, 'admin_1_footer_call_to_action!en_US', 'MARKETING', 'admin_1_footer_call_to_action', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:38:39'),
(39, 41, 'admin_1_footer_call_to_url!en_US', 'MARKETING', 'admin_1_footer_call_to_url', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:38:51'),
(40, 42, 'admin_1_footer_quick_reply!en_US', 'MARKETING', 'admin_1_footer_quick_reply', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:39:04'),
(41, 43, 'admin_1_footer_text!en_US', 'MARKETING', 'admin_1_footer_text', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:39:18'),
(42, 44, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"\n												]', '', 'Y', '2023-03-05 19:39:28'),
(43, 45, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678025440442_20_1677494866393.png\"\n																														} }\n																			]\n																	}\n												]', '', 'Y', '2023-03-05 19:40:40'),
(44, 46, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"\n												]', '', 'Y', '2023-03-05 19:40:49'),
(45, 47, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"\n												]', '', 'Y', '2023-03-05 19:42:58'),
(46, 48, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"]', '', 'Y', '2023-03-05 19:43:52'),
(47, 49, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678025714363_20_1677494866393.png\"\n																														} }\n																			]\n																	}]', '', 'Y', '2023-03-05 19:45:14'),
(48, 50, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678025741550_20_1677494866393.png\"\n																														} }\n																			]\n																	}]', '', 'Y', '2023-03-05 19:45:41'),
(49, 51, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"]', '', 'Y', '2023-03-05 19:45:56'),
(50, 52, 'admin_1_header_text!en_US', 'MARKETING', 'admin_1_header_text', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:47:26'),
(51, 53, 'admin_1_simple_body!en_US', 'MARKETING', 'admin_1_simple_body', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:47:40'),
(52, 54, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678025880167_kairos.png\"\n																														} }\n																			]\n																	}]', '', 'Y', '2023-03-05 19:48:00'),
(53, 55, 'admin_1_header_image!en_US', 'MARKETING', 'admin_1_header_image', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678025881116_kairos.png\"\n																														} }\n																			]\n																	}]', '', 'Y', '2023-03-05 19:48:01'),
(54, 56, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '\"\"', '', 'Y', '2023-03-05 19:49:16'),
(55, 57, 'admin_1_footer_call_to_action!en_US', 'MARKETING', 'admin_1_footer_call_to_action', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 19:49:27'),
(56, 58, 'slicemarketing!en_US', 'MARKETING', 'slicemarketing', 'en_US', '', '\"\"', '', 'Y', '2023-03-05 20:44:45'),
(57, 59, 'slicemarketing!en_US', 'MARKETING', 'slicemarketing', 'en_US', '', '[]', '', 'Y', '2023-03-08 09:17:03'),
(58, 60, 'billing_imagemedia!en_US', 'MARKETING', 'billing_imagemedia', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1678253177633_celebmedia_logo.png\"\n																														} }\n																			]\n																	}]', 'Thanks', 'Y', '2023-03-08 10:56:17'),
(59, 61, 'billing_variable!en_US!3', 'MARKETING', 'billing_variable', 'en_US', '', '[]', '', 'Y', '2023-03-09 10:08:51'),
(60, 62, 'billing_variable!en_US!3', 'MARKETING', 'billing_variable', 'en_US', '', '[]', '', 'Y', '2023-03-09 10:12:11'),
(61, 63, 'billing_variable!en_US!3', 'MARKETING', 'billing_variable', 'en_US', '', '[]', '', 'Y', '2023-03-09 10:36:01'),
(62, 64, 'demo1!en_GB!0', 'MARKETING', 'demo1', 'en_GB', '', '[]', '', 'Y', '2023-03-09 11:07:11'),
(63, 65, 'bible_quote_john_11_25_26!en_US!0', 'MARKETING', 'bible_quote_john_11_25_26', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1679903305053_english.png\"\n																														} }\n																			]\n																	}]', 'Thanks', 'Y', '2023-03-27 13:18:25'),
(64, 66, 'bible_quote_john_11_25_26!en_US!0', 'MARKETING', 'bible_quote_john_11_25_26', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/images/mobile.png\"\n																														} }\n																			]\n																	}]', 'Thanks', 'Y', '2023-03-27 13:25:08'),
(65, 67, 'bible_quote_john_11_25_26!en_US!0', 'MARKETING', 'bible_quote_john_11_25_26', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/2_1679913781286_\"\n																														} }\n																			]\n																	}]', 'Thanks', 'Y', '2023-03-27 16:13:01'),
(66, 68, 'malayalam_tmplt1!en_GB!0', 'MARKETING', 'malayalam_tmplt1', 'en_GB', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"video\",\"video\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_videos/2_1680684703226_vidoe.mp4\"\n																														} }\n																			]\n																	}]', '', 'Y', '2023-04-05 14:21:43'),
(67, 69, 'mirchiswayamvara!ml!0', 'MARKETING', 'mirchiswayamvara', 'ml', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"video\",\"video\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_videos/2_1680684958687_vidoe.mp4\"\n																														} }\n																			]\n																	}]', '', 'Y', '2023-04-05 14:25:58'),
(68, 70, 'mirchiswayamvara!ml!0', 'MARKETING', 'mirchiswayamvara', 'ml', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"video\",\"video\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_videos/2_1680685027787_vidoe.mp4\"\n																														} }\n																			]\n																	}]', '', 'Y', '2023-04-05 14:27:07'),
(69, 71, 'mirchiswayamvara!ml!0', 'MARKETING', 'mirchiswayamvara', 'ml', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"video\",\"video\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_videos/2_1680685057340_vidoe.mp4\"\n																														} }\n																			]\n																	}]', '', 'Y', '2023-04-05 14:27:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `compose_whatsapp_2`
--
ALTER TABLE `compose_whatsapp_2`
  ADD PRIMARY KEY (`compose_whatsapp_id`),
  ADD KEY `user_id` (`user_id`,`store_id`,`whatspp_config_id`);

--
-- Indexes for table `compose_whatsapp_status_2`
--
ALTER TABLE `compose_whatsapp_status_2`
  ADD PRIMARY KEY (`comwtap_status_id`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- Indexes for table `compose_whatsapp_status_tmpl_2`
--
ALTER TABLE `compose_whatsapp_status_tmpl_2`
  ADD PRIMARY KEY (`comwtap_status_id`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- Indexes for table `compose_whatsapp_tmpl_2`
--
ALTER TABLE `compose_whatsapp_tmpl_2`
  ADD PRIMARY KEY (`compose_whatsapp_id`),
  ADD KEY `user_id` (`user_id`,`store_id`,`whatspp_config_id`);

--
-- Indexes for table `compose_whatsapp_tmpl_2_tdp`
--
ALTER TABLE `compose_whatsapp_tmpl_2_tdp`
  ADD PRIMARY KEY (`compose_whatsapp_id`),
  ADD KEY `user_id` (`user_id`,`store_id`,`whatspp_config_id`);

--
-- Indexes for table `whatsapp_text_2`
--
ALTER TABLE `whatsapp_text_2`
  ADD PRIMARY KEY (`whatsapp_text_id`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- Indexes for table `whatsapp_text_tmpl_2`
--
ALTER TABLE `whatsapp_text_tmpl_2`
  ADD PRIMARY KEY (`compose_whatsapp_msgid`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `compose_whatsapp_2`
--
ALTER TABLE `compose_whatsapp_2`
  MODIFY `compose_whatsapp_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compose_whatsapp_status_2`
--
ALTER TABLE `compose_whatsapp_status_2`
  MODIFY `comwtap_status_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compose_whatsapp_status_tmpl_2`
--
ALTER TABLE `compose_whatsapp_status_tmpl_2`
  MODIFY `comwtap_status_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `compose_whatsapp_tmpl_2`
--
ALTER TABLE `compose_whatsapp_tmpl_2`
  MODIFY `compose_whatsapp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `compose_whatsapp_tmpl_2_tdp`
--
ALTER TABLE `compose_whatsapp_tmpl_2_tdp`
  MODIFY `compose_whatsapp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `whatsapp_text_2`
--
ALTER TABLE `whatsapp_text_2`
  MODIFY `whatsapp_text_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whatsapp_text_tmpl_2`
--
ALTER TABLE `whatsapp_text_tmpl_2`
  MODIFY `compose_whatsapp_msgid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
