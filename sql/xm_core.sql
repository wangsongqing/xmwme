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

/*Table structure for table `xm_goods` */

DROP TABLE IF EXISTS `xm_goods`;

CREATE TABLE `xm_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(50) NOT NULL DEFAULT '' COMMENT '商品名称',
  `store` int(9) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '商品价格',
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
  `goods_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品个数',
  `credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消耗积分',
  `content` text COMMENT '备注',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_num` (`orders_num`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `is_get` (`is_get`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='订单表';

/*Table structure for table `xm_redbag` */

DROP TABLE IF EXISTS `xm_redbag`;

CREATE TABLE `xm_redbag` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `red_bag` decimal(10,2) DEFAULT '0.00' COMMENT '可用红包',
  `use_red_bag` decimal(10,2) DEFAULT '0.00' COMMENT '已经提现红包',
  `all_red_bag` decimal(10,2) DEFAULT '0.00' COMMENT '累计获得红包',
  `created` int(10) NOT NULL COMMENT '创建时间',
  `updated` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`user_id`),
  KEY `created` (`created`),
  KEY `updated` (`updated`),
  KEY `credit` (`red_bag`),
  KEY `use_credit` (`use_red_bag`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户积分表';

/*Table structure for table `xm_redbag_log` */

DROP TABLE IF EXISTS `xm_redbag_log`;

CREATE TABLE `xm_redbag_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `redbag` decimal(10,2) DEFAULT '0.00' COMMENT '变动积分',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:获得 2:提现',
  `created` int(10) NOT NULL COMMENT '创建时间',
  `updated` int(10) NOT NULL COMMENT '更新时间',
  `activity_id` int(10) DEFAULT '0' COMMENT '活动id',
  `goods_id` int(10) DEFAULT '0' COMMENT '商品id',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `activity_id` (`activity_id`),
  KEY `created` (`created`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='用户积分变动表';

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `xm_withdraw` */

DROP TABLE IF EXISTS `xm_withdraw`;

CREATE TABLE `xm_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '提现金额',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:审核中 2:审核通过 3:审核不通过 4:已到账',
  `orders` varchar(50) NOT NULL DEFAULT '' COMMENT '提现订单号',
  `created` int(10) NOT NULL COMMENT '发起时间',
  `updated` int(10) NOT NULL COMMENT '更新时间',
  `start_time` int(10) NOT NULL COMMENT '到账时间',
  `content` text NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户提现表';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
