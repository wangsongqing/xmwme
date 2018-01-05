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
  `key` int(11) unsigned NOT NULL COMMENT '活动key',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0关闭 1开启',
  `rule` text COMMENT '活动规则 (每天获得上线等)序列化数据',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动表';

/*Data for the table `xm_activity` */

/*Table structure for table `xm_activity_log` */

DROP TABLE IF EXISTS `xm_activity_log`;

CREATE TABLE `xm_activity_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '参与时间',
  `updated` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `activity_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动参与记录表';

/*Data for the table `xm_activity_log` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
