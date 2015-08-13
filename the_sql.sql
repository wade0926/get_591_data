-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- 主機: localhost
-- 建立日期: Aug 13, 2015, 08:55 AM
-- 伺服器版本: 5.0.51
-- PHP 版本: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 資料庫: `github`
-- 

-- --------------------------------------------------------

-- 
-- 資料表格式： `591_link_data`
-- 

CREATE TABLE `591_link_data` (
  `5_id` int(11) NOT NULL auto_increment,
  `5_name_id` varchar(100) NOT NULL,
  `5_name` varchar(30) NOT NULL COMMENT '姓名',
  `5_company` varchar(100) NOT NULL COMMENT '就職公司',
  `5_cell_phone` varchar(30) NOT NULL COMMENT '行動電話',
  `5_serve_area` varchar(100) NOT NULL COMMENT '服務區域',
  `5_mail` varchar(100) NOT NULL COMMENT 'E-mail',
  `5_del` tinyint(1) NOT NULL,
  PRIMARY KEY  (`5_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10319 ;
