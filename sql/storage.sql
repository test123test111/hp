DROP TABLE IF EXISTS `box`;
CREATE TABLE `box` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`status` tinyint(4) NOT NULL DEFAULT '0',
`created_uid` int(11) NOT NULL,
`modified_uid` int(11) NOT NULL,
`created_time` int(11) DEFAULT NULL,
`modified_time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `status`(`status`),
KEY `created_uid`(`created_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `box_order`;
CREATE TABLE `box_order` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`box_id` int(11) NOT NULL,
`order_id` int(11) NOT NULL,
`status` tinyint(4) NOT NULL DEFAULT '0',
`created_uid` int(11) NOT NULL,
`modified_uid` int(11) NOT NULL,
`created_time` int(11) DEFAULT NULL,
`modified_time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `box_id`(`box_id`),
KEY `order_id`(`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stock_order_delete`;
CREATE TABLE `stock_order_delete` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`box_id` int(11) NOT NULL,
`order_id` int(11) NOT NULL,
`reason` tinyint(4) NOT NULL DEFAULT '0',
`created_uid` int(11) NOT NULL,
`modified_uid` int(11) NOT NULL,
`created_time` int(11) DEFAULT NULL,
`modified_time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `box_id`(`box_id`),
KEY `order_id`(`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wait_output`;
CREATE TABLE `wait_output` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`box_id` int(11) NOT NULL,
`express_no` varchar(64) NOT NULL DEFAULT '',
`order_id` int(11) NOT NULL,
`created_uid` int(11) NOT NULL,
`modified_uid` int(11) NOT NULL,
`created_time` int(11) DEFAULT NULL,
`modified_time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `box_id`(`box_id`),
KEY `order_id`(`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `printer`;
CREATE TABLE `printer` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`type` tinyint(4) NOT NULL DEFAULT '0',
`desc` varchar(255) NOT NULL DEFAULT '',
`status` tinyint(4) NOT NULL DEFAULT '0',
`created_uid` int(11) NOT NULL,
`modified_uid` int(11) NOT NULL,
`created_time` int(11) DEFAULT NULL,
`modified_time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `printer_user`;
CREATE TABLE `printer_user` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`printer_id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`type` tinyint(4) NOT NULL DEFAULT '0',
`status` tinyint(4) NOT NULL DEFAULT '0',
`created_uid` int(11) NOT NULL,
`modified_uid` int(11) NOT NULL,
`created_time` int(11) DEFAULT NULL,
`modified_time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `printer_id`(`printer_id`),
KEY `user_id`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `storage_log`;
CREATE TABLE `storage_log` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`action` varchar(32) NOT NULL DEFAULT '',
`order_id` int(11) NOT NULL,
`box_id` int(11) NOT NULL,
`after_status` varchar(32) NOT NULL DEFAULT '',
`info` varchar(255) NOT NULL DEFAULT '',
`created_uid` int(11) NOT NULL,
`created_time` int(11) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `order_id`(`order_id`),
KEY `action`(`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;