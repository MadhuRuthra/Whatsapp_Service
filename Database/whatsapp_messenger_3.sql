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
-- Database: `whatsapp_messenger_3`
--

-- --------------------------------------------------------

--
-- Table structure for table `compose_whatsapp_3`
--

CREATE TABLE `compose_whatsapp_3` (
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
-- Table structure for table `compose_whatsapp_status_3`
--

CREATE TABLE `compose_whatsapp_status_3` (
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
-- Table structure for table `compose_whatsapp_status_tmpl_3`
--

CREATE TABLE `compose_whatsapp_status_tmpl_3` (
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
-- Table structure for table `compose_whatsapp_tmpl_3`
--

CREATE TABLE `compose_whatsapp_tmpl_3` (
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
-- Dumping data for table `compose_whatsapp_tmpl_3`
--

INSERT INTO `compose_whatsapp_tmpl_3` (`compose_whatsapp_id`, `user_id`, `store_id`, `whatspp_config_id`, `mobile_nos`, `sender_mobile_nos`, `whatsapp_content`, `message_type`, `total_mobileno_count`, `content_char_count`, `content_message_count`, `campaign_name`, `whatsapp_status`, `whatsapp_entry_date`) VALUES
(1, 3, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f335f6373765f313731353432383830313933392e637376, 0x39373139363332353837343139, 'te_tdp1_l0i000000_24511_044', 'TEXT', 1, 1, 1, 'ca_dh1_132_1', 'Y', '2024-05-11 12:00:02'),
(2, 3, 1, 1, 0x2e2e2f75706c6f6164732f636f6e74616374732f335f6373765f313731353432383836343338332e637376, 0x39373139363332353837343139, 'te_tdp1_l0i000000_24511_044', 'TEXT', 1, 1, 1, 'ca_dh1_132_2', 'Y', '2024-05-11 12:01:04');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_text_3`
--

CREATE TABLE `whatsapp_text_3` (
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
-- Table structure for table `whatsapp_text_tmpl_3`
--

CREATE TABLE `whatsapp_text_tmpl_3` (
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
-- Dumping data for table `whatsapp_text_tmpl_3`
--

INSERT INTO `whatsapp_text_tmpl_3` (`compose_whatsapp_msgid`, `compose_whatsapp_id`, `whatspp_template`, `whatsapp_tmpl_category`, `whatsapp_tmpl_name`, `whatsapp_tmpl_language`, `whatsapp_tmpl_hdtext`, `whatsapp_tmpl_body`, `whatsapp_tmpl_footer`, `whatsapp_tmpl_status`, `whatsapp_tmpl_entrydate`) VALUES
(1, 1, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '[]', '', 'Y', '2023-03-07 13:47:05'),
(2, 2, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '[]', '', 'Y', '2023-03-07 13:50:13'),
(3, 3, 'slice_msg!en_GB', 'MARKETING', 'slice_msg', 'en_GB', '', '[]', '', 'Y', '2023-03-07 17:20:42'),
(4, 4, 'billing_quikreply!en_US', 'MARKETING', 'billing_quikreply', 'en_US', '', '[]', '', 'Y', '2023-03-08 10:29:45'),
(5, 5, 'billing_imagemedia!en_US!0', 'MARKETING', 'billing_imagemedia', 'en_US', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"image\",\"image\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_images/3_1678795029353_\"\n																														} }\n																			]\n																	}]', 'Thanks', 'Y', '2023-03-14 17:27:09'),
(6, 6, 'billing_call!en_US!0', 'MARKETING', 'billing_call', 'en_US', '', '[]', '', 'Y', '2023-03-14 17:28:57'),
(7, 7, 'billing_call!en_US!0', 'MARKETING', 'billing_call', 'en_US', '', '[]', '', 'Y', '2023-03-14 17:39:24'),
(8, 8, 'slicemarketing!en_US!0', 'MARKETING', 'slicemarketing', 'en_US', '', '[]', '', 'Y', '2023-03-14 18:02:07'),
(9, 9, 'mirchiswayamvara!ml!0', 'MARKETING', 'mirchiswayamvara', 'ml', '', '[\n																	{\n																			\"type\": \"HEADER\",\n																			\"parameters\": [\n																					{\n																							\"type\": \"video\",\"video\": {\n																																\"link\": \"https://yjtec.in/watsp/uploads/whatsapp_videos/3_1679061874714_Swayamvara_Silks_17Mar23.mp4\"\n																														} }\n																			]\n																	}]', '', 'Y', '2023-03-17 19:34:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `compose_whatsapp_3`
--
ALTER TABLE `compose_whatsapp_3`
  ADD PRIMARY KEY (`compose_whatsapp_id`),
  ADD KEY `user_id` (`user_id`,`store_id`,`whatspp_config_id`);

--
-- Indexes for table `compose_whatsapp_status_3`
--
ALTER TABLE `compose_whatsapp_status_3`
  ADD PRIMARY KEY (`comwtap_status_id`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- Indexes for table `compose_whatsapp_status_tmpl_3`
--
ALTER TABLE `compose_whatsapp_status_tmpl_3`
  ADD PRIMARY KEY (`comwtap_status_id`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- Indexes for table `compose_whatsapp_tmpl_3`
--
ALTER TABLE `compose_whatsapp_tmpl_3`
  ADD PRIMARY KEY (`compose_whatsapp_id`),
  ADD KEY `user_id` (`user_id`,`store_id`,`whatspp_config_id`);

--
-- Indexes for table `whatsapp_text_3`
--
ALTER TABLE `whatsapp_text_3`
  ADD PRIMARY KEY (`whatsapp_text_id`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- Indexes for table `whatsapp_text_tmpl_3`
--
ALTER TABLE `whatsapp_text_tmpl_3`
  ADD PRIMARY KEY (`compose_whatsapp_msgid`),
  ADD KEY `compose_whatsapp_id` (`compose_whatsapp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `compose_whatsapp_3`
--
ALTER TABLE `compose_whatsapp_3`
  MODIFY `compose_whatsapp_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compose_whatsapp_status_3`
--
ALTER TABLE `compose_whatsapp_status_3`
  MODIFY `comwtap_status_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compose_whatsapp_status_tmpl_3`
--
ALTER TABLE `compose_whatsapp_status_tmpl_3`
  MODIFY `comwtap_status_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compose_whatsapp_tmpl_3`
--
ALTER TABLE `compose_whatsapp_tmpl_3`
  MODIFY `compose_whatsapp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `whatsapp_text_3`
--
ALTER TABLE `whatsapp_text_3`
  MODIFY `whatsapp_text_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whatsapp_text_tmpl_3`
--
ALTER TABLE `whatsapp_text_tmpl_3`
  MODIFY `compose_whatsapp_msgid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
