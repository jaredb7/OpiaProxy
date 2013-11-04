/*
Navicat MySQL Data Transfer

Source Server         : brislinkv2
Source Server Version : 50532
Source Host           : 192.168.1.156:3306
Source Database       : brislinkv2

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2013-10-19 20:55:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `proxy_cache`
-- ----------------------------
DROP TABLE IF EXISTS `proxy_cache`;
CREATE TABLE `proxy_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_hash` varchar(64) NOT NULL,
  `request_response` text NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of proxy_cache
-- ----------------------------
