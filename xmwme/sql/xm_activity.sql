/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.5.53 : Database - xm_activity
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`xm_activity` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `xm_activity`;

/*Table structure for table `xm_activity` */

DROP TABLE IF EXISTS `xm_activity`;

CREATE TABLE `xm_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `akey` varchar(50) NOT NULL DEFAULT '' COMMENT '活动key',
  `activity_name` varchar(50) NOT NULL DEFAULT '' COMMENT '活动名称',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `url` varchar(200) DEFAULT '' COMMENT '活动连接',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0关闭 1开启',
  `img_url` varchar(200) DEFAULT '' COMMENT '图片url',
  `rule` text COMMENT '活动规则 (每天获得上线等)序列化数据',
  `created` int(10) DEFAULT '0' COMMENT '创建时间',
  `updated` int(10) DEFAULT '0' COMMENT '编辑时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`akey`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='活动表';

/*Table structure for table `xm_activity_log` */

DROP TABLE IF EXISTS `xm_activity_log`;

CREATE TABLE `xm_activity_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '参与时间',
  `updated` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `activity_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='活动参与记录表';

/*Table structure for table `xm_lian` */

DROP TABLE IF EXISTS `xm_lian`;

CREATE TABLE `xm_lian` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `telephone` char(11) NOT NULL COMMENT '用户手机号',
  `lian_num` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '连接图标个数',
  `is_floop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:未使用翻倍卡,1:使用翻倍卡',
  `score` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '本次连连看获得分数',
  `nick` varchar(20) NOT NULL DEFAULT '' COMMENT '用户的昵称',
  `credit` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `join_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与类型 0免费 1邀请',
  `created` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `created` (`created`),
  KEY `join_type` (`join_type`),
  KEY `score` (`score`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='连连看游戏参与记录表';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
