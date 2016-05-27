/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50547
Source Host           : 127.0.0.1:3306
Source Database       : restaurant

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-02-19 16:52:42
*/

DROP DATABASE IF EXISTS restaurant;
CREATE DATABASE restaurant DEFAULT CHARSET=utf8;

USE restaurant;


SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tp_books
-- ----------------------------
DROP TABLE IF EXISTS `tp_books`;
CREATE TABLE `tp_books` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `food_id` varchar(50) NOT NULL,
  `count` varchar(30) NOT NULL DEFAULT '0',
  `user_id` smallint(6) unsigned NOT NULL,
  `booktime` varchar(10) NOT NULL,
  `pay` tinyint(1) unsigned DEFAULT '0',
  `status` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_books
-- ----------------------------
INSERT INTO `tp_books` VALUES ('1', '8,9', '2,2', '1', '1453371792', '0', '0');
INSERT INTO `tp_books` VALUES ('2', '7,8', '1,2', '1', '1453383982', '0', '1');

-- ----------------------------
-- Table structure for tp_foods
-- ----------------------------
DROP TABLE IF EXISTS `tp_foods`;
CREATE TABLE `tp_foods` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `foodname` varchar(30) NOT NULL,
  `type` enum('主餐','饮料') NOT NULL DEFAULT '主餐',
  `price` float(11,2) NOT NULL DEFAULT '0.00',
  `imageurl` varchar(50) DEFAULT NULL,
  `totalcount` int(11) DEFAULT '0',
  `discount` float(3,1) DEFAULT '10.0',
  `status` tinyint(1) unsigned DEFAULT '0',
  `extra` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_foods
-- ----------------------------
INSERT INTO `tp_foods` VALUES ('1', '烧鸭', '主餐', '998.50', './Uploads/food_pics/569e419bb7397.jpg', '0', '9.8', '0', '正宗的北京烤鸭');
INSERT INTO `tp_foods` VALUES ('2', '猪杂', '主餐', '10.00', './Uploads/food_pics/569e43b5469e7.jpg', '0', '10.0', '0', '汤饭中的经典——猪杂汤饭');
INSERT INTO `tp_foods` VALUES ('3', '牛杂', '主餐', '20.00', './Uploads/food_pics/569e4414e252a.jpg', '0', '9.0', '0', '好吃又美味，你，还在等什么？');
INSERT INTO `tp_foods` VALUES ('4', '百事可乐', '饮料', '9.60', './Uploads/food_pics/569e44663d8a3.jpg', '0', '6.0', '0', '');
INSERT INTO `tp_foods` VALUES ('5', '拿铁咖啡', '饮料', '68.00', './Uploads/food_pics/569e44b705e1a.png', '0', '8.8', '0', '国外进口，正宗拿铁咖啡');
INSERT INTO `tp_foods` VALUES ('6', '韩式石锅饭', '', '32.00', './Uploads/food_pics/569e45262b1f1.jpg', '0', '10.0', '0', '韩式风味，你值得拥有！');
INSERT INTO `tp_foods` VALUES ('7', '苦瓜炒蛋', '主餐', '12.00', './Uploads/food_pics/569e458921d39.jpg', '0', '9.5', '0', '清凉下火，必备苦瓜炒蛋');
INSERT INTO `tp_foods` VALUES ('8', '啤酒炸鸡', '主餐', '1099.00', './Uploads/food_pics/569e45ebea7da.jpg', '0', '7.7', '0', '韩式风味，情侣必备，啤酒炸鸡');
INSERT INTO `tp_foods` VALUES ('9', '海底捞', '', '99.00', './Uploads/food_pics/569e462f840a7.jpg', '0', '10.0', '0', '这是什么鬼？');
INSERT INTO `tp_foods` VALUES ('10', '炸鸡腿', '主餐', '6.00', './Uploads/food_pics/569f0d79d0f1b.jpg', '0', '10.0', '1', '新鲜出炉的黄金炸鸡腿，香喷喷');

-- ----------------------------
-- Table structure for tp_generaluser
-- ----------------------------
DROP TABLE IF EXISTS `tp_generaluser`;
CREATE TABLE `tp_generaluser` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `logintime` varchar(10) DEFAULT NULL,
  `loginip` varchar(15) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `address` varchar(50) DEFAULT NULL,
  `createDate` varchar(20) DEFAULT NULL,
  `locked` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_generaluser
-- ----------------------------
INSERT INTO `tp_generaluser` VALUES ('1', 'Hongxuan', 'caf5e1b52ceb39256f8f787bebc45fc4', '1455855695', '0.0.0.0', '1758677739@qq.com', '18316960780', '佛山大学本部西三105', '1453213405', '0');

-- ----------------------------
-- Table structure for tp_session
-- ----------------------------
DROP TABLE IF EXISTS `tp_session`;
CREATE TABLE `tp_session` (
  `session_id` varchar(255) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` blob,
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_session
-- ----------------------------
INSERT INTO `tp_session` VALUES ('ldn759o3c7osr3j7rlua0ho5u3', '1455873096', 0x7665726966797C733A33323A223136373930393163356138383066616636666235653630383765623162326463223B);

-- ----------------------------
-- Table structure for tp_user
-- ----------------------------
DROP TABLE IF EXISTS `tp_user`;
CREATE TABLE `tp_user` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `logintime` varchar(10) DEFAULT NULL,
  `loginip` varchar(15) DEFAULT NULL,
  `locked` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user
-- ----------------------------
INSERT INTO `tp_user` VALUES ('1', 'admin', '030462464b663cf921a47c2f6aad5014', '1455865044', '0.0.0.0', '0');
