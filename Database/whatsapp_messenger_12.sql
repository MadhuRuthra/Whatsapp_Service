-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 11, 2024 at 05:00 AM
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
-- Database: `whatsapp_messenger_12`
--

-- --------------------------------------------------------

--
-- Table structure for table `compose_whatsapp_12`
--

CREATE TABLE `compose_whatsapp_12` (
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
-- Table structure for table `compose_whatsapp_status_12`
--

CREATE TABLE `compose_whatsapp_status_12` (
  `comwtap_status_id` int NOT NULL,
  `compose_whatsapp_id` int NOT NULL,
  `country_code` int DEFAULT NULL,
  `mobile_no` varchar(13) NOT NULL,
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
-- Table structure for table `compose_whatsapp_status_tmpl_12`
--

CREATE TABLE `compose_whatsapp_status_tmpl_12` (
  `comwtap_status_id` int NOT NULL,
  `compose_whatsapp_id` int NOT NULL,
  `country_code` int DEFAULT NULL,
  `mobile_no` varchar(13) NOT NULL,
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
-- Dumping data for table `compose_whatsapp_status_tmpl_12`
--

INSERT INTO `compose_whatsapp_status_tmpl_12` (`comwtap_status_id`, `compose_whatsapp_id`, `country_code`, `mobile_no`, `comments`, `comwtap_status`, `comwtap_entry_date`, `response_status`, `response_message`, `response_id`, `response_date`, `delivery_status`, `delivery_date`, `read_date`, `read_status`) VALUES
(1, 1, NULL, '919894876392', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5ODk0ODc2MzkyFQIAERgSQkU1MDc4RkVGNDBGMTRCMThEAA==', '2024-03-30 12:22:05', 'Y', '2024-03-30 12:46:34', '2024-03-30 12:46:48', 'Y'),
(2, 1, NULL, '919840170487', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5ODQwMTcwNDg3FQIAERgSQTkwOUVERkQ3RjlGNDYyNUZFAA==', '2024-03-30 12:22:05', 'Y', '2024-03-30 12:23:03', '2024-03-30 12:50:32', 'Y'),
(3, 1, NULL, '919052341985', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5MDUyMzQxOTg1FQIAERgSMTEzRDM5NkYyRTYxNDVEQzkxAA==', '2024-03-30 12:22:06', 'Y', '2024-03-30 12:23:12', '2024-03-30 12:52:45', 'Y'),
(4, 1, NULL, '919487628129', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5NDg3NjI4MTI5FQIAERgSRDczRjdFRjAzOTAzMUIyQ0E0AA==', '2024-03-30 12:22:06', 'Y', '2024-03-30 12:23:06', '2024-03-30 12:48:36', 'Y'),
(5, 1, NULL, '919940755577', '916381778162', 'Y', '2024-03-30 12:22:04', 'F', 'Failed', 'wamid.HBgMOTE5OTQwNzU1NTc3FQIAERgSMEZEQzc5NTU2MzQ0MzRBRkRCAA==', '2024-03-30 12:22:07', NULL, NULL, NULL, NULL),
(6, 1, NULL, '919944247800', '916381778162', 'Y', '2024-03-30 12:22:04', 'F', 'Failed', 'wamid.HBgMOTE5OTQ0MjQ3ODAwFQIAERgSQjVCMkM3MUFBQzU5NEM0NDFCAA==', '2024-03-30 12:22:07', NULL, NULL, NULL, NULL),
(7, 1, NULL, '918341393493', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE4MzQxMzkzNDkzFQIAERgSMjk0NTg2OUMxOTRDMDZCOTk1AA==', '2024-03-30 12:22:08', 'Y', '2024-03-30 12:23:07', '2024-03-30 12:36:50', 'Y'),
(8, 1, NULL, '917981460969', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE3OTgxNDYwOTY5FQIAERgSOEE3OEJCRjg5NjBFRUMxMDY2AA==', '2024-03-30 12:22:08', 'Y', '2024-03-30 12:23:09', '2024-03-30 12:37:28', 'Y'),
(9, 1, NULL, '919686193535', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5Njg2MTkzNTM1FQIAERgSNEZFNzRDQUZERUY3RjhFMzRGAA==', '2024-03-30 12:22:09', 'Y', '2024-03-30 12:23:03', '2024-03-30 12:23:47', 'Y'),
(10, 1, NULL, '919000012231', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5MDAwMDEyMjMxFQIAERgSNzg0NTRCQUYwRERFNUMxNkMxAA==', '2024-03-30 12:22:09', 'Y', '2024-03-30 12:23:14', NULL, NULL),
(11, 1, NULL, '919885339919', '916381778162', 'Y', '2024-03-30 12:22:04', 'F', 'Failed', 'wamid.HBgMOTE5ODg1MzM5OTE5FQIAERgSNjRCQTNFMzg4MjY4NDREN0VGAA==', '2024-03-30 12:22:10', NULL, NULL, NULL, NULL),
(12, 1, NULL, '919445603329', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5NDQ1NjAzMzI5FQIAERgSODQ5OThBRkNGQTcxNUMwRjlDAA==', '2024-03-30 12:22:10', 'Y', '2024-03-30 12:23:10', '2024-03-30 13:00:38', 'Y'),
(13, 1, NULL, '919894606748', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE5ODk0NjA2NzQ4FQIAERgSMUQxNzc5QkE0MDA1Q0NBMDE4AA==', '2024-03-30 12:22:11', 'Y', '2024-03-30 12:23:11', '2024-03-30 12:23:24', 'Y'),
(14, 1, NULL, '916380885546', '916381778162', 'Y', '2024-03-30 12:22:04', 'S', 'SUCCESS', 'wamid.HBgMOTE2MzgwODg1NTQ2FQIAERgSQzQyOUZEOTU1MDRDMkIxREEyAA==', '2024-03-30 12:22:11', 'Y', '2024-03-30 12:23:13', '2024-03-30 12:23:34', 'Y'),
(15, 2, NULL, '916380885546', '916381778162', 'Y', '2024-03-31 06:14:31', 'S', 'SUCCESS', 'wamid.HBgMOTE2MzgwODg1NTQ2FQIAERgSQzg5RkFCOTg0NzM0MjYzNkRCAA==', '2024-03-31 06:14:31', 'Y', '2024-03-31 06:15:31', '2024-03-31 06:15:41', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `compose_whatsapp_tmpl_12`
--

CREATE TABLE `compose_whatsapp_tmpl_12` (
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
-- Dumping data for table `compose_whatsapp_tmpl_12`
--

INSERT INTO `compose_whatsapp_tmpl_12` (`compose_whatsapp_id`, `user_id`, `store_id`, `whatspp_config_id`, `mobile_nos`, `sender_mobile_nos`, `whatsapp_content`, `message_type`, `total_mobileno_count`, `content_char_count`, `content_message_count`, `campaign_name`, `whatsapp_status`, `whatsapp_entry_date`) VALUES
(1, 12, 1, 1, 0x3931393839343837363339322c3931393834303137303438372c3931393035323334313938352c3931393438373632383132392c3931393934303735353537372c3931393934343234373830302c3931383334313339333439332c3931373938313436303936392c3931393638363139333533352c3931393030303031323233312c3931393838353333393931392c3931393434353630333332392c3931393839343630363734382c393136333830383835353436, 0x393136333831373738313632, 'te_neu_t0i000000_24330_030', 'TEXT', 14, 1, 14, 'ca_neu_090_1', 'S', '2024-03-30 12:22:04'),
(2, 12, 1, 1, 0x393136333830383835353436, 0x393136333831373738313632, 'te_neu_t0i000000_24330_024', 'TEXT', 1, 1, 1, 'ca_neu_091_2', 'S', '2024-03-31 06:14:31');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_text_12`
--

CREATE TABLE `whatsapp_text_12` (
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
-- Table structure for table `whatsapp_text_tmpl_12`
--

CREATE TABLE `whatsapp_text_tmpl_12` (
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
-- Indexes for dumped tables
--

--
-- Indexes for table `compose_whatsapp_12`
--
ALTER TABLE `compose_whatsapp_12`
  ADD PRIMARY KEY (`compose_whatsapp_id`),
  ADD KEY `user_id` (`user_id`,`store_id`,`whatspp_config_id`);

--
-- Indexes for table `compose_whatsapp_status_12`
--
ALTER TABLE `compose_whatsapp_status_12`
  ADD PRIMARY KEY (`comwtap_status_id`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- Indexes for table `compose_whatsapp_status_tmpl_12`
--
ALTER TABLE `compose_whatsapp_status_tmpl_12`
  ADD PRIMARY KEY (`comwtap_status_id`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- Indexes for table `compose_whatsapp_tmpl_12`
--
ALTER TABLE `compose_whatsapp_tmpl_12`
  ADD PRIMARY KEY (`compose_whatsapp_id`),
  ADD KEY `user_id` (`user_id`,`store_id`,`whatspp_config_id`);

--
-- Indexes for table `whatsapp_text_12`
--
ALTER TABLE `whatsapp_text_12`
  ADD PRIMARY KEY (`whatsapp_text_id`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- Indexes for table `whatsapp_text_tmpl_12`
--
ALTER TABLE `whatsapp_text_tmpl_12`
  ADD PRIMARY KEY (`compose_whatsapp_msgid`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `compose_whatsapp_12`
--
ALTER TABLE `compose_whatsapp_12`
  MODIFY `compose_whatsapp_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compose_whatsapp_status_12`
--
ALTER TABLE `compose_whatsapp_status_12`
  MODIFY `comwtap_status_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compose_whatsapp_status_tmpl_12`
--
ALTER TABLE `compose_whatsapp_status_tmpl_12`
  MODIFY `comwtap_status_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `compose_whatsapp_tmpl_12`
--
ALTER TABLE `compose_whatsapp_tmpl_12`
  MODIFY `compose_whatsapp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `whatsapp_text_12`
--
ALTER TABLE `whatsapp_text_12`
  MODIFY `whatsapp_text_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whatsapp_text_tmpl_12`
--
ALTER TABLE `whatsapp_text_tmpl_12`
  MODIFY `compose_whatsapp_msgid` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
