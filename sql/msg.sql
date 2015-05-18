CREATE TABLE `pm_lists` (
  `plid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `authorid` int(11) unsigned NOT NULL DEFAULT '0',
  `author_type` varchar(16) NOT NULL DEFAULT 'user',
  `min_max` varchar(32) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `lastmessage` text NOT NULL,
  PRIMARY KEY (`plid`),
  KEY `min_max` (`min_max`),
  KEY `authorid` (`authorid`,`dateline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

CREATE TABLE `pm_members` (
  `plid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `user_type` varchar(16) NOT NULL DEFAULT 'user',
  `other_uid` int(11) unsigned NOT NULL DEFAULT '0',
  `other_type` varchar(16) NOT NULL DEFAULT 'buyer',
  `unreadnum` int(10) unsigned NOT NULL DEFAULT '0',
  `pmnum` int(10) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`plid`,`uid`),
  KEY `unreadnum` (`unreadnum`),
  KEY `lastdateline` (`uid`,`status`,`lastdateline`),
  KEY `uid_type` (`uid`,`user_type`,`unreadnum`),
  KEY `lastupdate` (`uid`,`lastupdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `pm_messages`;
CREATE TABLE `pm_messages` (
  `pmid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `plid` int(11) unsigned NOT NULL DEFAULT '0',
  `authorid` int(11) unsigned NOT NULL DEFAULT '0',
  `author_type` varchar(16) NOT NULL DEFAULT 'user',
  `message` text NOT NULL,
  `ext` text NOT NULL DEFAULT '',
  `other_read` tinyint(4) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `other_read` (`other_read`),
  KEY `plid` (`plid`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

