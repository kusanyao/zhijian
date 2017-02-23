/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-02-23 17:24:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for b_article
-- ----------------------------
DROP TABLE IF EXISTS `b_article`;
CREATE TABLE `b_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cate_id` int(10) unsigned NOT NULL COMMENT 'category.id',
  `title` char(50) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';

-- ----------------------------
-- Table structure for b_category
-- ----------------------------
DROP TABLE IF EXISTS `b_category`;
CREATE TABLE `b_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(50) NOT NULL COMMENT '分类名',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_title` (`title`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类表';

-- ----------------------------
-- Table structure for b_comment
-- ----------------------------
DROP TABLE IF EXISTS `b_comment`;
CREATE TABLE `b_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `article_id` int(10) unsigned NOT NULL COMMENT '文章id',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `content` varchar(1000) NOT NULL COMMENT '内容',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';

-- ----------------------------
-- Table structure for f_cash
-- ----------------------------
DROP TABLE IF EXISTS `f_cash`;
CREATE TABLE `f_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cash_no` bigint(20) unsigned NOT NULL COMMENT '提现单号',
  `trade_no` varchar(30) NOT NULL COMMENT '第三方交易号',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user_id',
  `mode` tinyint(3) unsigned NOT NULL COMMENT '提现方式:',
  `state` tinyint(3) unsigned NOT NULL COMMENT '状态:trans',
  `amount` smallint(6) NOT NULL COMMENT '金额',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户表';

-- ----------------------------
-- Table structure for f_diary
-- ----------------------------
DROP TABLE IF EXISTS `f_diary`;
CREATE TABLE `f_diary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user_id',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型:',
  `amount` smallint(6) NOT NULL COMMENT '金额',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户表';

-- ----------------------------
-- Table structure for g_category
-- ----------------------------
DROP TABLE IF EXISTS `g_category`;
CREATE TABLE `g_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` char(50) NOT NULL COMMENT '分类名',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_title` (`title`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类表';

-- ----------------------------
-- Table structure for g_goods
-- ----------------------------
DROP TABLE IF EXISTS `g_goods`;
CREATE TABLE `g_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user.id',
  `cate_id` int(10) unsigned NOT NULL COMMENT 'category.id',
  `title` char(50) NOT NULL COMMENT '商品标题',
  `ltitle` char(100) NOT NULL COMMENT '长标题',
  `picture` varchar(50) NOT NULL COMMENT '商品图片',
  `price` smallint(5) unsigned NOT NULL COMMENT '商品价格',
  `sprice` smallint(5) unsigned NOT NULL COMMENT '售卖价格',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Table structure for g_picture
-- ----------------------------
DROP TABLE IF EXISTS `g_picture`;
CREATE TABLE `g_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `goods_id` int(10) unsigned NOT NULL COMMENT 'goods.id',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型:',
  `url` varchar(50) NOT NULL COMMENT '商品图片',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品图片表';

-- ----------------------------
-- Table structure for o_deliver
-- ----------------------------
DROP TABLE IF EXISTS `o_deliver`;
CREATE TABLE `o_deliver` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_no` bigint(20) unsigned NOT NULL COMMENT '订单号',
  `deliver_no` tinyint(3) unsigned NOT NULL COMMENT '快递单号',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发货信息表';

-- ----------------------------
-- Table structure for o_order
-- ----------------------------
DROP TABLE IF EXISTS `o_order`;
CREATE TABLE `o_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user.id',
  `shop_id` int(10) unsigned NOT NULL COMMENT 'shop.id',
  `main_no` bigint(20) unsigned NOT NULL COMMENT '主订单号',
  `pay_no` bigint(20) unsigned NOT NULL COMMENT '支付号',
  `order_no` bigint(20) unsigned NOT NULL COMMENT '子订单号',
  `amount` tinyint(3) unsigned NOT NULL COMMENT '订单金额',
  `order_state` tinyint(3) unsigned NOT NULL COMMENT '订单状态:',
  `pay_state` tinyint(3) unsigned NOT NULL COMMENT '订单状态:',
  `deliver_state` tinyint(3) unsigned NOT NULL COMMENT '送货状态:',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_order_no` (`order_no`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Table structure for o_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `o_order_goods`;
CREATE TABLE `o_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_no` bigint(20) unsigned NOT NULL COMMENT '订单号',
  `deliver_no` char(30) NOT NULL COMMENT '快递单号',
  `goods_id` int(10) unsigned NOT NULL COMMENT 'goods.id',
  `title` varchar(50) NOT NULL COMMENT '商品标题',
  `ltitle` varchar(100) NOT NULL COMMENT '长标题',
  `picture` varchar(100) NOT NULL COMMENT '商品图片',
  `price` smallint(5) unsigned NOT NULL COMMENT '商品价格',
  `sprice` smallint(5) unsigned NOT NULL COMMENT '售卖价格',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Table structure for p_weixin
-- ----------------------------
DROP TABLE IF EXISTS `p_weixin`;
CREATE TABLE `p_weixin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user.id',
  `pay_no` bigint(20) unsigned NOT NULL COMMENT '支付号',
  `amount` smallint(5) unsigned NOT NULL COMMENT '支付金额',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付信息表';

-- ----------------------------
-- Table structure for p_weixin_notify
-- ----------------------------
DROP TABLE IF EXISTS `p_weixin_notify`;
CREATE TABLE `p_weixin_notify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user.id',
  `pay_no` bigint(20) unsigned NOT NULL COMMENT '支付号',
  `amount` smallint(5) unsigned NOT NULL COMMENT '支付金额',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发货信息表';

-- ----------------------------
-- Table structure for u_follow
-- ----------------------------
DROP TABLE IF EXISTS `u_follow`;
CREATE TABLE `u_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user_id',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏表';

-- ----------------------------
-- Table structure for u_shop
-- ----------------------------
DROP TABLE IF EXISTS `u_shop`;
CREATE TABLE `u_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user.id',
  `shop_name` char(20) NOT NULL COMMENT '用户名',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Table structure for u_user
-- ----------------------------
DROP TABLE IF EXISTS `u_user`;
CREATE TABLE `u_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `nickname` char(20) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `phone` char(11) NOT NULL COMMENT '手机号',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT 'email',
  `balance` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '余额',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_nickname` (`nickname`) USING BTREE,
  UNIQUE KEY `un_phone` (`phone`) USING BTREE,
  UNIQUE KEY `un_email` (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Table structure for u_weixin
-- ----------------------------
DROP TABLE IF EXISTS `u_weixin`;
CREATE TABLE `u_weixin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user.id',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `headimgurl` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `openid` varchar(255) NOT NULL COMMENT '微信openid',
  `unionid` varchar(255) NOT NULL DEFAULT '' COMMENT '微信联登的unionid',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_user_id` (`user_id`) USING BTREE,
  UNIQUE KEY `un_openid` (`openid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户表';

-- ----------------------------
-- Table structure for u_weixin_relation
-- ----------------------------
DROP TABLE IF EXISTS `u_weixin_relation`;
CREATE TABLE `u_weixin_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'user_id',
  `site` tinyint(3) unsigned NOT NULL COMMENT 'site',
  `openid` varchar(255) NOT NULL COMMENT '微信openid',
  `unionid` varchar(255) NOT NULL DEFAULT '' COMMENT '微信联登的unionid',
  `insert_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户表';
