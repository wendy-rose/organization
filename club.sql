/*
Navicat MySQL Data Transfer

Source Server         : wendy
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : club

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-05-02 23:46:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for club_attach
-- ----------------------------
DROP TABLE IF EXISTS `club_attach`;
CREATE TABLE `club_attach` (
  `attachid` int(11) NOT NULL AUTO_INCREMENT,
  `attachname` varchar(255) NOT NULL,
  `attachurl` varchar(255) NOT NULL,
  `attachicon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`attachid`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for club_corp
-- ----------------------------
DROP TABLE IF EXISTS `club_corp`;
CREATE TABLE `club_corp` (
  `cid` int(11) NOT NULL AUTO_INCREMENT COMMENT '社团id',
  `corpname` varchar(255) NOT NULL COMMENT '社团名称',
  `createuid` int(11) NOT NULL COMMENT '创建者uid',
  `univeristy` varchar(255) NOT NULL COMMENT '学校',
  `class` varchar(255) NOT NULL COMMENT '专业班级',
  `number` varchar(255) NOT NULL COMMENT '学号',
  `corppic` varchar(255) NOT NULL,
  `attach` varchar(255) NOT NULL COMMENT '附件id',
  `description` text NOT NULL COMMENT '简介',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  `num` int(10) NOT NULL COMMENT '总人数',
  `belong` tinyint(2) NOT NULL COMMENT '1表示个人2表示学院3表示学校',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for club_corp_number
-- ----------------------------
DROP TABLE IF EXISTS `club_corp_number`;
CREATE TABLE `club_corp_number` (
  `id` int(11) NOT NULL COMMENT '自增id',
  `uid` int(11) NOT NULL COMMENT '用户表id',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `password` char(32) NOT NULL COMMENT '密码',
  `mobile` char(11) NOT NULL COMMENT '手机号',
  `deptid` int(11) NOT NULL COMMENT '部门id',
  `pid` int(11) NOT NULL COMMENT '职位id',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
-- Table structure for club_credit_log
-- ----------------------------
DROP TABLE IF EXISTS `club_credit_log`;
CREATE TABLE `club_credit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '用户id',
  `type` int(11) NOT NULL COMMENT '类型，1表示登录，2表示报名活动，3表示评论，4表示加入社团，5表示注册',
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for club_dept
-- ----------------------------
DROP TABLE IF EXISTS `club_dept`;
CREATE TABLE `club_dept` (
  `deptid` int(11) NOT NULL COMMENT '部门id',
  `cid` int(11) NOT NULL COMMENT '社团id',
  `name` varchar(255) NOT NULL COMMENT '部门名称',
  `manager` int(11) NOT NULL COMMENT '主管id',
  `mobile` char(11) NOT NULL COMMENT '手机号码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态值',
  `pid` int(11) NOT NULL COMMENT '上级部门id',
  PRIMARY KEY (`deptid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for club_position
-- ----------------------------
DROP TABLE IF EXISTS `club_position`;
CREATE TABLE `club_position` (
  `pid` int(11) NOT NULL COMMENT '职位id',
  `name` varchar(255) NOT NULL COMMENT '职位名称',
  `cid` int(11) NOT NULL COMMENT '社团id',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
-- Table structure for person
-- ----------------------------
DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '姓名',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `age` int(3) DEFAULT '0' COMMENT '年龄',
  `sex` enum('女','男') DEFAULT '男' COMMENT '性别',
  `salary` int(11) DEFAULT NULL COMMENT '薪水',
  `address` varchar(50) DEFAULT NULL COMMENT '地址',
  `remark` varchar(300) DEFAULT NULL COMMENT '备注',
  `createtime` datetime DEFAULT NULL COMMENT '创建日期',
  `updatetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
