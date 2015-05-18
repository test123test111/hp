DROP TABLE IF EXISTS `manager`;
CREATE TABLE `manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(128) NOT NULL,
  `password_reset_token` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `manager` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `role`, `status`, `created`, `modified`) VALUES
(1, 'admin', 'D179fKcB3pJbImNK4Oy279FszUfU7jbS', '$2y$13$J870q/urL8UWO/LfiW2ym.hwwuUUpOGDAsmWbb/2ChS8lKaLwluQG', '$2y$13$J870q/urL8UWO/LfiW2ym.hwwuUUpOGDAsmWbb/2ChS8lKaLwluQG', 'admin@goumin.com', 10, 10, "2014-12-06 12:10:15", "2014-12-06 12:10:15");

DROP TABLE IF EXISTS `storeroom`;
CREATE TABLE `storeroom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(64) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `owner`;
CREATE TABLE `owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(32) NOT NULL DEFAULT '',
  `tell` varchar(32) NOT NULL DEFAULT '',
  `auth_key` varchar(64) NOT NULL DEFAULT '',
  `password_hash` varchar(128) NOT NULL DEFAULT '',
  `password_reset_token` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `department_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:所属人 1:申请人', 
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `material`;
CREATE TABLE `material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL DEFAULT '',
  `owner_id` int(11) NOT NULL DEFAULT '0', 
  `department_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_line_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `pn` varchar(64) NOT NULL DEFAULT '',
  `datasource` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:导入 1:新增',
  `size` varchar(32) NOT NULL DEFAULT '' COMMENT '规格尺寸',
  `package` varchar(32) NOT NULL DEFAULT '' COMMENT '包装规格',
  `weight` varchar(16) NOT NULL DEFAULT '0' COMMENT '单位重量',
  `image` varchar(1024) NOT NULL DEFAULT '',
  `warning_quantity` int(11) NOT NULL DEFAULT '0',
  `proterty` varchar(64) NOT NULL DEFAULT '',
  `is_share` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:不分享 1:分享',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_line`;
CREATE TABLE `product_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL DEFAULT '0',
  `storeroom_id` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `product_line_id` int(11) NOT NULL,
  `actual_quantity` int(11) NOT NULL DEFAULT '1',
  `add_quantity` int(11) NOT NULL DEFAULT '0',
  `stock_time` datetime NOT NULL,
  `delivery` varchar(64) NOT NULL DEFAULT '',
  `delivery_person` varchar(32) NOT NULL DEFAULT '',
  `delivery_contact` varchar(64) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stock_total`;
CREATE TABLE `stock_total` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL DEFAULT '0',
  `storeroom_id` int(11) NOT NULL,
  `product_line_id` int(11) NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `material_id`(`material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `viewid` varchar(128) NOT NULL DEFAULT '',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `company` varchar(128) NOT NULL,
  `to_city` varchar(64) NOT NULL,
  `recipients` varchar(64) NOT NULL,
  `recipients_address` varchar(255),
  `recipients_contact` varchar(255),
  `insurance` tinyint(4) NOT NULL DEFAULT '0',
  `insurance_fee` float(10,2) NOT NULL DEFAULT '0.00',
  `borrow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '借用',
  `revert` datetime NOT NULL COMMENT '归还时间',
  `limitday` varchar(64) NOT NULL DEFAULT '' COMMENT '运输时效',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `is_del` tinyint(4) NOT NULL DEFAULT '0',
  `purpose` varchar(255) NOT NULL DEFAULT '' COMMENT '用途',
  `info` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=100;

DROP TABLE IF EXISTS `order_detail`;
CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `material_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `storeroom_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0', 
  PRIMARY KEY (`id`),
  KEY `province_id` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `material_id` int(11) NOT NULL DEFAULT '0',
  `storeroom_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `customer_address`;
CREATE TABLE `customer_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `name` varchar(60) NOT NULL COMMENT '姓名',
  `phone` varchar(60) NOT NULL COMMENT '电话',
  `province` varchar(60) NOT NULL COMMENT '省',
  `city` varchar(60) NOT NULL COMMENT '市',
  `area` varchar(60) NOT NULL COMMENT '区',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `zip` varchar(12) NOT NULL COMMENT '邮编',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 1 默认',
  `created` datetime DEFAULT NULL COMMENT '添加时间',
  `tel` varchar(12) NOT NULL DEFAULT '',
  `tel_area_code` varchar(12) NOT NULL DEFAULT '',
  `tel_ext` varchar(12) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
