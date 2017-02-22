-- 主表
CREATE TABLE `$basic_table` (
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  UNIQUE KEY `userid` (`userid`)
) TYPE=MyISAM;
