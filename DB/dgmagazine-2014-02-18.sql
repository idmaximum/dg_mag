-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Feb 18, 2014 at 05:32 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `dgmagazine`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `banner`
-- 

CREATE TABLE `banner` (
  `banner_id` int(10) NOT NULL auto_increment,
  `bannerCategoryID_FK` int(2) NOT NULL default '0',
  `banner_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `banner_url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `banner_pic` varchar(50) collate utf8_unicode_ci NOT NULL,
  `banner_swf` varchar(50) collate utf8_unicode_ci NOT NULL,
  `visited` int(10) NOT NULL,
  `publish` enum('0','1') collate utf8_unicode_ci NOT NULL default '0',
  `CreateDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `banner_order` int(10) NOT NULL default '0',
  PRIMARY KEY  (`banner_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

-- 
-- Dumping data for table `banner`
-- 

INSERT INTO `banner` VALUES (3, 0, '', 'dddddddddddd', '20140211163049RUkS.jpg', '', 0, '', '0000-00-00 00:00:00', 0);
INSERT INTO `banner` VALUES (4, 0, '', 'dddddddddddd', '20140211163205N4Ue.jpg', '', 0, '', '0000-00-00 00:00:00', 0);
INSERT INTO `banner` VALUES (5, 0, '', 'dddddddddddd', '20140211163226wiGU.jpg', '', 0, '', '0000-00-00 00:00:00', 0);
INSERT INTO `banner` VALUES (12, 2, 'คาซ่ายูเรก้า', 'http://www.idmaximum.com/', '20140211165227DDtp.jpg', '2013_12_17_14_49_32.swf', 0, '', '2014-02-11 16:52:27', 0);
INSERT INTO `banner` VALUES (11, 2, 'คาซ่ายูเรก้า', 'http://www.idmaximum.com/', '2014021116520445Us.jpg', '2013_11_28_13_56_25.swf', 0, '', '2014-02-11 16:52:04', 0);
INSERT INTO `banner` VALUES (13, 3, 'คาซ่ายูเรก้า', 'http://www.idmaximum.com/', '201402111652456SW2.jpg', '', 0, '', '2014-02-11 16:52:45', 0);
INSERT INTO `banner` VALUES (14, 4, 'เพลส', 'http://www.idmaximum.com/', '20140211172334qubx.jpg', '2013_12_17_14_49_32(1).swf', 0, '', '2014-02-11 16:58:12', 0);
INSERT INTO `banner` VALUES (15, 4, 'ทดสอบๆ', 'http://www.idmaximum.com/', '20140211165827fxcn.jpg', '', 0, '', '2014-02-11 16:58:27', 0);
INSERT INTO `banner` VALUES (16, 1, 'คาซ่ายูเรก้า', 'http://www.idmaximum.com/', '20140211165954Fpbm.jpg', '', 0, '', '2014-02-11 16:59:54', 0);
INSERT INTO `banner` VALUES (17, 8, 'Home', 'http://www.idmaximum.com/', '20140211175610gUZt.jpg', '', 0, '', '2014-02-11 17:56:10', 0);
INSERT INTO `banner` VALUES (18, 8, 'Flash', 'http://www.idmaximum.com/', '20140211175937cfY1.jpg', '2014_1_31_10_5_47.swf', 0, '', '2014-02-11 17:59:37', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `banner_category`
-- 

CREATE TABLE `banner_category` (
  `category_id` int(4) unsigned NOT NULL auto_increment,
  `category_name` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `category_size_img_des` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `category_size` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `category_order` tinyint(4) NOT NULL default '0',
  `category_type` enum('1','2') collate utf8_unicode_ci NOT NULL COMMENT '1=banner,2=slide image',
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `banner_category`
-- 

INSERT INTO `banner_category` VALUES (1, 'Home (ขนาดใหญ่)	', '940*140', '940', 2, '1');
INSERT INTO `banner_category` VALUES (2, 'Decoration (ขนาดเล็ก ด้านขวา)', '220*220', '220', 3, '1');
INSERT INTO `banner_category` VALUES (3, 'Decoration (ขนาดใหญ่)', '940*140', '940', 4, '1');
INSERT INTO `banner_category` VALUES (4, 'Kitchen (ขนาดเล็ก ด้านขวา)	', '220*220', '220', 5, '1');
INSERT INTO `banner_category` VALUES (5, 'Kitchen (ขนาดใหญ่)	', '940*140', '940', 6, '1');
INSERT INTO `banner_category` VALUES (6, 'Shopping (ขนาดเล็ก ด้านขวา)', '220*220', '220', 7, '1');
INSERT INTO `banner_category` VALUES (7, 'Like & Share (ขนาดใหญ่)', '940*140', '940', 8, '1');
INSERT INTO `banner_category` VALUES (8, 'รูปสไลด์หน้าแรก', '700*400', '700', 1, '2');

-- --------------------------------------------------------

-- 
-- Table structure for table `comment`
-- 

CREATE TABLE `comment` (
  `commentID` int(16) unsigned NOT NULL auto_increment,
  `itemID_FK` int(10) NOT NULL default '0',
  `memberID_FK` int(8) NOT NULL default '0',
  `comment` longtext collate utf8_unicode_ci NOT NULL,
  `dateTimeComment` datetime NOT NULL,
  `userIP` varchar(40) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`commentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `comment`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `files`
-- 

CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `size` int(11) default NULL,
  `itemID` tinyint(5) NOT NULL,
  `type` varchar(255) collate utf8_unicode_ci default NULL,
  `url` varchar(255) collate utf8_unicode_ci default NULL,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40 ;

-- 
-- Dumping data for table `files`
-- 

INSERT INTO `files` VALUES (38, '190_160.jpg', 21858, 0, 'image/jpeg', NULL, NULL, NULL);
INSERT INTO `files` VALUES (39, '190_160 (1).jpg', 21858, 0, 'image/jpeg', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `item`
-- 

CREATE TABLE `item` (
  `item_id` int(12) unsigned NOT NULL auto_increment,
  `item_category_id_FK` int(4) NOT NULL default '0',
  `item_sub_category_id_FK` int(4) NOT NULL default '0',
  `memberID_FK` int(8) NOT NULL,
  `item_title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `item_abstract` text collate utf8_unicode_ci NOT NULL,
  `item_detail` longtext collate utf8_unicode_ci NOT NULL,
  `item_image` varchar(100) collate utf8_unicode_ci NOT NULL,
  `item_image_thumb` varchar(100) collate utf8_unicode_ci NOT NULL,
  `item_visited` int(11) NOT NULL default '0',
  `item_like` int(8) NOT NULL default '0',
  `item_comment` int(8) NOT NULL default '0',
  `item_publish` enum('0','1') collate utf8_unicode_ci NOT NULL default '0',
  `item_create_date` datetime NOT NULL,
  `item_create_user` int(11) NOT NULL default '0',
  `item_update_date` datetime NOT NULL,
  `item_update_user` int(11) NOT NULL default '0',
  `order_by` int(8) NOT NULL,
  PRIMARY KEY  (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `item`
-- 

INSERT INTO `item` VALUES (1, 1, 0, 0, 'oceanic white tip shark', 'When-Attitudes-become', '', '20140210170753ew8p.jpg', '20140210170753ew8p-220p.jpg', 0, 0, 0, '1', '2014-02-10 17:07:53', 1, '2014-02-10 17:07:53', 0, 1);
INSERT INTO `item` VALUES (2, 3, 1, 0, 'western Sweden.', 'When-Attitudes-become', '', '20140210172403sFje.jpg', '20140210172403sFje-220p.jpg', 0, 0, 0, '1', '2014-02-10 17:12:58', 1, '2014-02-10 17:24:03', 1, 0);
INSERT INTO `item` VALUES (3, 3, 1, 0, 'Tip shark Oceanic white tip shark', 'When-Attitudes-become', '', '201402101716339AyM.jpg', '201402101716339AyM-220p.jpg', 0, 0, 0, '1', '2014-02-10 17:16:33', 1, '2014-02-10 17:16:33', 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `item_category`
-- 

CREATE TABLE `item_category` (
  `category_id` int(2) unsigned NOT NULL auto_increment,
  `category_name` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `category_order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `item_category`
-- 

INSERT INTO `item_category` VALUES (1, 'Decoration', 1);
INSERT INTO `item_category` VALUES (2, 'Kitchen', 2);
INSERT INTO `item_category` VALUES (3, 'Shopping', 3);
INSERT INTO `item_category` VALUES (4, 'Like & Share', 4);

-- --------------------------------------------------------

-- 
-- Table structure for table `item_image`
-- 

CREATE TABLE `item_image` (
  `item_image_id` int(10) unsigned NOT NULL auto_increment,
  `item_id` int(11) NOT NULL,
  `item_image_name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `item_image_thumb` varchar(100) collate utf8_unicode_ci NOT NULL,
  `item_image_description` varchar(500) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`item_image_id`),
  KEY `item_image_item_FK` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `item_image`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `item_subcategory`
-- 

CREATE TABLE `item_subcategory` (
  `sub_category_id` int(4) unsigned NOT NULL auto_increment,
  `sub_category_name` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `category_id` int(4) unsigned NOT NULL default '0',
  `category_sub_order` int(6) NOT NULL,
  PRIMARY KEY  (`sub_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `item_subcategory`
-- 

INSERT INTO `item_subcategory` VALUES (1, 'Product of Decoration', 3, 1);
INSERT INTO `item_subcategory` VALUES (2, 'Product of Home', 3, 2);
INSERT INTO `item_subcategory` VALUES (3, 'Decoration', 4, 1);
INSERT INTO `item_subcategory` VALUES (4, 'Kitchen', 4, 2);
INSERT INTO `item_subcategory` VALUES (5, 'Shopping', 4, 3);

-- --------------------------------------------------------

-- 
-- Table structure for table `like`
-- 

CREATE TABLE `like` (
  `likeID` int(16) unsigned NOT NULL auto_increment,
  `itemID_FK` int(10) NOT NULL default '0',
  `memberID_FK` int(8) NOT NULL default '0',
  `dateTimeLike` datetime NOT NULL,
  `userIP` varchar(40) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`likeID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `like`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `member`
-- 

CREATE TABLE `member` (
  `customer_id` int(12) unsigned NOT NULL auto_increment,
  `fbID` int(12) NOT NULL,
  `customer_fname` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `customer_lname` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `username` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `password` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `citizen_id` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `hint` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `answer` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `telephone_no` varchar(30) collate utf8_unicode_ci NOT NULL default '',
  `primary_email` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `enews_letter` enum('0','1') collate utf8_unicode_ci NOT NULL default '0' COMMENT '0=ไม่รับ,1=รับ',
  `created_dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_access` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_upd` datetime NOT NULL default '0000-00-00 00:00:00',
  `rip` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `activation` enum('0','1') collate utf8_unicode_ci NOT NULL default '0',
  `typeRegister` enum('1','2') collate utf8_unicode_ci NOT NULL default '1' COMMENT '1=onwebsite,2=facebook',
  PRIMARY KEY  (`customer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `member`
-- 

INSERT INTO `member` VALUES (1, 0, '', '', 'meawkiller@hotmail.com', '', '', '', '', '', 'meawkiller@hotmail.com', '0', '2014-02-18 15:40:26', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0', '1');
INSERT INTO `member` VALUES (2, 0, '', '', 'meawkiller@hotmail.com', '', '', '', '', '', 'meawkiller@hotmail.com', '0', '2014-02-18 15:40:38', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0', '1');
INSERT INTO `member` VALUES (3, 0, '', '', 'meawkillerdd@hotmail.com', '0f919ab7f0ebfc8c5ef1c87cadbb02a600fdd8999947d63712e173721a82d93e', '', '', '', '', 'meawkillerdd@hotmail.com', '0', '2014-02-18 16:45:34', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0', '1');
INSERT INTO `member` VALUES (4, 0, '', '', 'meawkiller2@hotmail.com', '0f919ab7f0ebfc8c5ef1c87cadbb02a600fdd8999947d63712e173721a82d93e', '', '', '', '', 'meawkiller2@hotmail.com', '0', '2014-02-18 17:02:03', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0', '1');
INSERT INTO `member` VALUES (5, 0, '', '', 'meawkilleffr2@hotmail.com', '0f919ab7f0ebfc8c5ef1c87cadbb02a600fdd8999947d63712e173721a82d93e', '', '', '', '', 'meawkilleffr2@hotmail.com', '0', '2014-02-18 17:04:16', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0', '1');
INSERT INTO `member` VALUES (6, 0, '', '', 'meawkillerddd@hotmail.com', '0f919ab7f0ebfc8c5ef1c87cadbb02a600fdd8999947d63712e173721a82d93e', '', '', '', '', 'meawkillerddd@hotmail.com', '0', '2014-02-18 17:05:59', '2014-02-18 17:05:59', '0000-00-00 00:00:00', '', '0', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `news`
-- 

CREATE TABLE `news` (
  `news_id` int(10) unsigned NOT NULL auto_increment,
  `news_title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `news_title_en` varchar(255) collate utf8_unicode_ci NOT NULL,
  `subscribe` varchar(255) collate utf8_unicode_ci NOT NULL,
  `issue_ebook` varchar(255) collate utf8_unicode_ci NOT NULL,
  `issue_pdf` varchar(255) collate utf8_unicode_ci NOT NULL,
  `take_a_peak` varchar(255) collate utf8_unicode_ci NOT NULL,
  `news_abstract` text collate utf8_unicode_ci NOT NULL,
  `news_abstract_en` text collate utf8_unicode_ci NOT NULL,
  `news_detail` longtext collate utf8_unicode_ci NOT NULL,
  `news_detail_en` longtext collate utf8_unicode_ci NOT NULL,
  `news_image` varchar(100) collate utf8_unicode_ci NOT NULL,
  `news_date` datetime NOT NULL,
  `news_image_thumb` varchar(100) collate utf8_unicode_ci NOT NULL,
  `news_visited` int(11) NOT NULL default '0',
  `news_publish` enum('0','1') collate utf8_unicode_ci NOT NULL default '0',
  `news_create_date` datetime NOT NULL,
  `news_create_user` int(11) NOT NULL default '0',
  `news_update_date` datetime NOT NULL,
  `news_update_user` int(11) NOT NULL default '0',
  `order_by` int(8) NOT NULL,
  PRIMARY KEY  (`news_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `news`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `picfrontpage`
-- 

CREATE TABLE `picfrontpage` (
  `front_id` int(8) NOT NULL auto_increment,
  `front_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `front_url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `front_pic` varchar(50) collate utf8_unicode_ci NOT NULL,
  `front_swf` varchar(50) collate utf8_unicode_ci NOT NULL,
  `front_visit` int(10) NOT NULL,
  `publish` enum('0','1') collate utf8_unicode_ci NOT NULL default '0',
  `typePosition` enum('2','1') collate utf8_unicode_ci NOT NULL default '1',
  `front_order` int(7) NOT NULL,
  PRIMARY KEY  (`front_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `picfrontpage`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `picslider`
-- 

CREATE TABLE `picslider` (
  `picslider_id` int(10) NOT NULL auto_increment,
  `picsliderCategoryID_FK` int(2) NOT NULL default '0',
  `picslider_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `picslider_url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `picslider_pic` varchar(50) collate utf8_unicode_ci NOT NULL,
  `picslider_swf` varchar(50) collate utf8_unicode_ci NOT NULL,
  `visited` int(10) NOT NULL,
  `publish` enum('0','1') collate utf8_unicode_ci NOT NULL default '0',
  `CreateDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `picslider_order` int(10) NOT NULL default '0',
  PRIMARY KEY  (`picslider_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- 
-- Dumping data for table `picslider`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `picslider_category`
-- 

CREATE TABLE `picslider_category` (
  `category_id` int(4) unsigned NOT NULL auto_increment,
  `category_name` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `category_size_img_des` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `category_size` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `category_order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `picslider_category`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pin_article`
-- 

CREATE TABLE `pin_article` (
  `commentID` int(16) unsigned NOT NULL auto_increment,
  `itemID_FK` int(10) NOT NULL default '0',
  `memberID_FK` int(8) NOT NULL default '0',
  `comment` longtext collate utf8_unicode_ci NOT NULL,
  `dateTimeComment` datetime NOT NULL,
  `userIP` varchar(40) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`commentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pin_article`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sbd_profile`
-- 

CREATE TABLE `sbd_profile` (
  `profile_id` mediumint(8) unsigned NOT NULL auto_increment,
  `username` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `password` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `status` enum('Y','N') collate utf8_unicode_ci NOT NULL default 'Y',
  `name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `group_id` mediumint(8) unsigned NOT NULL default '2' COMMENT '1=Admin, 2=Staff',
  `lastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
  `update_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`profile_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `sbd_profile`
-- 

INSERT INTO `sbd_profile` VALUES (1, 'idmax69', '6e271db4c9884d626b88f8d8e92c8cad1743d3bb6e1b60e6b684f7178fbd85d9', 'webmaster@8webs.com', 'Y', 'WebMaster', 88, '2014-02-17 14:45:57', '2009-02-27 11:38:00', '2009-02-11 10:47:15');
