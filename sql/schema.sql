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

DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `owner`;
CREATE TABLE `owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `english_name` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `tell` varchar(255) NOT NULL DEFAULT '',
  `auth_key` varchar(64) NOT NULL DEFAULT '',
  `password_hash` varchar(128) NOT NULL DEFAULT '',
  `password_reset_token` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `role` smallint(6) NOT NULL DEFAULT '10',
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
  `name` varchar(255) NOT NULL DEFAULT '',
  `english_name` varchar(255) NOT NULL DEFAULT '',
  `owner_id` int(11) NOT NULL DEFAULT '0', 
  `project_id` int(11) NOT NULL DEFAULT '0', 
  `desc` text NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
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
  `project_id` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `forecast_quantity` int(11) NOT NULL DEFAULT '1',
  `actual_quantity` int(11) NOT NULL DEFAULT '1',
  `stock_time` datetime NOT NULL,
  `delivery` varchar(64) NOT NULL DEFAULT '',
  `increase` tinyint(4) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
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
  `goods_active` varchar(255) NOT NULL DEFAULT '',
  `storeroom_id` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `to_city` varchar(64) NOT NULL DEFAULT '',
  `recipients` varchar(64) NOT NULL DEFAULT '',
  `recipients_address` varchar(255) NOT NULL DEFAULT '',
  `recipients_contact` varchar(255) NOT NULL DEFAULT '',
  `info` text NOT NULL,
  `limitday` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `is_del` tinyint(4) NOT NULL DEFAULT '0',
  `source` tinyint(4) NOT NULL DEFAULT '0',
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
  `goods_code` varchar(128) NOT NULL DEFAULT '',
  `goods_quantity` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `package`;
CREATE TABLE `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` int(11) NOT NULL,
  `actual_weight` int(11) NOT NULL,
  `throw_weight` int(11) NOT NULL,
  `volume` int(11) NOT NULL,
  `box` varchar(255) NOT NULL DEFAULT '',
  `method` tinyint(4) NOT NULL DEFAULT '1',
  `trunk` varchar(64) NOT NULL DEFAULT '',
  `delivery` varchar(64) NOT NULL DEFAULT '',
  `price` decimal(32,2) NOT NULL DEFAULT '0.00', 
  `info` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

alter table `package` add column height int(11) NOT NULL DEFAULT '0';
alter table `package` add column width int(11) NOT NULL DEFAULT '0';
alter table `package` add column length int(11) NOT NULL DEFAULT '0';

DROP TABLE IF EXISTS `order_package`;
CREATE TABLE `order_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `channel`;
CREATE TABLE `channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connect_number` varchar(64) NOT NULL DEFAULT '',
  `channel_number` varchar(64) NOT NULL DEFAULT '',
  `goods_name` varchar(255) NOT NULL DEFAULT '',
  `goods_quantity` int(11) NOT NULL DEFAULT '0',
  `goods_weight` int(11) NOT NULL DEFAULT '0',
  `goods_volume` int(11) NOT NULL DEFAULT '0',
  `expected_time` datetime NOT NULL,
  `actual_time` datetime NOT NULL,
  `receiver` varchar(64) NOT NULL DEFAULT '',
  `order_receiver` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `packing_details` text,
  `info` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `order_channel`;
CREATE TABLE `order_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connect_number` varchar(64) NOT NULL DEFAULT '',
  `order_id` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `order_channel`;
CREATE TABLE `order_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connect_number` varchar(64) NOT NULL DEFAULT '',
  `order_id` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `trunk`;
CREATE TABLE `trunk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `contact` varchar(64) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE `delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `contact` varchar(64) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `order_sign`;
CREATE TABLE `order_sign` (
  `order_id` int(11) NOT NULL,
  `sign_date` datetime NOT NULL,
  `signer` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `info` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

alter table `stock` add column `destory` int(11) NOT NULL DEFAULT '0';
alter table `stock` add column `destory_reason` blob NOT NULL;
alter table `stock_total` add column `storeroom_id` int(11) NOT NULL DEFAULT '1';
alter table `stock` add column activite varchar(64) NOT NULL DEFAULT '';
alter table `order_sign` add column `type` tinyint(4) NOT NULL DEFAULT '0';
alter table `owner` add column `department` varchar(64) NOT NULL DEFAULT '';
alter table stock add column active varchar(64) NOT NULL DEFAULT '';
alter table package change volume volume varchar(32) NOT NULL DEFAULT '';
alter table manager add column storeroom_id int(11) NOT NULL DEFAULT '1';
alter table `order` add column to_province varchar(32) NOT NULL DEFAULT '';

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

alter table order_detail add column storeroom_id int(11) NOT NULL DEFAULT '1';
alter table material add column property tinyint(4) NOT NULL DEFAULT '0';
alter table material add column channel varchar(16) NOT NULL DEFAULT '';
alter table material add column datasource tinyint(4) NOT NULL DEFAULT '0';
alter table material add column `size` varchar(16) NOT NULL DEFAULT '';
alter table material add column `weight` varchar(8) NOT NULL DEFAULT '';
alter table material add column `stuff` varchar(16) NOT NULL DEFAULT '';

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


alter table material add column pn varchar(64) not null default '';
alter table material add column package varchar(64) not null default '';
alter table material change column `desc` `desc` varchar(255) NOT NULL DEFAULT '';
alter table stock add column warning_quantity int(11) NOT NULL DEFAULT '0';


DROP TABLE IF EXISTS `customer_share`;
CREATE TABLE `customer_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `customer_id` int(10) NOT NULL COMMENT '用户id',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `to_customer_id` int(10) NOT NULL,
  `share_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `owner`;
CREATE TABLE `owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `tell` varchar(255) NOT NULL DEFAULT '',
  `department_id` int(11) NOT NULL COMMENT '部门',
  `category_id` int(11) NOT NULL COMMENT '组别',
  `role` tinyint(4) NOT NULL COMMENT '0 HHG 1:customer',
  `is_budget` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否有预算权限',
  `budget_num` float(10,2) NOT NULL DEFAULT '0.00', 
  `auth_key` varchar(64) NOT NULL DEFAULT '',
  `password_hash` varchar(128) NOT NULL DEFAULT '',
  `password_reset_token` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `product_two_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_line_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `share`;
CREATE TABLE `share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL,
  `storeroom_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `to_customer_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_uid` int(11) NOT NULL DEFAULT '1',
  `modified_uid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `hp_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `province_id` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `shippment_cost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeroom_id` int(11) NOT NULL,
  `transport_type` tinyint(4) NOT NULL,
  `to_city` varchar(32) NOT NULL,
  `city_level` int(11) NOT NULL,
  `fob_weight` int(11) NOT NULL,
  `fob_price` float(10,2) NOT NULL DEFAULT '0.00',
  `increase_weight` int(11) NOT NULL,
  `increase_price` float(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `storeroom_id` (`storeroom_id`),
  KEY `to_city` (`to_city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;






