/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.5.53 : Database - xm_core
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`xm_core` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `xm_core`;

/*Table structure for table `xm_address` */

DROP TABLE IF EXISTS `xm_address`;

CREATE TABLE `xm_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `contanct` varchar(64) NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` char(12) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province` varchar(64) NOT NULL DEFAULT '' COMMENT '省ID',
  `city` varchar(64) NOT NULL DEFAULT '' COMMENT '城市ID',
  `region` varchar(64) NOT NULL DEFAULT '' COMMENT '区（县）',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '区街道楼号',
  `is_default` int(10) NOT NULL DEFAULT '0' COMMENT '是否默认地址 0否，1是',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除  0未删除 1已删除',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收货地址信息表';

/*Table structure for table `xm_areas` */

DROP TABLE IF EXISTS `xm_areas`;

CREATE TABLE `xm_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '用户名',
  `nid` varchar(50) DEFAULT NULL COMMENT '标示名',
  `status` int(2) NOT NULL COMMENT '状态',
  `pid` int(11) DEFAULT NULL COMMENT '父级ID',
  `province` int(11) NOT NULL DEFAULT '0' COMMENT '省份',
  `city` int(11) NOT NULL DEFAULT '0' COMMENT '城市',
  `domain` varchar(100) DEFAULT NULL COMMENT '范围',
  `order` int(11) DEFAULT NULL COMMENT '排序',
  `tenpay` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`),
  KEY `pid` (`pid`),
  KEY `province` (`province`),
  KEY `city` (`city`),
  KEY `nid_pid` (`nid`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=3578 DEFAULT CHARSET=utf8 COMMENT='地址认证表';

/*Table structure for table `xm_banner` */

DROP TABLE IF EXISTS `xm_banner`;

CREATE TABLE `xm_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(10) NOT NULL DEFAULT '0' COMMENT '关联活动id',
  `banner_name` varchar(50) DEFAULT NULL COMMENT 'banner名称',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '点击连接地址',
  `img_url` varchar(100) NOT NULL DEFAULT '' COMMENT '图片地址',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0停用 1启用',
  `created` int(10) NOT NULL COMMENT '创建时间',
  `updated` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='首页banner表';

/*Table structure for table `xm_credit` */

DROP TABLE IF EXISTS `xm_credit`;

CREATE TABLE `xm_credit` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `credit` int(10) DEFAULT '0' COMMENT '可用积分',
  `use_credit` int(10) DEFAULT '0' COMMENT '已经使用积分',
  `all_credit` int(10) DEFAULT '0' COMMENT '累计获得积分',
  `created` int(10) NOT NULL COMMENT '创建时间',
  `updated` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`user_id`),
  KEY `created` (`created`),
  KEY `updated` (`updated`),
  KEY `credit` (`credit`),
  KEY `use_credit` (`use_credit`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户积分表';

/*Table structure for table `xm_credit_log` */

DROP TABLE IF EXISTS `xm_credit_log`;

CREATE TABLE `xm_credit_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `credit` int(10) DEFAULT '0' COMMENT '变动积分',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:获得 2:兑换',
  `created` int(10) NOT NULL COMMENT '创建时间',
  `updated` int(10) NOT NULL COMMENT '更新时间',
  `activity_id` int(10) DEFAULT '0' COMMENT '活动id',
  `goods_id` int(10) DEFAULT '0' COMMENT '商品id',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `activity_id` (`activity_id`),
  KEY `created` (`created`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='用户积分变动表';

/*Table structure for table `xm_goods` */

DROP TABLE IF EXISTS `xm_goods`;

CREATE TABLE `xm_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(50) NOT NULL DEFAULT '' COMMENT '商品名称',
  `store` int(9) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '商品状态 0下架 1上架',
  `credit` int(9) unsigned NOT NULL DEFAULT '0' COMMENT '商品所需积分',
  `list_pic` varchar(255) NOT NULL DEFAULT '' COMMENT '商品列表图片',
  `detail_pic` varchar(255) NOT NULL DEFAULT '' COMMENT '商品详情图片',
  `content` text COMMENT '商品详情',
  `goods_type` tinyint(1) DEFAULT '1' COMMENT '商品类型 1虚拟 2实物',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='积分商城';

/*Table structure for table `xm_manage_log` */

DROP TABLE IF EXISTS `xm_manage_log`;

CREATE TABLE `xm_manage_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL,
  `admin_name` varchar(50) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `created` int(11) unsigned NOT NULL,
  `phone` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Table structure for table `xm_manage_user` */

DROP TABLE IF EXISTS `xm_manage_user`;

CREATE TABLE `xm_manage_user` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `admin_name` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员帐号',
  `realname` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员真实姓名',
  `salt` varchar(6) DEFAULT NULL COMMENT '随机字段',
  `type` tinyint(1) unsigned NOT NULL COMMENT '后台类型  1管理后台',
  `mobile` varchar(11) DEFAULT NULL COMMENT '绑定电话',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属用户组ID',
  `group_name` varchar(24) NOT NULL DEFAULT '' COMMENT '所属用户组名',
  `operate_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人ID',
  `operate_name` varchar(24) NOT NULL DEFAULT '' COMMENT '操作人用户名',
  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '账户状态，0停用，1正常，-1锁定',
  `operation_time` int(10) DEFAULT '0' COMMENT '最后操作时间',
  `lock_reason` varchar(50) DEFAULT NULL COMMENT '锁定原因',
  `lock_time` int(10) unsigned DEFAULT NULL COMMENT '锁定时间',
  `unlock_time` int(10) unsigned DEFAULT NULL COMMENT '解锁时间',
  PRIMARY KEY (`admin_id`),
  KEY `uId` (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

/*Table structure for table `xm_orders` */

DROP TABLE IF EXISTS `xm_orders`;

CREATE TABLE `xm_orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `orders_num` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `address_id` int(10) NOT NULL DEFAULT '0' COMMENT '地址id',
  `is_get` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发货状态 0未发货 1发货',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `money` decimal(11,3) unsigned DEFAULT '0.000' COMMENT '需要补差价',
  `number` varchar(50) DEFAULT '' COMMENT '快递单号',
  `goods_type` tinyint(1) DEFAULT '1' COMMENT '商品类型 1虚拟 2实物',
  `content` text COMMENT '备注',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_num` (`orders_num`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `is_get` (`is_get`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单表';

/*Table structure for table `xm_user_info` */

DROP TABLE IF EXISTS `xm_user_info`;

CREATE TABLE `xm_user_info` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nick` varchar(64) DEFAULT '' COMMENT '昵称',
  `telephone` char(11) DEFAULT '0' COMMENT '电话',
  `form_code` int(10) DEFAULT '0' COMMENT '邀请码',
  `from_user_id` int(11) unsigned DEFAULT '0' COMMENT '邀请人id',
  `to_user_count` int(5) unsigned DEFAULT '0' COMMENT '邀请成功注册的人数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '注册类型：0自然注册 1邀请注册',
  `last_login_time` int(10) DEFAULT '0' COMMENT '最后一次登录时间',
  `regiest_ip` varchar(15) NOT NULL DEFAULT '0' COMMENT '注册ip地址',
  `locked` tinyint(1) DEFAULT '0' COMMENT '是否锁定用户账号 0正常，1锁定',
  `password` char(32) NOT NULL COMMENT '密码',
  `salt` char(6) NOT NULL COMMENT '加密随机常量（用6位的常量）',
  `created` int(10) NOT NULL COMMENT '创建时间',
  `updated` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `telephone` (`telephone`),
  KEY `created` (`created`) USING BTREE,
  KEY `updated` (`updated`) USING BTREE,
  KEY `from_user_id` (`from_user_id`) USING BTREE,
  KEY `type` (`type`),
  KEY `last_login_time` (`last_login_time`),
  KEY `form_code` (`form_code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
