/*
Navicat MySQL Data Transfer

Source Server         : wendy
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : club

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-03-05 22:14:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for club_credit
-- ----------------------------
DROP TABLE IF EXISTS `club_credit`;
CREATE TABLE `club_credit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `credit` varchar(255) DEFAULT NULL COMMENT '积分类型',
  `low` int(255) DEFAULT NULL,
  `high` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of club_credit
-- ----------------------------
INSERT INTO `club_credit` VALUES ('1', '出入江湖', '0', '50');
INSERT INTO `club_credit` VALUES ('2', '小有名气', '51', '100');
INSERT INTO `club_credit` VALUES ('3', '江湖少侠', '101', '150');
INSERT INTO `club_credit` VALUES ('4', '一代宗师', '151', '200');

-- ----------------------------
-- Table structure for club_credit_log
-- ----------------------------
DROP TABLE IF EXISTS `club_credit_log`;
CREATE TABLE `club_credit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '用户id',
  `type` int(11) NOT NULL COMMENT '类型，1表示登录，2表示报名活动，3表示评论，4表示加入社团，5表示注册',
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of club_credit_log
-- ----------------------------
INSERT INTO `club_credit_log` VALUES ('1', '4', '1', '1488627434');
INSERT INTO `club_credit_log` VALUES ('2', '4', '1', '1488628116');
INSERT INTO `club_credit_log` VALUES ('3', '4', '1', '1488681780');
INSERT INTO `club_credit_log` VALUES ('4', '4', '1', '1488696966');

-- ----------------------------
-- Table structure for club_user
-- ----------------------------
DROP TABLE IF EXISTS `club_user`;
CREATE TABLE `club_user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增用户id',
  `username` varchar(255) NOT NULL,
  `realname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `mobile` char(11) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `credit` int(255) DEFAULT '0' COMMENT '积分',
  `experience` int(255) DEFAULT '0' COMMENT '经验值',
  `money` int(255) DEFAULT '0' COMMENT '金钱值',
  `donation` int(255) DEFAULT '0' COMMENT '贡献值',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of club_user
-- ----------------------------
INSERT INTO `club_user` VALUES ('4', 'wendy', null, '1104777947@qq.com', 'e10adc3949ba59abbe56e057f20f883e', null, '/avatar/7b2dcc665b1f623779c30a27a1f34906.jpg', '1', '3', '3', '0', '0');
