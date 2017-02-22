SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `kf_anquan_daan`
-- ----------------------------
DROP TABLE IF EXISTS `kf_anquan_daan`;
CREATE TABLE `kf_anquan_daan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned DEFAULT '0',
  `questionid` int(10) unsigned DEFAULT '0',
  `solution` varchar(255) DEFAULT NULL,
  `time` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='密保问题会员答案';

-- ----------------------------
--  Table structure for `kf_anquan_wenti`
-- ----------------------------
DROP TABLE IF EXISTS `kf_anquan_wenti`;
CREATE TABLE `kf_anquan_wenti` (
  `questionid` int(10) unsigned NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `pid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`questionid`)
) TYPE=MyISAM COMMENT='密保问题';

-- ----------------------------
--  Table structure for `kf_bangzhu`
-- ----------------------------
DROP TABLE IF EXISTS `kf_bangzhu`;
CREATE TABLE `kf_bangzhu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `l` char(30) DEFAULT NULL,
  `l_cn` varchar(50) DEFAULT NULL,
  `a` char(30) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` text,
  `v` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_biaoqing`
-- ----------------------------
DROP TABLE IF EXISTS `kf_biaoqing`;
CREATE TABLE `kf_biaoqing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `em` varchar(20) DEFAULT NULL,
  `catid` int(10) unsigned DEFAULT '0',
  `catid_cn` varchar(50) DEFAULT NULL,
  `inputtime` int(10) unsigned DEFAULT '0',
  `listorder` int(10) unsigned DEFAULT '0',
  `is` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_biaoqing_fenlei`
-- ----------------------------
DROP TABLE IF EXISTS `kf_biaoqing_fenlei`;
CREATE TABLE `kf_biaoqing_fenlei` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(30) DEFAULT NULL,
  `listorder` int(10) unsigned DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`catid`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_dingdan`
-- ----------------------------
DROP TABLE IF EXISTS `kf_dingdan`;
CREATE TABLE `kf_dingdan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `touserid` int(10) unsigned DEFAULT '0' COMMENT '卖家ID，0为系统',
  `toadmin` int(10) unsigned DEFAULT '0' COMMENT '配合touseri=0使用；1为后台手动发货，0为自动',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `titleurl` text COMMENT '产品链接',
  `content` mediumtext COMMENT '备注说明',
  `tocontent` text COMMENT '给卖家的留言',
  `type` char(20) DEFAULT NULL COMMENT '类型，作为购买类型区分。如: 附件购买、积分购买、会员购买',
  `status` int(10) unsigned DEFAULT '0' COMMENT '状态：0正常(下单未付款)，1已付款，2已发货，10已收货(交易成功)，99关闭交易',
  `status_type` int(10) unsigned DEFAULT '0' COMMENT '类型，作为购买成功区分。0默认、1为付款立即交易成功、2为发货立即交易成功',
  `status_close` varchar(255) DEFAULT NULL COMMENT '订单关闭原因',
  `price` decimal(8,2) unsigned DEFAULT '0.00' COMMENT '单价',
  `price_type` char(20) DEFAULT 'amount' COMMENT '货币类型: amount金币、point积分',
  `num` int(10) unsigned DEFAULT '0' COMMENT '购买数量',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '购买时间',
  `paytime` int(10) unsigned DEFAULT '0' COMMENT '付款时间',
  `oktime` int(10) unsigned DEFAULT '0' COMMENT '交易成功时间',
  `paysql` mediumtext COMMENT '交易成功后对买家执行sql',
  `paytosql` mediumtext COMMENT '交易成功后对卖家执行sql',
  `payfun` mediumtext COMMENT '交易成功后对买家执行function函数',
  `paytofun` mediumtext COMMENT '交易成功后对卖家执行function函数',
  `setting` text,
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_diy_bbs`
-- ----------------------------
DROP TABLE IF EXISTS `kf_diy_bbs`;
CREATE TABLE `kf_diy_bbs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL,
  `title` char(200) NOT NULL DEFAULT '',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `style` char(24) NOT NULL DEFAULT '',
  `thumb` varchar(600) NOT NULL DEFAULT '',
  `keywords` char(200) NOT NULL DEFAULT '',
  `description` char(255) NOT NULL DEFAULT '',
  `posids` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` char(255) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` varchar(255) NOT NULL DEFAULT '',
  `username` char(20) NOT NULL,
  `reply` int(10) unsigned NOT NULL DEFAULT '0',
  `replytime` int(10) unsigned NOT NULL DEFAULT '0',
  `replyuid` int(10) unsigned NOT NULL DEFAULT '0',
  `replyname` char(20) NOT NULL,
  `read` int(10) unsigned NOT NULL DEFAULT '0',
  `readtime` int(10) unsigned NOT NULL DEFAULT '0',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `jinghua` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dingzhi` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tongzhiwo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `site` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_diy_bbs_data`
-- ----------------------------
DROP TABLE IF EXISTS `kf_diy_bbs_data`;
CREATE TABLE `kf_diy_bbs_data` (
  `id` mediumint(8) unsigned DEFAULT '0',
  `content` text NOT NULL,
  `subcontent` text NOT NULL,
  `downfile` text NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupids_view` varchar(100) NOT NULL,
  `paginationtype` tinyint(1) NOT NULL,
  `pages` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `relation` varchar(255) NOT NULL DEFAULT '',
  `tupian` text NOT NULL,
  `site` int(10) unsigned NOT NULL DEFAULT '1',
  KEY `id` (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_diy_download`
-- ----------------------------
DROP TABLE IF EXISTS `kf_diy_download`;
CREATE TABLE `kf_diy_download` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL,
  `title` char(200) NOT NULL DEFAULT '',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `style` char(24) NOT NULL DEFAULT '',
  `thumb` varchar(600) NOT NULL DEFAULT '',
  `keywords` char(200) NOT NULL DEFAULT '',
  `description` char(255) NOT NULL DEFAULT '',
  `posids` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` char(255) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` varchar(255) NOT NULL DEFAULT '',
  `username` char(20) NOT NULL,
  `reply` int(10) unsigned NOT NULL DEFAULT '0',
  `replytime` int(10) unsigned NOT NULL DEFAULT '0',
  `replyuid` int(10) unsigned NOT NULL DEFAULT '0',
  `replyname` char(20) NOT NULL,
  `read` int(10) unsigned NOT NULL DEFAULT '0',
  `readtime` int(10) unsigned NOT NULL DEFAULT '0',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `jinghua` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dingzhi` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tongzhiwo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `site` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_diy_download_data`
-- ----------------------------
DROP TABLE IF EXISTS `kf_diy_download_data`;
CREATE TABLE `kf_diy_download_data` (
  `id` mediumint(8) unsigned DEFAULT '0',
  `content` text NOT NULL,
  `subcontent` text NOT NULL,
  `downfile` text NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupids_view` varchar(100) NOT NULL,
  `paginationtype` tinyint(1) NOT NULL,
  `pages` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `relation` varchar(255) NOT NULL DEFAULT '',
  `site` int(10) unsigned NOT NULL DEFAULT '0',
  `jietu` text NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_diy_news`
-- ----------------------------
DROP TABLE IF EXISTS `kf_diy_news`;
CREATE TABLE `kf_diy_news` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL,
  `title` char(200) NOT NULL DEFAULT '',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `style` char(24) NOT NULL DEFAULT '',
  `thumb` varchar(600) NOT NULL DEFAULT '',
  `keywords` char(200) NOT NULL DEFAULT '',
  `description` char(255) NOT NULL DEFAULT '',
  `posids` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` char(255) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` varchar(255) NOT NULL DEFAULT '',
  `username` char(20) NOT NULL,
  `reply` int(10) unsigned NOT NULL DEFAULT '0',
  `replytime` int(10) unsigned NOT NULL DEFAULT '0',
  `replyuid` int(10) unsigned NOT NULL DEFAULT '0',
  `replyname` char(20) NOT NULL,
  `read` int(10) unsigned NOT NULL DEFAULT '0',
  `readtime` int(10) unsigned NOT NULL DEFAULT '0',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `jinghua` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dingzhi` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tongzhiwo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `site` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_diy_news_data`
-- ----------------------------
DROP TABLE IF EXISTS `kf_diy_news_data`;
CREATE TABLE `kf_diy_news_data` (
  `id` mediumint(8) unsigned DEFAULT '0',
  `content` text NOT NULL,
  `subcontent` text NOT NULL,
  `downfile` text NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupids_view` varchar(100) NOT NULL,
  `paginationtype` tinyint(1) NOT NULL,
  `pages` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `relation` varchar(255) NOT NULL DEFAULT '',
  `site` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_diy_picture`
-- ----------------------------
DROP TABLE IF EXISTS `kf_diy_picture`;
CREATE TABLE `kf_diy_picture` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL,
  `title` char(200) NOT NULL DEFAULT '',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `style` char(24) NOT NULL DEFAULT '',
  `thumb` varchar(600) NOT NULL DEFAULT '',
  `keywords` char(200) NOT NULL DEFAULT '',
  `description` char(255) NOT NULL DEFAULT '',
  `posids` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` char(255) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` varchar(255) NOT NULL DEFAULT '',
  `username` char(20) NOT NULL,
  `reply` int(10) unsigned NOT NULL DEFAULT '0',
  `replytime` int(10) unsigned NOT NULL DEFAULT '0',
  `replyuid` int(10) unsigned NOT NULL DEFAULT '0',
  `replyname` char(20) NOT NULL,
  `read` int(10) unsigned NOT NULL DEFAULT '0',
  `readtime` int(10) unsigned NOT NULL DEFAULT '0',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `jinghua` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dingzhi` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tongzhiwo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `site` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_diy_picture_data`
-- ----------------------------
DROP TABLE IF EXISTS `kf_diy_picture_data`;
CREATE TABLE `kf_diy_picture_data` (
  `id` mediumint(8) unsigned DEFAULT '0',
  `content` text NOT NULL,
  `subcontent` text NOT NULL,
  `downfile` text NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupids_view` varchar(100) NOT NULL,
  `paginationtype` tinyint(1) NOT NULL,
  `pages` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `relation` varchar(255) NOT NULL DEFAULT '',
  `site` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_guanggao`
-- ----------------------------
DROP TABLE IF EXISTS `kf_guanggao`;
CREATE TABLE `kf_guanggao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned DEFAULT NULL,
  `cattype` varchar(10) DEFAULT NULL,
  `catid_cn` varchar(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `setting` text,
  `startdate` int(10) unsigned DEFAULT NULL,
  `enddate` int(10) unsigned DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  `hits` int(10) unsigned DEFAULT NULL,
  `clicks` int(10) DEFAULT NULL,
  `clickstime` int(10) unsigned DEFAULT NULL,
  `listorder` int(10) DEFAULT NULL,
  `disabled` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_guanggao_data`
-- ----------------------------
DROP TABLE IF EXISTS `kf_guanggao_data`;
CREATE TABLE `kf_guanggao_data` (
  `dataid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned DEFAULT '0' COMMENT '广告ID',
  `catid` int(10) unsigned DEFAULT '0' COMMENT '广告分类ID',
  `ip` char(15) NOT NULL DEFAULT '0',
  `userid` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `username` varchar(255) DEFAULT '' COMMENT '会员名称',
  `inputtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`dataid`)
) TYPE=MyISAM COMMENT='广告系统点击记录';

-- ----------------------------
--  Table structure for `kf_guanggao_fenlei`
-- ----------------------------
DROP TABLE IF EXISTS `kf_guanggao_fenlei`;
CREATE TABLE `kf_guanggao_fenlei` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `setting` text,
  `description` varchar(255) DEFAULT NULL,
  `items` int(10) unsigned DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`catid`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_guanliyuan`
-- ----------------------------
DROP TABLE IF EXISTS `kf_guanliyuan`;
CREATE TABLE `kf_guanliyuan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rank` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `pass` varchar(50) DEFAULT NULL,
  `purview` text,
  `allow` varchar(50) DEFAULT NULL,
  `lasttime` int(10) unsigned DEFAULT NULL,
  `lastip` varchar(50) DEFAULT NULL,
  `nowtime` int(10) unsigned DEFAULT NULL,
  `nowip` varchar(50) DEFAULT NULL,
  `logins` int(10) unsigned DEFAULT '0',
  `admin` int(10) unsigned DEFAULT '0',
  `email` varchar(50) DEFAULT NULL,
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_guanliyuan_rizhi`
-- ----------------------------
DROP TABLE IF EXISTS `kf_guanliyuan_rizhi`;
CREATE TABLE `kf_guanliyuan_rizhi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `time` int(10) unsigned DEFAULT '0',
  `ip` varchar(20) DEFAULT NULL,
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_huiyuan`
-- ----------------------------
DROP TABLE IF EXISTS `kf_huiyuan`;
CREATE TABLE `kf_huiyuan` (
  `userid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `usersid` char(24) DEFAULT NULL,
  `ucuserid` mediumint(8) unsigned DEFAULT '0',
  `phpssouid` mediumint(8) unsigned NOT NULL,
  `username` char(20) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `passwordpay` char(32) NOT NULL COMMENT '支付密码',
  `payerr` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付密码输入错误次数',
  `payerrtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付密码最后输入错误时间',
  `avatar` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `encrypt` char(6) NOT NULL,
  `nickname` char(20) NOT NULL,
  `colorname` varchar(10) NOT NULL COMMENT '用户名着色',
  `boldname` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户名加粗',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  `indate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '刷新在线时间',
  `indateh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '刷新会员组、vip等信息',
  `regip` char(15) NOT NULL DEFAULT '',
  `lastip` char(15) NOT NULL DEFAULT '',
  `loginnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `email` char(32) NOT NULL DEFAULT '',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `areaid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `point` smallint(5) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `message` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overduedate` int(10) unsigned NOT NULL DEFAULT '0',
  `connectid` char(40) NOT NULL DEFAULT '',
  `from` char(10) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `qianming` text,
  `regtype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `site` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`(20)),
  KEY `phpssouid` (`phpssouid`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_huiyuan_diy_detail`
-- ----------------------------
DROP TABLE IF EXISTS `kf_huiyuan_diy_detail`;
CREATE TABLE `kf_huiyuan_diy_detail` (
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  UNIQUE KEY `userid` (`userid`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_huiyuan_moxing`
-- ----------------------------
DROP TABLE IF EXISTS `kf_huiyuan_moxing`;
CREATE TABLE `kf_huiyuan_moxing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `body` text,
  `tablename` varchar(50) DEFAULT NULL,
  `mode` int(10) unsigned DEFAULT '0',
  `num` int(10) unsigned DEFAULT '0',
  `default_style` char(30) DEFAULT NULL,
  `category_template` char(30) DEFAULT NULL,
  `list_template` char(30) DEFAULT NULL,
  `show_template` char(30) DEFAULT NULL,
  `addtime` int(10) DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_huiyuan_moxing_ziduan`
-- ----------------------------
DROP TABLE IF EXISTS `kf_huiyuan_moxing_ziduan`;
CREATE TABLE `kf_huiyuan_moxing_ziduan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` int(10) unsigned DEFAULT '0',
  `type` varchar(20) DEFAULT NULL,
  `status` int(10) unsigned DEFAULT '0' COMMENT '0表示正常，1表示禁用，2表示不允许修改状态',
  `issystem` int(10) unsigned DEFAULT '0' COMMENT '0为副表数据，1为主表数据',
  `field` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `tips` varchar(200) DEFAULT NULL,
  `setting` text,
  `minlength` int(10) unsigned DEFAULT '0',
  `maxlength` int(10) unsigned DEFAULT '0',
  `del` int(10) unsigned DEFAULT '0' COMMENT '0正常，1表示不可删除',
  `hide` int(10) unsigned DEFAULT '0' COMMENT '0正常字段，1隐藏字段',
  `listorder` int(10) unsigned DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_huiyuan_shenhe`
-- ----------------------------
DROP TABLE IF EXISTS `kf_huiyuan_shenhe`;
CREATE TABLE `kf_huiyuan_shenhe` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ucuserid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` char(20) NOT NULL,
  `password` char(32) NOT NULL,
  `encrypt` char(6) NOT NULL,
  `nickname` char(20) NOT NULL,
  `regdate` int(10) unsigned NOT NULL,
  `regip` char(15) NOT NULL,
  `email` char(32) NOT NULL,
  `modelid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `point` smallint(5) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `modelinfo` char(255) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message` char(100) DEFAULT NULL,
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  `mobile` char(11) NOT NULL DEFAULT '',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email_encrypt` varchar(24) DEFAULT NULL COMMENT '邮箱认证',
  `email_encrypt_time` int(10) unsigned DEFAULT '0',
  `site` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`userid`),
  KEY `email` (`email`(20)),
  KEY `username` (`username`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_huiyuan_vip`
-- ----------------------------
DROP TABLE IF EXISTS `kf_huiyuan_vip`;
CREATE TABLE `kf_huiyuan_vip` (
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  UNIQUE KEY `userid` (`userid`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_huiyuan_zu`
-- ----------------------------
DROP TABLE IF EXISTS `kf_huiyuan_zu`;
CREATE TABLE `kf_huiyuan_zu` (
  `groupid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(15) NOT NULL,
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `starnum` tinyint(2) unsigned NOT NULL,
  `point` smallint(6) unsigned NOT NULL,
  `allowmessage` smallint(5) unsigned NOT NULL DEFAULT '0',
  `allowvisit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowpost` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowpostverify` tinyint(1) unsigned NOT NULL,
  `allowsearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowupgrade` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allowsendmessage` tinyint(1) unsigned NOT NULL,
  `allowpostnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `allowattachment` tinyint(1) NOT NULL,
  `price_y` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `price_m` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `price_d` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `icon` varchar(255) NOT NULL,
  `usernamecolor` char(7) NOT NULL,
  `description` char(100) NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `qianmingubb` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `qianminghtml` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `qianmingubbnum` int(10) unsigned DEFAULT '0',
  `qianminglength` int(10) unsigned DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`groupid`),
  KEY `disabled` (`disabled`),
  KEY `listorder` (`sort`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_jiangfa`
-- ----------------------------
DROP TABLE IF EXISTS `kf_jiangfa`;
CREATE TABLE `kf_jiangfa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned DEFAULT '0',
  `type` varchar(20) DEFAULT NULL COMMENT '0积分，1金币',
  `add` char(3) DEFAULT 'add' COMMENT 'add增加，cut减少',
  `title` varchar(255) DEFAULT NULL,
  `num` varchar(10) DEFAULT '0',
  `nums` varchar(20) DEFAULT '20' COMMENT '当时余额',
  `time` int(10) unsigned DEFAULT '0',
  `ip` char(15) DEFAULT NULL,
  `del` tinyint(3) unsigned DEFAULT '0' COMMENT '1为已删除',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) TYPE=MyISAM COMMENT='金币、积分减少增加日志';

-- ----------------------------
--  Table structure for `kf_lianjie`
-- ----------------------------
DROP TABLE IF EXISTS `kf_lianjie`;
CREATE TABLE `kf_lianjie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(20) DEFAULT NULL,
  `titlej` char(4) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `userid` int(10) unsigned DEFAULT '0',
  `catid` int(10) unsigned DEFAULT '0',
  `catid_cn` varchar(50) DEFAULT NULL,
  `type` int(10) unsigned DEFAULT '0' COMMENT '0审核中、1正常、2黑名单',
  `content` text,
  `inputtime` int(10) unsigned DEFAULT '0',
  `read` int(10) unsigned DEFAULT '0' COMMENT '访问次数(出站)',
  `readip` char(15) DEFAULT NULL COMMENT '最后访问的IP(出站)',
  `readtime` int(10) unsigned DEFAULT '0' COMMENT '最后访问的时间(出站)',
  `from` int(10) unsigned DEFAULT '0' COMMENT '访问次数(进站)',
  `fromip` char(15) DEFAULT NULL COMMENT '最后访问的IP(进站)',
  `fromtime` int(10) unsigned DEFAULT '0' COMMENT '最后访问的时间(进站)',
  `fromnum` int(10) unsigned DEFAULT '0' COMMENT '进站多少次自动通过审核',
  `zhichi` int(10) unsigned DEFAULT '0',
  `buzhichi` int(10) unsigned DEFAULT '0',
  `listorder` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='友情链接';

-- ----------------------------
--  Table structure for `kf_lianjie_data`
-- ----------------------------
DROP TABLE IF EXISTS `kf_lianjie_data`;
CREATE TABLE `kf_lianjie_data` (
  `dataid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned DEFAULT '0' COMMENT '友链ID',
  `catid` int(10) unsigned DEFAULT '0' COMMENT '友链分类ID',
  `type` int(10) unsigned DEFAULT '0' COMMENT '0出站、1进站',
  `ip` varchar(15) DEFAULT NULL,
  `userid` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `username` varchar(255) DEFAULT NULL COMMENT '会员名称',
  `inputtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`dataid`)
) TYPE=MyISAM COMMENT='友情链接进出记录';

-- ----------------------------
--  Table structure for `kf_lianjie_fenlei`
-- ----------------------------
DROP TABLE IF EXISTS `kf_lianjie_fenlei`;
CREATE TABLE `kf_lianjie_fenlei` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned DEFAULT '0' COMMENT '0申请需要审核、1不需要审核、2有流量进入通过审核',
  `type_num` int(10) unsigned DEFAULT '1',
  `islink` tinyint(3) unsigned DEFAULT '0',
  `title` char(30) DEFAULT NULL,
  `listorder` int(10) unsigned DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`catid`)
) TYPE=MyISAM COMMENT='友情链接分类';

-- ----------------------------
--  Table structure for `kf_neirong_fabu`
-- ----------------------------
DROP TABLE IF EXISTS `kf_neirong_fabu`;
CREATE TABLE `kf_neirong_fabu` (
  `checkid` char(15) NOT NULL,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` char(80) NOT NULL,
  `username` char(20) NOT NULL,
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `checktime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后审核时间',
  `yitongguo` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '1已经审核过通过，避免重复奖罚',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  KEY `username` (`username`),
  KEY `checkid` (`checkid`),
  KEY `status` (`status`,`inputtime`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_neirong_fujian`
-- ----------------------------
DROP TABLE IF EXISTS `kf_neirong_fujian`;
CREATE TABLE `kf_neirong_fujian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commentid` char(30) DEFAULT NULL,
  `modelid` int(10) unsigned DEFAULT '0',
  `of` int(10) unsigned DEFAULT '1' COMMENT '1为使用中，0未使用',
  `field` varchar(50) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `body` text,
  `url` varchar(255) DEFAULT NULL,
  `allurl` varchar(255) DEFAULT NULL,
  `size` int(10) DEFAULT '0',
  `format` char(5) DEFAULT NULL,
  `down` int(10) DEFAULT '0',
  `addtime` int(10) DEFAULT NULL,
  `addip` varchar(15) DEFAULT NULL,
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_neirong_lanmu`
-- ----------------------------
DROP TABLE IF EXISTS `kf_neirong_lanmu`;
CREATE TABLE `kf_neirong_lanmu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned DEFAULT '0',
  `title` varchar(200) DEFAULT NULL,
  `module` varchar(200) DEFAULT NULL,
  `module_cn` varchar(200) DEFAULT NULL,
  `modelid` smallint(5) unsigned DEFAULT '0',
  `parentid` int(10) unsigned DEFAULT '0' COMMENT '上级栏目',
  `arrparentid` varchar(255) DEFAULT NULL COMMENT '上级栏目(全部)',
  `arrchildid` mediumtext COMMENT '子级栏目(全部)',
  `url` varchar(200) DEFAULT NULL,
  `body` mediumtext,
  `items` mediumint(8) unsigned DEFAULT '0',
  `setting` mediumtext,
  `visit` text,
  `listorder` smallint(5) unsigned DEFAULT '0',
  `letter` varchar(50) DEFAULT NULL,
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_neirong_lanmu_quanxian`
-- ----------------------------
DROP TABLE IF EXISTS `kf_neirong_lanmu_quanxian`;
CREATE TABLE `kf_neirong_lanmu_quanxian` (
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0',
  `roleid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action` char(30) NOT NULL,
  KEY `catid` (`catid`,`roleid`,`is`,`action`),
  KEY `siteid` (`site`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_neirong_moxing`
-- ----------------------------
DROP TABLE IF EXISTS `kf_neirong_moxing`;
CREATE TABLE `kf_neirong_moxing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` char(10) DEFAULT 'new' COMMENT '模块类型，如bbs为论坛模型',
  `type_cn` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `body` text,
  `tablename` varchar(50) DEFAULT NULL,
  `mode` int(10) unsigned DEFAULT '0',
  `num` int(10) unsigned DEFAULT '0',
  `default_style` char(30) DEFAULT NULL,
  `category_template` char(30) DEFAULT NULL,
  `list_template` char(30) DEFAULT NULL,
  `show_template` char(30) DEFAULT NULL,
  `addtime` int(10) DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_neirong_moxing_ziduan`
-- ----------------------------
DROP TABLE IF EXISTS `kf_neirong_moxing_ziduan`;
CREATE TABLE `kf_neirong_moxing_ziduan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` int(10) unsigned DEFAULT '0',
  `type` varchar(20) DEFAULT NULL,
  `status` int(10) unsigned DEFAULT '0' COMMENT '0表示正常，1表示禁用，2表示不允许修改状态',
  `issystem` int(10) unsigned DEFAULT '0' COMMENT '0为副表数据，1为主表数据',
  `field` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `tips` varchar(200) DEFAULT NULL,
  `setting` text,
  `minlength` int(10) unsigned DEFAULT '0',
  `maxlength` int(10) unsigned DEFAULT '0',
  `del` int(10) unsigned DEFAULT '0' COMMENT '0正常，1表示不可删除',
  `hide` int(10) unsigned DEFAULT '0' COMMENT '0正常字段，1隐藏字段',
  `isadd` int(10) unsigned DEFAULT '0' COMMENT '前台允许发布： 0不允许，1允许',
  `listorder` int(10) unsigned DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_neirong_zhifu`
-- ----------------------------
DROP TABLE IF EXISTS `kf_neirong_zhifu`;
CREATE TABLE `kf_neirong_zhifu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned DEFAULT NULL,
  `commentid` char(30) DEFAULT NULL,
  `time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='阅读支付';

-- ----------------------------
--  Table structure for `kf_paiban`
-- ----------------------------
DROP TABLE IF EXISTS `kf_paiban`;
CREATE TABLE `kf_paiban` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) unsigned DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  `type_en` varchar(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `body` mediumtext,
  `qianmian` varchar(500) DEFAULT NULL,
  `houmian` varchar(500) DEFAULT NULL,
  `order` int(10) unsigned DEFAULT NULL COMMENT '0',
  `del` int(10) unsigned DEFAULT '0',
  `hide` int(10) unsigned DEFAULT '0',
  `addtime` int(10) unsigned DEFAULT NULL,
  `addip` varchar(50) DEFAULT NULL,
  `lasttime` int(10) DEFAULT NULL,
  `lastadmin` varchar(50) DEFAULT NULL,
  `adminuser` varchar(50) DEFAULT NULL,
  `wap` char(30) DEFAULT '0' COMMENT '显示版本：0所有，1简版，2彩版，3触屏版，4平板，5电脑版',
  `nocache` tinyint(3) unsigned DEFAULT '0' COMMENT '禁用缓存 是:0,否:1',
  `islogin` tinyint(3) unsigned DEFAULT '0' COMMENT '是否登录可见：0默认，1登录可见, 2仅未登录可见',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_peizhi`
-- ----------------------------
DROP TABLE IF EXISTS `kf_peizhi`;
CREATE TABLE `kf_peizhi` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_peizhi_mokuai`
-- ----------------------------
DROP TABLE IF EXISTS `kf_peizhi_mokuai`;
CREATE TABLE `kf_peizhi_mokuai` (
  `module` varchar(15) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `iscore` tinyint(1) unsigned DEFAULT '0',
  `version` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `setting` mediumtext,
  `listorder` tinyint(3) unsigned DEFAULT '0',
  `disabled` tinyint(1) unsigned DEFAULT '0',
  `installdate` date DEFAULT '0000-00-00',
  `updatedate` date DEFAULT '0000-00-00',
  PRIMARY KEY (`module`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_peizhi_mokuai_url`
-- ----------------------------
DROP TABLE IF EXISTS `kf_peizhi_mokuai_url`;
CREATE TABLE `kf_peizhi_mokuai_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(15) DEFAULT NULL,
  `urlid` int(10) unsigned DEFAULT '0',
  `urltitle` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='模块前台包含链接';

-- ----------------------------
--  Table structure for `kf_pinglun`
-- ----------------------------
DROP TABLE IF EXISTS `kf_pinglun`;
CREATE TABLE `kf_pinglun` (
  `commentid` char(30) NOT NULL,
  `title` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `total` int(8) unsigned DEFAULT '0',
  `square` mediumint(8) unsigned DEFAULT '0',
  `anti` mediumint(8) unsigned DEFAULT '0',
  `neutral` mediumint(8) unsigned DEFAULT '0',
  `lastupdate` int(10) unsigned DEFAULT '0',
  `site` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`commentid`),
  KEY `lastupdate` (`lastupdate`),
  KEY `siteid` (`site`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_pinglun_data_1`
-- ----------------------------
DROP TABLE IF EXISTS `kf_pinglun_data_1`;
CREATE TABLE `kf_pinglun_data_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `commentid` char(30) NOT NULL DEFAULT '' COMMENT '评论ID号',
  `userid` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `username` varchar(20) DEFAULT NULL COMMENT '用户名',
  `creat_at` int(10) DEFAULT NULL COMMENT '发布时间',
  `ip` varchar(15) DEFAULT NULL COMMENT '用户IP地址',
  `status` tinyint(1) DEFAULT '0' COMMENT '评论状态{0:未审核,-1:未通过审核,1:通过审核}',
  `content` text COMMENT '评论内容',
  `direction` tinyint(1) DEFAULT '0' COMMENT '评论方向{0:无方向,1:正文,2:反方,3:中立}',
  `support` mediumint(8) unsigned DEFAULT '0' COMMENT '支持数',
  `reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为回复',
  `is_huifu` int(10) unsigned DEFAULT '0',
  `site` int(10) NOT NULL DEFAULT '0' COMMENT '站点ID',
  PRIMARY KEY (`id`),
  KEY `commentid` (`commentid`),
  KEY `direction` (`direction`),
  KEY `siteid` (`site`),
  KEY `support` (`support`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_shoucang`
-- ----------------------------
DROP TABLE IF EXISTS `kf_shoucang`;
CREATE TABLE `kf_shoucang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` char(100) NOT NULL,
  `url` char(100) NOT NULL,
  `adddate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_sousuo`
-- ----------------------------
DROP TABLE IF EXISTS `kf_sousuo`;
CREATE TABLE `kf_sousuo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` int(10) unsigned DEFAULT NULL,
  `catid` int(10) unsigned DEFAULT NULL,
  `typeid` char(15) DEFAULT 'neirong',
  `contentid` int(10) unsigned DEFAULT NULL,
  `searchnums` int(10) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `data` text,
  `data_pinyin` text,
  `adddate` int(10) unsigned DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `description` varchar(255) DEFAULT NULL,
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_sousuo_key`
-- ----------------------------
DROP TABLE IF EXISTS `kf_sousuo_key`;
CREATE TABLE `kf_sousuo_key` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `searchnums` int(10) unsigned DEFAULT '0',
  `uptime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_tongji`
-- ----------------------------
DROP TABLE IF EXISTS `kf_tongji`;
CREATE TABLE `kf_tongji` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) DEFAULT NULL,
  `username` char(20) DEFAULT NULL,
  `type` char(10) DEFAULT NULL,
  `get` varchar(255) DEFAULT NULL,
  `get_md5` char(16) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `session` char(8) DEFAULT NULL,
  `ip` char(15) DEFAULT NULL,
  `ip2` char(11) DEFAULT NULL,
  `time` int(10) unsigned DEFAULT '0',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_xinqing`
-- ----------------------------
DROP TABLE IF EXISTS `kf_xinqing`;
CREATE TABLE `kf_xinqing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `contentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章id',
  `total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总数',
  `n1` int(10) unsigned NOT NULL DEFAULT '0',
  `n2` int(10) unsigned NOT NULL DEFAULT '0',
  `n3` int(10) unsigned NOT NULL DEFAULT '0',
  `n4` int(10) unsigned NOT NULL DEFAULT '0',
  `n5` int(10) unsigned NOT NULL DEFAULT '0',
  `n6` int(10) unsigned NOT NULL DEFAULT '0',
  `n7` int(10) unsigned NOT NULL DEFAULT '0',
  `n8` int(10) unsigned NOT NULL DEFAULT '0',
  `n9` int(10) unsigned NOT NULL DEFAULT '0',
  `n10` int(10) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `site` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '站点ID',
  PRIMARY KEY (`id`),
  KEY `total` (`total`),
  KEY `lastupdate` (`lastupdate`),
  KEY `catid` (`catid`,`site`,`contentid`)
) TYPE=MyISAM COMMENT='内容心情记录';

-- ----------------------------
--  Table structure for `kf_xinxi`
-- ----------------------------
DROP TABLE IF EXISTS `kf_xinxi`;
CREATE TABLE `kf_xinxi` (
  `messageid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `send_from_id` char(30) NOT NULL DEFAULT '0' COMMENT '发件人',
  `send_to_id` char(30) NOT NULL DEFAULT '0' COMMENT '收件人',
  `folder` enum('all','inbox','outbox') NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message_time` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` char(80) DEFAULT NULL,
  `content` text NOT NULL,
  `replyid` int(10) unsigned NOT NULL DEFAULT '0',
  `pmid` int(10) unsigned DEFAULT '0',
  `plid` int(10) unsigned DEFAULT '0',
  `authorid` int(10) unsigned DEFAULT '0',
  `del_type` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`messageid`),
  KEY `msgtoid` (`send_to_id`,`folder`),
  KEY `replyid` (`replyid`),
  KEY `folder` (`send_from_id`,`folder`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_xinxi_data`
-- ----------------------------
DROP TABLE IF EXISTS `kf_xinxi_data`;
CREATE TABLE `kf_xinxi_data` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) NOT NULL,
  `group_message_id` int(5) NOT NULL,
  `retime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `message` (`userid`,`group_message_id`)
) TYPE=MyISAM COMMENT='会员接收系统信息情况';

-- ----------------------------
--  Table structure for `kf_xinxi_xitong`
-- ----------------------------
DROP TABLE IF EXISTS `kf_xinxi_xitong`;
CREATE TABLE `kf_xinxi_xitong` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupid` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
  `groupid_cn` varchar(255) DEFAULT NULL,
  `subject` char(80) DEFAULT NULL,
  `content` text NOT NULL COMMENT '内容',
  `inputtime` int(10) unsigned DEFAULT '0',
  `status` tinyint(2) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='系统信息列表';

-- ----------------------------
--  Table structure for `kf_xitongrizhi`
-- ----------------------------
DROP TABLE IF EXISTS `kf_xitongrizhi`;
CREATE TABLE `kf_xitongrizhi` (
  `l_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `l_type` tinyint(1) unsigned NOT NULL,
  `l_type_name` varchar(30) NOT NULL,
  `l_time` int(10) unsigned NOT NULL,
  `l_ip` varchar(20) NOT NULL,
  `l_page` text NOT NULL,
  `l_str` text NOT NULL,
  PRIMARY KEY (`l_id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_yanzhengma`
-- ----------------------------
DROP TABLE IF EXISTS `kf_yanzhengma`;
CREATE TABLE `kf_yanzhengma` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `captcha` char(6) DEFAULT NULL,
  `code` char(20) DEFAULT NULL,
  `time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_youxiang`
-- ----------------------------
DROP TABLE IF EXISTS `kf_youxiang`;
CREATE TABLE `kf_youxiang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `body` mediumtext,
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_zhandian`
-- ----------------------------
DROP TABLE IF EXISTS `kf_zhandian`;
CREATE TABLE `kf_zhandian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `site` int(10) DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Table structure for `kf_zhifu`
-- ----------------------------
DROP TABLE IF EXISTS `kf_zhifu`;
CREATE TABLE `kf_zhifu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL COMMENT '支付方式（插件名称、不可修改）',
  `path` varchar(20) DEFAULT NULL COMMENT '系统路径',
  `title` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `setting` text COMMENT '配置',
  `setting_field` text COMMENT '配置字段',
  `rate` int(10) unsigned DEFAULT '0' COMMENT '手续费',
  `in` int(10) unsigned DEFAULT '0' COMMENT '1是在线支付，0不是',
  `open` int(11) unsigned DEFAULT '1' COMMENT '1启用 0停用',
  `pid` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `v` varchar(10) DEFAULT NULL COMMENT '版本',
  `key` varchar(255) DEFAULT NULL COMMENT '选用字段，商户密匙',
  `syscontent` text COMMENT '系统备注',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`path`)
) TYPE=MyISAM COMMENT='支付方式';

-- ----------------------------
-- Table structure for kf_yingyong
-- ----------------------------
DROP TABLE IF EXISTS `kf_yingyong`;
CREATE TABLE `kf_yingyong` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `v` int(10) unsigned DEFAULT '1',
  `intime` int(10) DEFAULT NULL,
  `uptime` int(10) DEFAULT NULL,
  `setting` mediumtext,
  `appinfo` text,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
-- Table structure for kf_yingyong_back
-- ----------------------------
DROP TABLE IF EXISTS `kf_yingyong_back`;
CREATE TABLE `kf_yingyong_back` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `v` int(10) unsigned DEFAULT '0',
  `urlsn` varchar(50) DEFAULT NULL,
  `indate` bigint(18) DEFAULT NULL,
  `setting` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for kf_neirong_data
-- ----------------------------
DROP TABLE IF EXISTS `kf_neirong_data`;
CREATE TABLE `kf_neirong_data` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT NULL,
  `userid` int(10) unsigned DEFAULT '0',
  `dataid` int(10) unsigned DEFAULT '0',
  `dataid2` int(10) unsigned DEFAULT '0',
  `intime` int(10) unsigned DEFAULT '0',
  `setting` mediumtext,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
-- Table structure for kf_huiyuan_xunzhang
-- ----------------------------
DROP TABLE IF EXISTS `kf_huiyuan_xunzhang`;
CREATE TABLE `kf_huiyuan_xunzhang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned DEFAULT '0',
  `dataid` int(10) unsigned DEFAULT '0',
  `dataid2` int(10) unsigned DEFAULT '0',
  `type` varchar(10) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `intime` int(10) unsigned DEFAULT '0',
  `setting` mediumtext,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
-- Table structure for kf_neirong_guanggao
-- ----------------------------
DROP TABLE IF EXISTS `kf_neirong_guanggao`;
CREATE TABLE `kf_neirong_guanggao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `stype` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT '通用' COMMENT '0通用，1内容广告，2评论广告',
  `order` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `islogin` tinyint(3) unsigned DEFAULT '0' COMMENT '是否登录可见：0默认，1登录可见, 2仅未登录可见',
  `wap` tinyint(3) unsigned DEFAULT '0' COMMENT '显示版本：0所有，1简版，2彩版，3触屏版，4平板，5电脑版',
  `site` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `kf_anquan_wenti` VALUES ('1001','您母亲的姓名是？','1'), ('1002','您配偶的生日是？','2'), ('1003','您的学号（或工号）是？','3'), ('1004','您母亲的生日是？','4'), ('1005','您高中班主任的名字是？','5'), ('1006','您父亲的姓名是？','6'), ('1007','您小学班主任的名字是？','7'), ('1008','您父亲的生日是？','8'), ('1009','您配偶的姓名是？','9'), ('1010','您初中班主任的名字是？','10'), ('1011','您最熟悉的童年好友名字是？','11'), ('1012','您最熟悉的学校宿舍室友名字是？','12'), ('1013','对您影响最大的人名字是？','13');
INSERT INTO `kf_bangzhu` VALUES ('1','paiban','排版设计','nrliebiao','内容列表','\r\n1、绑定栏目:指定栏目的内容\r\n---\r\n2、显示数量:最多50条\r\n---\r\n3、截取标题:0不截取\r\n---\r\n4、填补字符:标题超过截取长度用此填补\r\n---\r\n5、排序方式:根据选择进行顺序排列\r\n---\r\n6、升序降序:降序从大(新)~小(旧)，升序反之\r\n---\r\n7、内容筛选:根据指定条件调出列表\r\n---\r\n8、自写模板:\r\n　{id}为编号\r\n　{url}为链接\r\n　{title}为标题\r\n　{title,5}为截取长度5的标题(5可改)\r\n　{title,5,...}为截取长度5超过截取长度用...填补的标题(5和...可改)\r\n　{thumb}为缩略图-原图\r\n　{thumbsmall}为缩略图-小图\r\n　{thumbbig}为缩略图-大图\r\n　{description}为内容摘要\r\n　{description,10}为截取长度10的摘要(10可改)\r\n　{description,10,...}为截取长度10超过截取长度用...填补的摘要(10和...可改)\r\n　{addtime}为发布时间\r\n　{uptime}为更新时间\r\n　{reply}为回复数量\r\n　{read}为阅读数量\r\n　{murl}为栏目链接地址\r\n　{mtitle}为栏目名称\r\n　{mtitle,2}为截取长度2的栏目名称(2可改)\r\n　{userid}为用户ID\r\n　{username}为会员用户名\r\n　{nickname}为用户昵称\r\n　{colorname}为用户昵称(含:颜色、勋章)\r\n　{onlycolorname}为用户昵称(含:颜色)\r\n　{n}为序号\r\n　{wap=A}XXX{/wap}版本显示功能(请查阅后台>帮助手册>WML标签对此标签的说明)\r\n　注*:{addtime}和{uptime}支持date函数方式,如:{addtime(Y-m-d H:i:s)}\r\n　例如①:{n}.<a href=\"{url}\">{title}</a><br/>\r\n　例如②:<img src=\"{thumbsmall}\" /><a href=\"{url}\">{title}</a><br/>\r\n　例如③:<a href=\"{url}\">{title}</a>[{addtime(Y-m-d)}]<br/>',''), ('2','paiban','排版设计','nrlanmu','内容栏目','\r\n1、绑定栏目:指定栏目的内容\r\n---\r\n2、调用子栏目:如果是则调用出绑定栏目的子栏目列表\r\n---\r\n3、截取标题:0不截取\r\n---\r\n4、填补字符:标题超过截取长度用此填补\r\n---\r\n5、自写模板:\r\n　{id}为编号\r\n　{url}为链接\r\n　{title}为标题\r\n　{title,5}为截取长度5的标题(5可改)\r\n　{title,5,...}为截取长度5超过截取长度用...填补的标题(5和...可改)\r\n　{n}为序号\r\n　{wap=A}XXX{/wap}版本显示功能(请查阅后台>帮助手册>WML标签对此标签的说明)\r\n　例如①:{n}.<a href=\"{url}\">{title}</a><br/>',''), ('3','paiban','排版设计','wap','显示版本','\r\n0所有显示;\r\n1简版;\r\n2彩版;\r\n3触屏版;\r\n4平板;\r\n5电脑版。\r\n注明:若要两个或以上的版本显示则把它们的用半角逗号“,”连起来;\r\n例如:\r\n　　需要简版和彩版显示应填写“1,2”(不含双引号);\r\n　　需要触屏版和平板显示应填写“3,4”(不含双引号);\r\n　　需要电脑版单独显示应填写“5”(不含双引号)。',''), ('4','paiban','排版设计','beta','页面切换','\r\n1、切换版本:显示想要切换的浏览版本\r\n　1简版;\r\n　2彩版;\r\n　3触屏版;\r\n　4平板;\r\n　5电脑版。\r\n注明:若要两个或以上的版本显示则把它们的用半角逗号“,”连起来;\r\n　　可根据填写顺序调节显示的循序,如：“2,3,1”\r\n例如:\r\n　　需要显示切换到简版和彩版显示应填写“1,2”(不含双引号);\r\n　　需要显示切换到触屏版和平板显示应填写“3,4”(不含双引号);\r\n　　需要显示切换到电脑版单独显示应填写“5”(不含双引号)。\r\n---\r\n2、切换名称:对应显示版本的链接名称\r\n注明:如留空则使用系统名称“简版、彩版、触屏版、平板、电脑版”;\r\n　　若要两个或以上的版本显示也是把它们的用半角逗号“,”连起来;\r\n---\r\n3、分割符号:显示两个或以上的版本是链接起来的字符串\r\n---\r\n4、当前版本无链接:如果当然访问的版本就是要显示的版本则只显示名称不显示链接\r\n==========\r\n【实例】\r\n①\r\n切换版本:1,3,2\r\n切换名称:WML简版,HTML触屏版,3G彩版\r\n分割符号:-\r\n当前版本无链接:是\r\n<<配置如下图>>\r\n{ubb set=[img]templates/default/explain/images/beta-1.jpg[/img]}\r\n<<效果如下图>>\r\n{ubb set=[img]templates/default/explain/images/beta-1s.jpg[/img]}\r\n②\r\n切换版本:1,2,3,4,5\r\n切换名称:(留空)\r\n分割符号:|\r\n当前版本无链接:否\r\n<<配置如下图>>\r\n{ubb set=[img]templates/default/explain/images/beta-2.jpg[/img]}\r\n<<效果如下图>>\r\n{ubb set=[img]templates/default/explain/images/beta-2s.jpg[/img]}\r\n==========\r\n【模板中调用】\r\n{kuaifan beta=\"切换版本\" beta_cn=\"切换名称\" beta_cut=\"分割符号\" beta_dot=\"当前版本无链接(是:0;否:1)\" vs=\"显示版本\"}\r\n---\r\n如上实例①模板中应该是:\r\n　{kuaifan beta=\"1,3,2\" beta_cn=\"WML简版,HTML触屏版,3G彩版\" beta_cut=\"-\" beta_dot=\"0\"}\r\n如上实例②模板中应该是:\r\n　{kuaifan beta=\"1,2,3,4,5\" beta_cut=\"|\" beta_dot=\"1\"}',''), ('5','paiban','排版设计','ubb','UBB标签','\r\n[tab]　　　　　&nbsp;　　　空格\r\n///　　　　<br/>　　　换行\r\n[br]　　　　　<br/>　　　换行\r\n[copy]　　　　&copy;　　　版权符号\r\n[date]　　　　Y-m-d　　　时间：年-月-日\r\n[time]　　　　H:i:s　　　时间：时-分-秒\r\n[now]　　　　Y-m-d H:i:s　　　时间：年-月-日 时-分-秒\r\n[miao]　　　　time()　　　时间戳\r\n[sid]　　　　　标识　　　身份标识\r\n[date(Y-m-d H:i:s)]　　　　 date(Y-m-d H:i:s)　　　自定义date标签\r\n[login]XXX[/login]　　　登录可显示   \r\n[nologin]XXX[/nologin]　　　未登录可显示\r\n----------\r\n[img]XXX[/img]　　　图片显示\r\n[img=XXX]XXX[/img]　　　图片链接\r\n----------\r\n[url]XXX[/url]　　　链接显示\r\n[url=XXX]XXX[/url]　　　标题链接\r\n----------\r\n[call]XXX[/call]　　　拨打电话链接\r\n[call=XXX]XXX[/call]　　　拨打电话标题链接\r\n----------\r\n[i]XXX[/i]　　　斜体显示\r\n[u]XXX[/u]　　　下划线显示\r\n[b]XXX[/b]　　　粗体显示\r\n[big]XXX[/big]　　　大字体显示\r\n[small]XXX[/small]　　　小字体显示\r\n[center]XXX[/center]　　　居中显示\r\n----------\r\n会员资料信息\r\n[userid]　　　用户ID\r\n[username]　　　用户名\r\n[nickname]　　　用户昵称\r\n[colorname]　　　用户昵称(含:颜色、勋章)\r\n[onlycolorname]　　　用户昵称(含:颜色)\r\n[loginnum]　　　登录次数\r\n[email]　　　邮箱地址\r\n[amount]　　　金币\r\n[point]　　　积分\r\n[mobile]　　　手机号码\r\n----------\r\n自定义标签\r\n[div=XXX]XXX[/div]　　　自定义div标签\r\n[span=XXX]XXX[/span]　　　自定义span标签\r\n[ul=XXX]XXX[/ul]　　　自定义ul标签\r\n[li=XXX]XXX[/li]　　　自定义li标签\r\n----------\r\n[color=XXX]XXX[/color]　　　字体颜色\r\n[font=XXX]XXX[/font]　　　字体样式\r\n[size=XXX]XXX[/size]　　　字体大小\r\n----------\r\n其他标签\r\n[sup]XXX[/sup]　　　上标注\r\n[sub]XXX[/sub]　　　下表中\r\n[pre]XXX[/pre]　　　格式化的文本\r\n[strike]XXX[/strike]　　　删除线文本定义\r\n[email]XXX[/email]　　　邮箱地址连接\r\n[quote]XXX[/quote]　　　标签定义块引用\r\n[index]XXX[/index]　　　返回首页连接\r\n[sel]XXX[/sel]　　　下拉调转链接\r\n[option]XXX[/option]\r\n[option=XXX]XXX[/option]\r\n[optionv=XXX]XXX[/optionv]\r\n[selv=XXX]XXX[/selv]\r\n[HR]\r\n[anchor=XXX]XXX[/anchor]　　　无下划线链接\r\n[ag=XXX]XXX[/ag]　　　div对齐模式\r\n[back=XXX]XXX[/back]　　　返回上一页链接\r\n[backcolor=XXX]XXX[/backcolor]　　　带背景颜色的span标签\r\n[fly]XXX[/fly]　　　marquee标签\r\n----------\r\n随机链接\r\n[rndurl]XXX[/rndurl]\r\n[rndurl=XXX]XXX[/rndurl]\r\n---\r\n随机图片\r\n[rndimg]XXX[/rndimg]\r\n[rndimg=XXX]XXX[/rndimg]\r\n---\r\n随机文字\r\n[rndtxt]XXX[/rndtxt]\r\n---\r\n*以上3个随机项间隔符号“|”，如：[rndtxt]文字A|文字B|文字C|文字D[/rndtxt]\r\n----------\r\n倒计时（返回天）\r\n[codo]XXX[/codo]\r\n---\r\n倒计时（设置A返回: 1小时、2分钟、3秒、默认0天）\r\n[codo=A]XXX[/codo]\r\n----------\r\n在线会员数\r\n[zx]XXX[/zx] （XXX填写整数，表示XXX分钟内在线人数）\r\n----------\r\n随机数字\r\n[sjs]100,999[/sjs] （表示从100到999随机一个数字）\r\n----------\r\n未读信息\r\n[weidu=0] （未读信息）\r\n[weidu=0,1] （带链接未读信息）\r\n[weidu=1] （未读系统信息）\r\n[weidu=1,1] （带链接未读系统信息）\r\n----------\r\n版本显示\r\n[wap=A]XXX[/wap]\r\nA（1:简版、2:彩版、3:触屏版、4:平板、5:电脑版）\r\nXXX（要显示的内容）\r\n----------\r\n\\]、\\[、\\}、\\{　　　　　　 反斜标识',''), ('6','paiban','排版设计','wml','WML标签','\r\n{sid}　　　 标识\r\n{date(Y-m-d H:i:s)}　　　date(Y-m-d H:i:s)\r\n{ubb}XXX{/ubb}　　　ubb标签显示\r\n{login}XXX{/login}　　　登录可显示\r\n{nologin}XXX{/nologin}　　　未登录可显示\r\n----------\r\n会员资料信息\r\n{userid}　　　用户ID\r\n{username}　　　用户名\r\n{nickname}　　　用户昵称\r\n{colorname}　　　用户昵称(含:颜色、勋章)\r\n{onlycolorname}　　　用户昵称(含:颜色)\r\n{loginnum}　　　登录次数\r\n{email}　　　邮箱地址\r\n{amount}　　　金币\r\n{point}　　　积分\r\n{mobile}　　　手机号码\r\n----------\r\n随机链接\r\n{rndurl}XXX{/rndurl}\r\n{rndurl=XXX}XXX{/rndurl}\r\n---\r\n随机图片\r\n{rndimg}XXX{/rndimg}\r\n{rndimg=XXX}XXX{/rndimg}\r\n---\r\n随机文字\r\n{rndtxt}XXX{/rndtxt}\r\n---\r\n*以上3个随机项间隔符号“|”，如：{rndtxt}文字A|文字B|文字C|文字D{/rndtxt}\r\n----------\r\n倒计时（返回天）\r\n{codo}XXX{/codo}\r\n---\r\n倒计时（设置A返回: 1小时、2分钟、3秒、默认0天）\r\n{codo=A}XXX{/codo}\r\n----------\r\n在线会员数\r\n{zx}XXX{/zx} （XXX填写整数，表示XXX分钟内在线人数）\r\n----------\r\n随机数字\r\n{sjs}100,999{/sjs} （表示从100到999随机一个数字）\r\n----------\r\n未读信息\r\n{weidu=0} （未读信息）\r\n{weidu=0,1} （带链接未读信息）\r\n{weidu=1} （未读系统信息）\r\n{weidu=1,1} （带链接未读系统信息）\r\n----------\r\n版本显示\r\n{wap=A}XXX{/wap}\r\nA（1:简版、2:彩版、3:触屏版、4:平板、5:电脑版）\r\nXXX（要显示的内容）\r\n----------\r\n\\]、\\[、\\}、\\{　　　　　　 反斜标识',''), ('7','guanggao','广告系统','shuoming','广告系统说明','\r\n{ubb set=[b]一、广告系统说明：[/b]}\r\n广告系统由“广告位”和“广告”两个部分组成;\r\n它们的关系是广告位是拿来存放广告的(即:广告存放在广告位里);\r\n可以这样说广告位就是广告分类。\r\n-------\r\n{ubb set=[b]二、广告位投放：[/b]}\r\n①ubb代码投放:[guanggao]广告位ID|数量|排序|换行[/guanggao]\r\n②模板中投放:{kuaifan guanggao=\"广告位ID|数量|排序|换行\"}\r\n广告位ID: 0显示所有\r\n数量: 显示广告数量\r\n排序: 0默认,1随机,startdate上线时间,enddate下线时间,clicks点击数\r\n换行: 0不换行,填大于0则按填写的数字换行(如:填5则每显示5个就自动换行)',''), ('8','lianjie','友情链接','shuoming','友链系统说明','\r\n{ubb set=[b]友情链接调用说明：[/b]}\r\n①ubb代码投放:[lianjie]分类ID|数量|名称|排序|换行|间隔符[/lianjie]\r\n②模板中投放:{kuaifan lianjie=\"分类ID|数量|名称|排序|换行|间隔符\"}\r\n分类ID: 0显示所有\r\n数量: 显示友链数量\r\n名称: 0显示简称,1显示全称\r\n排序: 0默认,1随机,read访问次数降序,readtime访问时间降序,from来访次数降序,fromtime来访时间降序,zhichi支持降序,buzhichi不支持降序\r\n换行: 0不换行,填大于0则按填写的数字换行(如:填5则每显示5个就自动换行)\r\n间隔符: 可留空,两个友链之间的间隔内容',''), ('9','youjian','邮件模板','reg','注册会员模板变量','\r\n{username}　用户名\r\n{password}　密码\r\n{nickname}　昵称\r\n{email}　邮箱\r\n{regip}　注册IP\r\n{mobile}　手机号码\r\n\r\n{site_name}　网站名称\r\n{site_namej}　网站简称\r\n{site_domain}　网站地址(带http://)\r\n{site_dir}　网站根目录',''), ('10','youjian','邮件模板','editpwd','修改密码模板变量','\r\n{newpassword}　密码\r\n{email}　邮箱\r\n\r\n{site_name}　网站名称\r\n{site_namej}　网站简称\r\n{site_domain}　网站地址(带http://)\r\n{site_dir}　网站根目录',''), ('11','youjian','邮件模板','renzheng','邮箱认证模板变量','\r\n{username}　用户名\r\n{userpass}　密码\r\n{nickname}　昵称\r\n{email}　邮箱\r\n{regip}　注册IP\r\n{mobile}　手机号码\r\n{click}　认证链接\r\n{url}　认证链接地址\r\n\r\n{site_name}　网站名称\r\n{site_namej}　网站简称\r\n{site_domain}　网站地址(带http://)\r\n{site_dir}　网站根目录',NULL), ('12','youjian','邮件模板','zhaohui','密码找回模板变量','\r\n{username}　用户名\r\n{userpass}　密码\r\n{nickname}　昵称\r\n{email}　邮箱\r\n{regip}　注册IP\r\n{mobile}　手机号码\r\n\r\n{site_name}　网站名称\r\n{site_namej}　网站简称\r\n{site_domain}　网站地址(带http://)\r\n{site_dir}　网站根目录',NULL), ('13','youjian','邮件模板','order','新增订单模板变量','\r\n{title}　订单标题\r\n{oid}　订单编号\r\n{paymenttpye}　付款货币类型\r\n{amount}　付款金额\r\n{price}　单价\r\n{num}　购买数量\r\n\r\n{site_name}　网站名称\r\n{site_namej}　网站简称\r\n{site_domain}　网站地址(带http://)\r\n{site_dir}　网站根目录',NULL), ('14','youjian','邮件模板','payment','付款成功模板变量','\r\n{title}　订单标题\r\n{oid}　订单编号\r\n{paymenttpye}　付款货币类型\r\n{amount}　付款金额\r\n{price}　单价\r\n{num}　购买数量\r\n\r\n{site_name}　网站名称\r\n{site_namej}　网站简称\r\n{site_domain}　网站地址(带http://)\r\n{site_dir}　网站根目录',NULL), ('15','wenjian','文件上传','mulu','目录储存模式','\r\n当使用“目录储存模式”时附件列表是系统指定目录下指定文件类型以文件名排序方式显示出来;\r\n可以直接使用ftp等上传工具把文件上传到系统指定目录下即可直线显示;\r\n这样可以省去了一个一个文件上传的麻烦;\r\n但使用此模式将暂时无法统计下载次数。\r\n同时“最多上传个数”、“文件下载方式”的设置起不到效果。',NULL), ('16','paiban','排版设计','nocache','缓存时长','\r\n单位是：分钟；\r\n设置为0时禁用缓存；\r\n使用示例：随机UBB代码、在线人数等时时更新不需要缓存的项目应设置0；WML标签、列表等长时间不变的建议使用缓存，一般建议设置缓存时长10分钟左右。',NULL), ('17','paiban','排版设计','guanggao','广告投放','\r\n1、广告：选择要投放的广告; \r\n2、显示数量：设置要显示的广告数量(当“广告”设为不限是有效);\r\n3、排序方式：广告显示的排序方式(当“广告”设为不限是有效);\r\n4、换行间隔：0不换行,填大于0则按填写的数字换行[如:填5则每显示5个就自动换行一次](当“广告”设为不限是有效)。\r\n-------\r\n{ubb set=[b]模板中投放：[/b]}\r\n{kuaifan guanggao=\"广告位ID|显示数量|排序方式|换行间隔\"}\r\n*广告位ID: 0显示所有',NULL), ('18','paiban','排版设计','lianjie','友链调用','\r\n1、友链分类：选择要显示的友情链接分类； \r\n2、显示数量：设置要显示的友情链接数量；\r\n3、显示名称：设置要显示哪种名称类型；\r\n4、排序方式：友情链接显示的排序方式；\r\n5、换行间隔：0不换行,填大于0则按填写的数字换行[如:填5则每显示5个就自动换行一次]；\r\n6、间隔符号：可留空,两个友链之间的间隔内容。\r\n-------\r\n{ubb set=[b]模板中投放：[/b]}\r\n{kuaifan lianjie=\"分类ID|显示数量|显示名称|排序方式|换行间隔|间隔符号\"}\r\n*分类ID: 0显示所有',NULL), ('19','duanxin','短信系统','zhuce','短信注册','\r\n一、短信猫配置\r\n　1.1、打开 http://sms.8eoo.com/ 注册一个帐号并登录。\r\n　1.2、登录后点击你的会员名称进入“个人中心”。\r\n　1.3、在“个人中心”点击“新增指令”。\r\n　1.4、填写新增指令信息。\r\n　　1.4.1)接口地址: http://你的网址/smsreg.php\r\n　　1.4.2)效验码: (随便你设置, 如:abc321)。\r\n　　1.4.3)短信指令: (随便你设置, 如:kf)。\r\n-------------\r\n二、网站配置\r\n　2.1、进入网站后台点击进入“短信系统”。\r\n　2.2、输入上面第1.4步提到的“效验码”和“短信指令”。\r\n　2.3、根据你的需要填写短信注册说明等信息。\r\n-------------\r\n三、使用说明\r\n　3.1、发送“短信指令(如:kf)”到“在第1.1步注册时使用的手机号码”\r\n　3.2、发送“短信指令#密码(如:kf#123456)”到“在第1.1步注册时使用的手机号码”\r\n　3.3、发送“短信指令#用户名#密码(如:kf#apple#123456)”到“在第1.1步注册时使用的手机号码”\r\n　[发送短信成功后]\r\n　用户名:为你的手机号码。(注意:如果您使用第3.3步发送的用户名已存在则注册失败)\r\n　密码:为手机号码后6位。(注意:如果你已经注册系统会把你的密码更改为手机号码后6位,此法即为找回密码)\r\n-------------\r\n四、注册/找回密码说明效果演示\r\n{ubb set=[img]templates/default/explain/images/sms-1.jpg[/img]}\r\n{ubb set=[img]templates/default/explain/images/sms-2.jpg[/img]}',NULL), ('20','ucenter','Ucenter','peizhi','Ucenter配置','\r\n第一步：安装 Discuz! X3 （同时安装 UCenter）。\r\n第二步：在 UCenter 中添加“kuaifan 应用”。\r\n　2.1：管理员登录 Discuz! X3 管理中心，进入“UCenter”，点击“应用管理”。\r\n　2.2：点击“添加新应用”按钮，选择安装方式为”自定义安装“。在展开的”添加新应用“参数配置表中，各项参数配置如下：\r\n　　应用类型：其它（必选）\r\n　　应用名称：kuaifancms（必填）\r\n　　应用的主 URL：http：//localhost/kuaifan   （必填， kuaifancms 安装路径，视实际情况而定，最后不要带斜线）\r\n　　应用 IP：（选填，正常情况下留空即可。如果由于域名解析问题导致 UCenter 与该应用通信失败，请尝试设置为该应用所在服务器的 IP 地址。）\r\n　　通信密钥：（必填，kuaifancms 的通信密钥必须与此设置保持一致，否则 kuaifan 将无法与 UCenter 正常通信。）\r\n　　应用的物理路径：（选填，默认留空）\r\n　　查看个人资料页面地址：（选填，URL中域名后面的部分，如：/space.php?uid=%s 这里的 %s 代表uid）\r\n　　应用接口文件名称：（选填，默认为uc.php）\r\n　　标签单条显示模板：（选填，默认留空）\r\n　　标签模板标记说明：（选填，默认留空）\r\n　　是否开启同步登录：是（可选，开启同步登录后，当用户在登录 Discuz! 时，同时也会登录 kuaifan 。）\r\n　　是否接受通知：是（可选）\r\n　　{ubb set=[url=templates/default/explain/images/uc-1.jpg]查看图片教程演示[/url]}\r\n　2.3：提交后，将生成新的应用ID。（记住这个应用ID，在”第三步“中将用到它。）\r\n　　{ubb set=[url=templates/default/explain/images/uc-2.jpg]查看图片教程演示[/url]}\r\n　2.4：进入后台“站长”，“UCenter 设置”选项。\"是否允许直接激活\"一项，选择“是”。\r\n　2.5：进入后台“工具”，“更新缓存”选项。选择“数据缓存”和“模板缓存”点击确定。\r\n第三步：配置 kuaifancms 。\r\n　3.1：管理员登录 kuaifancms 后台管理中心，点击进入“UCenter”，各项参数配置如下：\r\n　　是否启用：是\r\n　　api 地址：http：//localhost/discuz/uc_server　　 （必填，此为 Discuz! 安装路径，视实际情况而定，最后不要带斜线）\r\n　　api IP：（选填，一般不用填写，遇到无法同步时，请填写 UCenter 主机的IP地址）\r\n　　数据库主机名：localhost　　 （必填，视实际情况而定）\r\n　　数据库用户名：root　　 （必填，视实际情况而定）\r\n　　数据库密码：root　　（视实际情况而定）\r\n　　数据库名：discuz　　 （必填，视实际情况而定）\r\n　　数据库表前缀：`discuz`.pre_ucenter_　　 （必填，视实际情况而定。如果此项填写错误，将导致 kuaifan 无法注册新会员！）\r\n　　数据库字符集：UTF-8　　  （必选，视实际情况而定）\r\n　　应用id(APP ID)：（必填，该值来在“第二步”中 UCenter 创建的 kuaifan 应用时自动。）\r\n　　通信密钥：（必填，一定确保该值与在“第二步”中 UCenter 创建的 kuaifan 应用密钥相同。）\r\n　　{ubb set=[url=templates/default/explain/images/uc-3.jpg]查看图片教程演示[/url]}\r\n　3.2：提交。\r\n第四步：查看通信状态。\r\n　查看在 UCenter 中创建的 kuaifan 应用与 UCenter 通信是否成功。\r\n　如果通信成功，则进行下一步。\r\n　如果通信失败，请检查“第二步”与“第三步”中的各项参数配置是否正确。\r\n第五步：安装kuaifan上传头像同步到UCenter程序。　\r\n　5.1：{ubb set=[url=templates/default/explain/images/avatar_kuaifan.zip]下载avatar_kuaifan.zip[/url]}，解压得到avatar_kuaifan.php。\r\n　5.2：将avatar_kuaifan.php上传至“Discuz! 安装路径/uc_server”。\r\n　{ubb set=[url=templates/default/explain/images/uc-4.jpg]查看图片教程演示[/url]}\r\n　(如果第五步未安装也不影响系统正常使用，只是在kuaifan程序上传头像的时候不同步到Discuz而已。)',NULL), ('21','xitong','系统辅助','yanse','颜色代码','\r\n颜色代码不分大小写\r\n{ubb set=[url=templates/default/explain/images/yanse.gif][img]templates/default/explain/images/yanse.gif[/img][/url]}',NULL), ('22','paiban','排版设计','dongtai','会员动态','\r\n1、显示数量:调用的数量\r\n---\r\n2、截取标题:0不截取\r\n---\r\n3、填补字符:标题超过截取长度用此填补\r\n---\r\n4、调用类型:根据选择进行筛选\r\n---\r\n5、自写模板:\r\n　{url}为链接\r\n　{title}为标题\r\n　{title,5}为截取长度5的标题(5可改)\r\n　{title,5,...}为截取长度5超过截取长度用...填补的标题(5和...可改)\r\n　{time}为格式时间(Y-m-d H:i:s)\r\n　{time2}为格式时间(xxx前)\r\n　{huiyuan}为会员信息(含链接)\r\n　{nickname}为会员昵称(不含链接,不含勋章)\r\n　{colorname}为会员昵称(不含链接,含勋章)\r\n　{userid}为会员ID\r\n　{username}为会员用户名\r\n　{hyurl}为会员链接\r\n　{ip}为格式IP(111.111.111.111)\r\n　{ip2}为格式IP(111.111.*.*)\r\n　{type}为动态动作(正在访问)\r\n　{n}为序号\r\n　{wap=A}XXX{/wap}版本显示功能(请查阅后台>帮助手册>WML标签对此标签的说明)\r\n　注*:{time}支持date函数方式,如:{time(Y-m-d H:i:s)}\r\n　例如:<a href=\"{hyurl}\">{colorname}</a>{time2}{type}<a href=\"{url}\">{title}</a><br/>',NULL);
INSERT INTO `kf_biaoqing` VALUES ('1','微笑','1','默认分类','1401287521','0','0'), ('2','撇嘴','1','默认分类','1401178440','0','0'), ('3','色','1','默认分类','1401178441','0','0'), ('4','发呆','1','默认分类','1401178442','0','0'), ('5','得意','1','默认分类','1401178443','0','0'), ('6','流泪','1','默认分类','1401178444','0','0'), ('7','害羞','1','默认分类','1401178445','0','0'), ('8','闭嘴','1','默认分类','1401178446','0','0'), ('9','睡','1','默认分类','1401178447','0','0'), ('10','大哭','1','默认分类','1401178448','0','0'), ('11','尴尬','1','默认分类','1401178449','0','0'), ('12','发怒','1','默认分类','1401178450','0','0'), ('13','调皮','1','默认分类','1401178451','0','0'), ('14','呲牙','1','默认分类','1401178452','0','0'), ('15','惊讶','1','默认分类','1401178453','0','0'), ('16','难过','1','默认分类','1401178454','0','0'), ('17','酷','1','默认分类','1401178455','0','0'), ('18','冷汗','1','默认分类','1401178456','0','0'), ('19','抓狂','1','默认分类','1401178457','0','0'), ('20','吐','1','默认分类','1401178458','0','0'), ('21','偷笑','1','默认分类','1401178459','0','1'), ('22','可爱','1','默认分类','1401178460','0','1'), ('23','白眼','1','默认分类','1401178461','0','1'), ('24','傲慢','1','默认分类','1401178462','0','1'), ('25','饥饿','1','默认分类','1401178463','0','1'), ('26','困','1','默认分类','1401178464','0','1'), ('27','惊恐','1','默认分类','1401178465','0','1'), ('28','流汗','1','默认分类','1401178466','0','1'), ('29','憨笑','1','默认分类','1401178467','0','1'), ('30','大兵','1','默认分类','1401178468','0','1'), ('31','奋斗','1','默认分类','1401178469','0','1'), ('32','咒骂','1','默认分类','1401178470','0','1'), ('33','疑问','1','默认分类','1401178471','0','1'), ('34','嘘','1','默认分类','1401178472','0','1'), ('35','晕','1','默认分类','1401178473','0','1'), ('36','折磨','1','默认分类','1401178474','0','1'), ('37','衰','1','默认分类','1401178475','0','1'), ('38','骷髅','1','默认分类','1401178476','0','1'), ('39','敲打','1','默认分类','1401178477','0','1'), ('40','再见','1','默认分类','1401178478','0','1'), ('41','擦汗','1','默认分类','1401178479','0','1'), ('42','抠鼻','1','默认分类','1401178480','0','1'), ('43','鼓掌','1','默认分类','1401178481','0','1'), ('44','糗大了','1','默认分类','1401178482','0','1'), ('45','坏笑','1','默认分类','1401178483','0','1'), ('46','左哼哼','1','默认分类','1401178484','0','1'), ('47','右哼哼','1','默认分类','1401178485','0','1'), ('48','哈欠','1','默认分类','1401178486','0','1'), ('49','鄙视','1','默认分类','1401178487','0','1'), ('50','委屈','1','默认分类','1401178488','0','1'), ('51','快哭了','1','默认分类','1401178489','0','1'), ('52','阴险','1','默认分类','1401178490','0','1'), ('53','亲亲','1','默认分类','1401178491','0','1'), ('54','吓','1','默认分类','1401178492','0','1'), ('55','可怜','1','默认分类','1401178493','0','1'), ('56','菜刀','1','默认分类','1401178494','0','1'), ('57','西瓜','1','默认分类','1401178495','0','1'), ('58','啤酒','1','默认分类','1401178496','0','1'), ('59','篮球','1','默认分类','1401178497','0','1'), ('60','乒乓','1','默认分类','1401178498','0','1'), ('61','咖啡','1','默认分类','1401178499','0','1'), ('62','饭','1','默认分类','1401178500','0','1'), ('63','猪头','1','默认分类','1401178501','0','1'), ('64','玫瑰','1','默认分类','1401178502','0','1'), ('65','凋谢','1','默认分类','1401178503','0','1'), ('66','示爱','1','默认分类','1401178504','0','1'), ('67','爱心','1','默认分类','1401178505','0','1'), ('68','心碎','1','默认分类','1401178506','0','1'), ('69','蛋糕','1','默认分类','1401178507','0','1'), ('70','闪电','1','默认分类','1401178508','0','1'), ('71','炸弹','1','默认分类','1401178509','0','1'), ('72','刀','1','默认分类','1401178510','0','1'), ('73','足球','1','默认分类','1401178511','0','1'), ('74','瓢虫','1','默认分类','1401178512','0','1'), ('75','便便','1','默认分类','1401178513','0','1'), ('76','月亮','1','默认分类','1401178514','0','1'), ('77','太阳','1','默认分类','1401178515','0','1'), ('78','礼物','1','默认分类','1401178516','0','1'), ('79','拥抱','1','默认分类','1401178517','0','1'), ('80','强','1','默认分类','1401178518','0','1'), ('81','弱','1','默认分类','1401178519','0','1'), ('82','握手','1','默认分类','1401178520','0','1'), ('83','胜利','1','默认分类','1401178521','0','1'), ('84','抱拳','1','默认分类','1401178522','0','1'), ('85','勾引','1','默认分类','1401178523','0','1'), ('86','拳头','1','默认分类','1401178524','0','1'), ('87','差劲','1','默认分类','1401178525','0','1'), ('88','爱你','1','默认分类','1401178526','0','1'), ('89','NO','1','默认分类','1401178527','0','1'), ('90','OK','1','默认分类','1401178528','0','1'), ('91','爱情','1','默认分类','1401178529','0','1'), ('92','飞吻','1','默认分类','1401178530','0','1'), ('93','跳跳','1','默认分类','1401178531','0','1'), ('94','发抖','1','默认分类','1401178532','0','1'), ('95','怄火','1','默认分类','1401178533','0','1'), ('96','转圈','1','默认分类','1401178534','0','1'), ('97','磕头','1','默认分类','1401178535','0','1'), ('98','回头','1','默认分类','1401178536','0','1'), ('99','跳绳','1','默认分类','1401178537','0','1'), ('100','挥手','1','默认分类','1401178538','0','1');
INSERT INTO `kf_biaoqing` VALUES ('101','激动','1','默认分类','1401178539','0','1'), ('102','街舞','1','默认分类','1401178540','0','1'), ('103','献吻','1','默认分类','1401178541','0','1'), ('104','左太极','1','默认分类','1401178542','0','1'), ('105','右太极','1','默认分类','1401178543','0','1');
INSERT INTO `kf_biaoqing_fenlei` VALUES ('1','默认分类','0','1');
INSERT INTO `kf_huiyuan` VALUES ('1','','0','2','kuaifan','95e208bf08066094e1f16401435beb75','','0','0','0','p9piKB','快范之家','','0','1383939358','1390196225','1390196230','1390196230','127.0.0.1','127.0.0.1','9','342210020@qq.com','4','0','168.88','100','1','0','0','0','0','1','','18978941931','','0','1');
INSERT INTO `kf_huiyuan_diy_detail` VALUES ('1','1');
INSERT INTO `kf_huiyuan_moxing` VALUES ('1','默认会员','','detail','0','0','default','category','list','show','1382072744','1');
INSERT INTO `kf_huiyuan_zu` VALUES ('8','游客','1','0','0','0','0','0','0','1','0','0','0','0','0.00','0.00','0.00','','','','1','0','1','0','0','200','1'), ('2','新手上路','1','1','50','100','0','1','1','0','1','1','0','0','50.00','10.00','1.00','','','','2','0','1','0','0','200','1'), ('6','注册会员','1','2','100','150','0','1','0','0','1','1','0','0','300.00','30.00','1.00','','','','6','0','1','0','0','200','1'), ('4','中级会员','1','3','150','500','0','1','0','1','1','1','0','0','500.00','60.00','1.00','{:domain}/uploadfiles/avatar/icon/4.gif','','','4','0','1','0','0','200','1'), ('5','高级会员','1','5','300','999','0','1','0','1','1','1','0','1','360.00','90.00','5.00','{:domain}/uploadfiles/avatar/icon/5.gif','ff00ff','','5','0','1','0','0','200','1'), ('1','禁止访问','1','0','0','0','1','1','0','1','0','0','0','0','0.00','0.00','0.00','','','0','0','0','1','0','0','200','1'), ('7','邮件认证','1','0','0','0','0','0','0','0','0','0','0','0','0.00','0.00','0.00','{:domain}/uploadfiles/avatar/icon/7.png','#000000','','7','0','1','0','0','200','1');
INSERT INTO `kf_lianjie` VALUES ('1','快范之家','快范','http://www.kuaifan.net','0','1','默认分类','1','这位站长比校忙，还没有时间填写。','1386255590','2','127.0.0.1','1388210786','0','','0','0','4','0','0');
INSERT INTO `kf_lianjie_fenlei` VALUES ('1','0','1','0','默认分类','0','1');
INSERT INTO `kf_neirong_moxing` VALUES ('1','new','默认类型','文章模型','','news','0','0','default','category','list','show','1382072744','1'), ('2','new','默认类型','下载模型','','download','0','0','default','category','list_xiazai','show_xiazai','1382079361','1'), ('3','new','默认类型','图片模型','','picture','0','0','default','category','list_tupian','show_tupian','1382079385','1'), ('4','bbs','论坛类型','论坛模型','','bbs','0','0','default','category','list_bbs','show_bbs','1388378448','1');
INSERT INTO `kf_neirong_moxing_ziduan` VALUES ('1','1','catid','2','1','catid','栏目','请选择栏目',NULL,'0','0','1','0','1','1','1'), ('2','1','typeid','0','1','typeid','类别','',NULL,'0','0','1','1','1','0','1'), ('3','1','title','2','1','title','标题','请输入标题',NULL,'1','100','1','0','1','2','1'), ('4','1','keyword','0','1','keywords','关键词','多关键词之间用空格或者“,”隔开',NULL,'0','200','1','0','1','3','1'), ('5','1','textarea','0','1','description','摘要','',NULL,'0','200','1','0','0','4','1'), ('6','1','datetime','2','1','updatetime','更新时间','','array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','1','0','0','1'), ('7','1','textarea','0','0','content','内容','',NULL,'0','0','1','0','1','6','1'), ('8','1','image','0','1','thumb','缩略图','',NULL,'0','0','1','0','1','8','1'), ('9','1','text','2','1','url','URL','',NULL,'0','0','1','1','0','0','1'), ('10','1','number','2','1','listorder','排序','','array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','0','0','10','1'), ('11','1','box','0','1','status','状态','',NULL,'0','0','1','1','0','0','1'), ('12','1','text','2','1','username','用户名','',NULL,'0','0','1','1','0','0','1'), ('13','1','islink','0','1','islink','转向链接','输入则访问该内容自动转跳改此链接',NULL,'0','0','1','0','0','12','1'), ('14','1','datetime','2','1','inputtime','发布时间','','array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','0','0','5','1'), ('15','1','readpoint','0','0','readpoint','阅读收费','',NULL,'0','0','1','0','0','14','1'), ('16','1','relation','0','0','relation','相关文章','',NULL,'0','0','1','1','0','0','1'), ('17','1','pages','0','0','pages','分页字数','留空或者填“0”则默认1000字','array (\r\n  \'defaultvalue\' => \'1000\',\r\n)','0','0','1','0','0','7','1'), ('18','1','groupid','0','0','groupids_view','阅读权限','',NULL,'0','0','1','1','0','0','1'), ('19','1','template','2','0','template','内容页模板','',NULL,'0','0','1','1','0','0','1'), ('20','1','box','0','0','allow_comment','允许评论','','array (\r\n  \'options\' => \'允许评论|1\\r\\n不允许评论|0\',\r\n  \'defaultvalue\' => \'1\',\r\n)','0','0','1','0','0','13','1'), ('21','1','downfile','0','0','downfile','文件上传','','array (\n  \'defaultvalue\' => \'\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'\',\n  \'boxtype\' => \'\',\n  \'outputtype\' => \'\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'jpg\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'1\',\n  \'pathlist\' => \'0\',\n)','0','0','1','0','1','9','1'), ('22','2','catid','2','1','catid','栏目','请选择栏目',NULL,'0','0','1','0','1','1','1'), ('23','2','typeid','0','1','typeid','类别','',NULL,'0','0','1','1','1','0','1'), ('24','2','title','2','1','title','标题','请输入标题',NULL,'1','100','1','0','1','2','1'), ('25','2','keyword','0','1','keywords','关键词','多关键词之间用空格或者“,”隔开',NULL,'0','200','1','0','1','3','1'), ('26','2','textarea','0','1','description','摘要','',NULL,'0','200','1','0','1','4','1'), ('27','2','datetime','2','1','updatetime','更新时间','','array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','1','0','0','1'), ('28','2','textarea','0','0','content','介绍说明','',NULL,'0','0','1','0','1','6','1'), ('29','2','image','0','1','thumb','缩略图','',NULL,'0','0','1','0','1','8','1'), ('30','2','text','2','1','url','URL','',NULL,'0','0','1','1','0','0','1'), ('31','2','number','2','1','listorder','排序','','array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','0','0','11','1'), ('32','2','box','0','1','status','状态','',NULL,'0','0','1','1','0','0','1'), ('33','2','text','2','1','username','用户名','',NULL,'0','0','1','1','0','0','1'), ('34','2','islink','0','1','islink','转向链接','输入则访问该内容自动转跳改此链接',NULL,'0','0','1','0','0','13','1'), ('35','2','datetime','2','1','inputtime','发布时间','','array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','0','0','5','1'), ('36','2','readpoint','0','0','readpoint','阅读收费','',NULL,'0','0','1','0','0','15','1'), ('37','2','relation','0','0','relation','相关文章','',NULL,'0','0','1','1','0','0','1'), ('38','2','pages','1','0','pages','分页字数','留空或者填“0”则默认1000字','array (\r\n  \'defaultvalue\' => \'1000\',\r\n)','0','0','1','0','0','7','1'), ('39','2','groupid','0','0','groupids_view','阅读权限','',NULL,'0','0','1','1','0','0','1'), ('40','2','template','2','0','template','内容页模板','',NULL,'0','0','1','1','0','0','1'), ('41','2','box','0','0','allow_comment','允许评论','','array (\r\n  \'options\' => \'允许评论|1\\r\\n不允许评论|0\',\r\n  \'defaultvalue\' => \'1\',\r\n)','0','0','1','0','0','14','1'), ('42','2','downfile','0','0','downfile','文件上传','','array (\r\n  \'defaultfile\' => \'3\',\r\n  \'maxfile\' => \'10\',\r\n  \'options\' => \'直接下载|1\\r\\n跳转下载|0\',\r\n  \'defaultvalue\' => \'1\',\r\n)','0','0','1','0','1','10','1'), ('43','3','catid','2','1','catid','栏目','请选择栏目',NULL,'0','0','1','0','1','1','1'), ('44','3','typeid','0','1','typeid','类别','',NULL,'0','0','1','1','1','0','1'), ('45','3','title','2','1','title','标题','请输入标题',NULL,'1','100','1','0','1','2','1'), ('46','3','keyword','0','1','keywords','关键词','多关键词之间用空格或者“,”隔开',NULL,'0','200','1','0','1','3','1'), ('47','3','textarea','0','1','description','摘要','',NULL,'0','200','1','0','1','4','1'), ('48','3','datetime','2','1','updatetime','更新时间','','array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','1','0','0','1'), ('49','3','textarea','0','0','content','内容','',NULL,'0','0','1','0','1','6','1'), ('50','3','image','0','1','thumb','缩略图','',NULL,'0','0','1','0','1','8','1'), ('51','3','text','2','1','url','URL','',NULL,'0','0','1','1','0','0','1'), ('52','3','number','2','1','listorder','排序','','array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','0','0','10','1'), ('53','3','box','0','1','status','状态','',NULL,'0','0','1','1','0','0','1'), ('54','3','text','2','1','username','用户名','',NULL,'0','0','1','1','0','0','1'), ('55','3','islink','0','1','islink','转向链接','输入则访问该内容自动转跳改此链接',NULL,'0','0','1','0','0','12','1'), ('56','3','datetime','2','1','inputtime','发布时间','','array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','0','0','5','1'), ('57','3','readpoint','0','0','readpoint','阅读收费','',NULL,'0','0','1','0','0','14','1'), ('58','3','relation','0','0','relation','相关文章','',NULL,'0','0','1','1','0','0','1'), ('59','3','pages','0','0','pages','分页字数','留空或者填“0”则默认1000字','array (\r\n  \'defaultvalue\' => \'1000\',\r\n)','0','0','1','0','0','7','1'), ('60','3','groupid','0','0','groupids_view','阅读权限','',NULL,'0','0','1','1','0','0','1'), ('61','3','template','2','0','template','内容页模板','',NULL,'0','0','1','1','0','0','1'), ('62','3','box','0','0','allow_comment','允许评论','','array (\r\n  \'options\' => \'允许评论|1\\r\\n不允许评论|0\',\r\n  \'defaultvalue\' => \'1\',\r\n)','0','0','1','0','0','13','1'), ('63','3','downfile','0','0','downfile','文件上传','','array (\n  \'defaultvalue\' => \'\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'\',\n  \'boxtype\' => \'\',\n  \'outputtype\' => \'\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'png,gif,jpg,jpeg,bmp\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'0\',\n  \'pathlist\' => \'0\',\n)','0','0','1','0','1','9','1'), ('75','1','datetime','2','1','readtime','最后阅读','','array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','1','1','0','1'), ('76','2','datetime','2','1','readtime','最后阅读','','array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','1','1','0','1'), ('77','3','datetime','2','1','readtime','最后阅读','','array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','1','1','0','1'), ('78','1','number','2','1','read','点击数','','array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','0','0','11','1'), ('79','2','number','2','1','read','点击数','','array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','0','0','12','1'), ('80','3','number','2','1','read','点击数','','array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','0','0','11','1'), ('81','1','number','2','1','reply','回复数',NULL,'array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','1','0','0','1'), ('82','2','number','2','1','reply','回复数',NULL,'array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','1','0','0','1'), ('83','3','number','2','1','reply','回复数',NULL,'array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','1','0','0','1'), ('86','2','images','0','0','jietu','应用截图','','array (\n  \'defaultvalue\' => \'\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'\',\n  \'boxtype\' => \'\',\n  \'fieldtype\' => \'\',\n  \'outputtype\' => \'\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'gif|jpg|jpeg|png|bmp\',\n  \'upload_number\' => \'10\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'0\',\n)','0','0','0','0','1','9','1'), ('111','1','text','0','1','subtitle','副标题',NULL,NULL,'0','0','1','1','0','0','1'), ('112','1','number','2','1','replytime','最后回复时间',NULL,NULL,'0','0','1','1','0','0','1'), ('113','1','number','2','1','replyuid','最后回复会员ID',NULL,NULL,'0','0','1','1','0','0','1'), ('114','1','number','2','1','replyname','最后回复会员名',NULL,NULL,'0','0','1','1','0','0','1'), ('115','1','textarea','0','0','subcontent','副内容',NULL,NULL,'0','0','1','1','0','0','1'), ('116','2','text','0','1','subtitle','副标题','','','0','0','1','1','0','0','1'), ('117','2','number','2','1','replytime','最后回复时间','','','0','0','1','1','0','0','1'), ('118','2','number','2','1','replyuid','最后回复会员ID','','','0','0','1','1','0','0','1'), ('119','2','number','2','1','replyname','最后回复会员名','','','0','0','1','1','0','0','1'), ('120','2','textarea','0','0','subcontent','副内容','','','0','0','1','1','0','0','1'), ('121','3','text','0','1','subtitle','副标题','','','0','0','1','1','0','0','1'), ('122','3','number','2','1','replytime','最后回复时间','','','0','0','1','1','0','0','1'), ('123','3','number','2','1','replyuid','最后回复会员ID','','','0','0','1','1','0','0','1'), ('124','3','number','2','1','replyname','最后回复会员名','','','0','0','1','1','0','0','1'), ('125','3','textarea','0','0','subcontent','副内容','','','0','0','1','1','0','0','1'), ('126','4','catid','2','1','catid','栏目','请选择栏目',NULL,'0','0','1','0','1','1','1'), ('127','4','typeid','0','1','typeid','类别',NULL,NULL,'0','0','1','1','1','0','1'), ('128','4','title','2','1','title','标题','请输入标题',NULL,'1','100','1','0','1','2','1'), ('129','4','keyword','0','1','keywords','关键词','多关键词之间用空格或者“,”隔开',NULL,'0','200','1','0','0','3','1'), ('130','4','textarea','0','1','description','摘要','',NULL,'0','200','1','0','0','4','1'), ('131','4','datetime','2','1','readtime','最后阅读',NULL,'array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','1','1','0','1'), ('132','4','datetime','2','1','updatetime','更新时间',NULL,'array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','1','0','0','1'), ('133','4','textarea','0','0','content','内容',NULL,NULL,'0','0','1','0','1','6','1'), ('134','4','image','0','1','thumb','缩略图',NULL,NULL,'0','0','1','0','1','8','1'), ('135','4','text','2','1','url','URL',NULL,NULL,'0','0','1','1','0','0','1'), ('136','4','number','2','1','listorder','排序',NULL,'array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','0','0','11','1'), ('137','4','number','2','1','read','点击数',NULL,'array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','0','0','12','1');
INSERT INTO `kf_neirong_moxing_ziduan` VALUES ('138','4','number','2','1','reply','回复数',NULL,'array (\r\n  \'defaultvalue\' => \'0\',\r\n)','0','0','1','1','0','0','1'), ('139','4','box','0','1','status','状态',NULL,NULL,'0','0','1','1','0','0','1'), ('140','4','text','2','1','username','用户名',NULL,NULL,'0','0','1','1','0','0','1'), ('141','4','islink','0','1','islink','转向链接','输入则访问该内容自动转跳改此链接',NULL,'0','0','1','0','0','13','1'), ('142','4','datetime','2','1','inputtime','发布时间',NULL,'array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)','0','0','1','0','0','5','1'), ('143','4','readpoint','0','0','readpoint','阅读收费',NULL,NULL,'0','0','1','0','0','15','1'), ('144','4','relation','0','0','relation','相关文章',NULL,NULL,'0','0','1','1','0','0','1'), ('145','4','pages','0','0','pages','分页字数','留空或者填“0”则默认1000字','array (\r\n  \'defaultvalue\' => \'1000\',\r\n)','0','0','1','0','0','7','1'), ('146','4','groupid','0','0','groupids_view','阅读权限',NULL,NULL,'0','0','1','1','1','0','0'), ('147','4','template','2','0','template','内容页模板',NULL,NULL,'0','0','1','1','0','0','1'), ('148','4','box','0','0','allow_comment','允许评论',NULL,'array (\r\n  \'options\' => \'允许评论|1\\r\\n不允许评论|0\',\r\n  \'defaultvalue\' => \'1\',\r\n)','0','0','1','0','0','14','1'), ('149','4','downfile','0','0','downfile','文件上传',NULL,'array (\r\n  \'defaultfile\' => \'3\',\r\n  \'maxfile\' => \'10\',\r\n  \'options\' => \'直接下载|1\\r\\n跳转下载|0\',\r\n  \'defaultvalue\' => \'1\',\r\n)','0','0','1','0','1','10','1'), ('150','4','text','0','1','subtitle','副标题','使用空格分隔',NULL,'0','0','1','0','0','2','1'), ('151','4','number','2','1','replytime','最后回复时间',NULL,NULL,'0','0','1','1','0','0','1'), ('152','4','number','2','1','replyuid','最后回复会员ID',NULL,NULL,'0','0','1','1','0','0','1'), ('153','4','number','2','1','replyname','最后回复会员名',NULL,NULL,'0','0','1','1','0','0','1'), ('154','4','textarea','0','0','subcontent','副内容',NULL,NULL,'0','0','1','1','0','0','1'), ('155','1','box','2','1','jinghua','精华','','array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','1','0','15','1'), ('156','1','box','2','1','dingzhi','顶置','','array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','1','0','16','1'), ('157','2','box','2','1','jinghua','精华','','array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','1','0','15','1'), ('158','2','box','2','1','dingzhi','顶置','','array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','1','0','16','1'), ('159','3','box','2','1','jinghua','精华','','array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','1','0','15','1'), ('160','3','box','2','1','dingzhi','顶置','','array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','1','0','16','1'), ('161','4','box','2','1','jinghua','精华','','array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','0','0','16','1'), ('162','4','box','2','1','dingzhi','顶置','','array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','0','0','17','1'), ('163','1','box','2','1','tongzhiwo','有评论通知我','','array (\n  \'defaultvalue\' => \'1\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'通知|1\r\n不通知|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','0','0','17','1'), ('164','2','box','2','1','tongzhiwo','有评论通知我','','array (\n  \'defaultvalue\' => \'1\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'通知|1\r\n不通知|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','0','0','17','1'), ('165','3','box','2','1','tongzhiwo','有评论通知我','','array (\n  \'defaultvalue\' => \'1\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'通知|1\r\n不通知|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','0','0','17','1'), ('166','4','box','2','1','tongzhiwo','有评论通知我','','array (\n  \'defaultvalue\' => \'1\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'通知|1\r\n不通知|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)','0','0','1','0','1','18','1'), ('167','4','images','0','0','tupian','图片上传','','array (\n  \'defaultvalue\' => \'\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'\',\n  \'boxtype\' => \'\',\n  \'fieldtype\' => \'\',\n  \'outputtype\' => \'\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'gif|jpg|jpeg|png|bmp\',\n  \'upload_number\' => \'10\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'0\',\n  \'input_kuan\' => \'\',\n  \'input_gao\' => \'\',\n)','0','0','0','0','1','9','1');
INSERT INTO `kf_peizhi` VALUES ('1','site_name','手机快范之家'), ('3','site_domain','{:domain}'), ('4','site_dir','/'), ('14','template_dir','default/'), ('2','site_namej','快范'), ('5','lonline','50'), ('6','regtype','1'), ('7','smsregtxt',''), ('8','fullmoney','0'), ('17','updir_thumb',''), ('18','member_photo_dir',''), ('19','member_photo_dir_thumb',''), ('11','isclose','0'), ('12','close_reason','网站升级中。。。'), ('13','closereg','0'), ('100','open_sql','1'), ('101','open_csrf','0'), ('102','admin_url',''), ('103','admin_islog','1'), ('104','minrefreshtime','200'), ('10','fullkeyvalue',''), ('16','updir_images',''), ('9','fullmerid',''), ('110','tjopen','1'), ('111','shengjiauto','7'), ('112','shengjiauto_time','1405699200'), ('20','cookie_pre','GER9S_'), ('21','cookie_domain',''), ('22','cookie_path',''), ('23','auth_key','r1ds23'), ('15','template_lifetime','0'), ('24','sms_key','qazqaz'), ('25','sms_zl','kf'), ('113','vs','99'), ('114','amountname','金币'), ('115','open_post','addslashes'), ('116','index_title',''), ('117','index_keywords',''), ('118','index_description',''), ('119','inwebway',''), ('120','hideusersid','');
INSERT INTO `kf_peizhi_mokuai` VALUES ('admin','后台模块','','0','1.0','','array ()','0','0','2010-10-18','2010-10-18'), ('huiyuan','会员模块','','0','1.0','','array (\n  \'amountname\' => \'金币\',\n  \'choosemodel\' => \'1\',\n  \'enablemailcheck\' => \'0\',\n  \'registerverify\' => \'0\',\n  \'showapppoint\' => \'1\',\n  \'rmb_point_rate\' => \'10\',\n  \'defualtpoint\' => \'10\',\n  \'defualtamount\' => \'0.1\',\n  \'showregprotocol\' => \'1\',\n  \'regprotocol\' => \'欢迎您注册成为KuaiFanCMS用户<br /> 请仔细阅读下面的协议，只有接受协议才能继续进行注册。 <br /> 1．服务条款的确认和接纳<br /> 　　KuaiFanCMS用户服务的所有权和运作权归KuaiFanCMS拥有。KuaiFanCMS所提供的服务将按照有关章程、服务条款和操作规则严格执行。用户通过注册程序点击“我同意” 按钮，即表示用户与KuaiFanCMS达成协议并接受所有的服务条款。<br /> 2． KuaiFanCMS服务简介<br /> 　　KuaiFanCMS通过国际互联网为用户提供新闻及文章浏览、图片浏览、软件下载、网上留言和BBS论坛等服务。<br /> 　　用户必须： <br /> 　　1)购置设备，包括个人电脑一台、调制解调器一个及配备上网装置。 <br /> 　　2)个人上网和支付与此服务有关的电话费用、网络费用。<br /> 　　用户同意： <br /> 　　1)提供及时、详尽及准确的个人资料。 <br /> 　　2)不断更新注册资料，符合及时、详尽、准确的要求。所有原始键入的资料将引用为注册资料。 <br /> 　　3)用户同意遵守《中华人民共和国保守国家秘密法》、《中华人民共和国计算机信息系统安全保护条例》、《计算机软件保护条例》等有关计算机及互联网规定的法律和法规、实施办法。在任何情况下，KuaiFanCMS合理地认为用户的行为可能违反上述法律、法规，KuaiFanCMS可以在任何时候，不经事先通知终止向该用户提供服务。用户应了解国际互联网的无国界性，应特别注意遵守当地所有有关的法律和法规。<br /> 3． 服务条款的修改<br /> 　　KuaiFanCMS会不定时地修改服务条款，服务条款一旦发生变动，将会在相关页面上提示修改内容。如果您同意改动，则再一次点击“我同意”按钮。 如果您不接受，则及时取消您的用户使用服务资格。<br /> 4． 服务修订<br /> 　　KuaiFanCMS保留随时修改或中断服务而不需知照用户的权利。KuaiFanCMS行使修改或中断服务的权利，不需对用户或第三方负责。<br /> 5． 用户隐私制度<br /> 　　尊重用户个人隐私是KuaiFanCMS的 基本政策。KuaiFanCMS不会公开、编辑或透露用户的注册信息，除非有法律许可要求，或KuaiFanCMS在诚信的基础上认为透露这些信息在以下三种情况是必要的： <br /> 　　1)遵守有关法律规定，遵从合法服务程序。 <br /> 　　2)保持维护KuaiFanCMS的商标所有权。 <br /> 　　3)在紧急情况下竭力维护用户个人和社会大众的隐私安全。 <br /> 　　4)符合其他相关的要求。 <br /> 6．用户的帐号，密码和安全性<br /> 　　一旦注册成功成为KuaiFanCMS用户，您将得到一个密码和帐号。如果您不保管好自己的帐号和密码安全，将对因此产生的后果负全部责任。另外，每个用户都要对其帐户中的所有活动和事件负全责。您可随时根据指示改变您的密码，也可以结束旧的帐户重开一个新帐户。用户同意若发现任何非法使用用户帐号或安全漏洞的情况，立即通知KuaiFanCMS。<br /> 7． 免责条款<br /> 　　用户明确同意网站服务的使用由用户个人承担风险。 　　 <br /> 　　KuaiFanCMS不作任何类型的担保，不担保服务一定能满足用户的要求，也不担保服务不会受中断，对服务的及时性，安全性，出错发生都不作担保。用户理解并接受：任何通过KuaiFanCMS服务取得的信息资料的可靠性取决于用户自己，用户自己承担所有风险和责任。 <br /> 8．有限责任<br /> 　　KuaiFanCMS对任何直接、间接、偶然、特殊及继起的损害不负责任。<br /> 9． 不提供零售和商业性服务 <br /> 　　用户使用网站服务的权利是个人的。用户只能是一个单独的个体而不能是一个公司或实体商业性组织。用户承诺不经KuaiFanCMS同意，不能利用网站服务进行销售或其他商业用途。<br /> 10．用户责任 <br /> 　　用户单独承担传输内容的责任。用户必须遵循： <br /> 　　1)从中国境内向外传输技术性资料时必须符合中国有关法规。 <br /> 　　2)使用网站服务不作非法用途。 <br /> 　　3)不干扰或混乱网络服务。 <br /> 　　4)不在论坛BBS或留言簿发表任何与政治相关的信息。 <br /> 　　5)遵守所有使用网站服务的网络协议、规定、程序和惯例。<br /> 　　6)不得利用本站危害国家安全、泄露国家秘密，不得侵犯国家社会集体的和公民的合法权益。<br /> 　　7)不得利用本站制作、复制和传播下列信息： <br /> 　　　1、煽动抗拒、破坏宪法和法律、行政法规实施的；<br /> 　　　2、煽动颠覆国家政权，推翻社会主义制度的；<br /> 　　　3、煽动分裂国家、破坏国家统一的；<br /> 　　　4、煽动民族仇恨、民族歧视，破坏民族团结的；<br /> 　　　5、捏造或者歪曲事实，散布谣言，扰乱社会秩序的；<br /> 　　　6、宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的；<br /> 　　　7、公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的；<br /> 　　　8、损害国家机关信誉的；<br /> 　　　9、其他违反宪法和法律行政法规的；<br /> 　　　10、进行商业广告行为的。<br /> 　　用户不能传输任何教唆他人构成犯罪行为的资料；不能传输长国内不利条件和涉及国家安全的资料；不能传输任何不符合当地法规、国家法律和国际法 律的资料。未经许可而非法进入其它电脑系统是禁止的。若用户的行为不符合以上的条款，KuaiFanCMS将取消用户服务帐号。<br /> 11．网站内容的所有权<br /> 　　KuaiFanCMS定义的内容包括：文字、软件、声音、相片、录象、图表；在广告中全部内容；电子邮件的全部内容；KuaiFanCMS为用户提供的商业信息。所有这些内容受版权、商标、标签和其它财产所有权法律的保护。所以，用户只能在KuaiFanCMS和广告商授权下才能使用这些内容，而不能擅自复制、篡改这些内容、或创造与内容有关的派生产品。<br /> 12．附加信息服务<br /> 　　用户在享用KuaiFanCMS提供的免费服务的同时，同意接受KuaiFanCMS提供的各类附加信息服务。<br /> 13．解释权<br /> 　　本注册协议的解释权归KuaiFanCMS所有。如果其中有任何条款与国家的有关法律相抵触，则以国家法律的明文规定为准。\',\n)','0','0','2010-09-06','2010-09-06'), ('duanxin','短信系统','&amp;c=duanxin&amp;a=peizhi','1','1.0','','array (\n  \'zhuce\' => \'[b]用户注册[/b][br]  发短信免费注册、取回密码：[br]  编辑以下短信格式: [br]  ① [b]kf[/b] [br]  ② [b]kf#密码[/b] [br]  ③ [b]kf#用户名#密码[/b] [br]  发送至以下任意一个通道:[br]  通道1:[url=sms:18978941931?body=kf]18978941931[/url][br]  通道2:[u]未开通[/u][br]  用户名:为你的手机号码。(注意:如果您使用格式③发送的用户名已存在则注册失败)[br]  密码:为手机号码后6位。(注意:如果你已经注册系统会把你的密码更改为手机号码后6位)[br]  短信内容写错将无法注册成功或更新密码，如果受移动短信中心延迟发送，请耐心等待，或向不同通道号码发信息。[br]  系统不会回复你任何信息，注册或更新密码不收取任何费用！请放心！\',\n  \'zhuce_open\' => \'2\',\n  \'zhaohui\' => \'[b]取回用户密码[/b][br]  发送短信取回密码：[br]  编辑以下短信格式: [br]  ① [b]kf[/b] [br]  ② [b]kf#密码[/b] [br]  发送至以下任意一个通道:[br]  通道1:[url=sms:18978941931?body=kf]18978941931[/url][br]  通道2:[u]未开通[/u][br]  用户名:为你的手机号码[br]  密码:为手机号码后6位。(注意:如果你已经注册系统会把你的密码更改为手机号码后6位)[br]  短信内容写错将无法注册成功或更新密码，如果受移动短信中心延迟发送，请耐心等待，或向不同通道号码发信息。[br]  系统不会回复你任何信息，注册或更新密码不收取任何费用！请放心！\',\n  \'zhaohui_open\' => \'2\',\n)','99','0','0000-00-00','0000-00-00'), ('chongzhi','订单充值','&amp;c=chongzhi','1','1.0','','array ()','98','0','0000-00-00','0000-00-00'), ('sousuo','全站搜索','&amp;c=sousuo','1','1.0','','array ()','100','0','0000-00-00','0000-00-00'), ('muban','模块风格','&amp;c=muban','1','1.0','','array ()','101','0','0000-00-00','0000-00-00'), ('lianjie','友情链接','&amp;c=lianjie','1','1.0','','array ()','97','0','0000-00-00','0000-00-00'), ('guanggao','广告系统','&amp;c=guanggao','1','1.0','','array ()','96','0','0000-00-00','0000-00-00'), ('toupiao','投票系统','','0','1.0','','array ()','93','0','0000-00-00','0000-00-00'), ('xinqing','内容心情','&amp;c=xinqing','1','1.0','','array ()','94','0','0000-00-00','0000-00-00'), ('fenlei','分类信息','','0','1.0','','array ()','0','0','0000-00-00','0000-00-00'), ('kongjian','空间系统','','0','1.0','','array ()','92','0','0000-00-00','0000-00-00'), ('youxi','游戏系统','','0','1.0','','array ()','91','0','0000-00-00','0000-00-00'), ('shangcheng','商城系统','','0','1.0','','array ()','90','0','0000-00-00','0000-00-00'), ('jiazu','家族系统','','0','1.0','','array ()','89','0','0000-00-00','0000-00-00'), ('shequ','社区系统','','0','1.0','','array ()','88','0','0000-00-00','0000-00-00'), ('xinxi','信息系统','&amp;c=xinxi','1','1.0',' ','array ()','95','0','0000-00-00','0000-00-00'), ('index','首页模块','','0','1.0','首页排版模块','array ()','0','0','0000-00-00','0000-00-00'), ('banben','版本识别',NULL,'0','1.0',NULL,'array (\n  \'isvs1\' => \'0\',\n  \'isvs2\' => \'0\',\n  \'isvs3\' => \'0\',\n  \'isvs4\' => \'3\',\n  \'isvs5\' => \'3\',\n  \'isAuto\' => \'0\',\n  \'isAndroidOS\' => \'3\',\n  \'isBlackBerryOS\' => \'2\',\n  \'isPalmOS\' => \'2\',\n  \'isSymbianOS\' => \'2\',\n  \'isWindowsMobileOS\' => \'2\',\n  \'isWindowsPhoneOS\' => \'2\',\n  \'isiOS\' => \'3\',\n  \'isMeeGoOS\' => \'2\',\n  \'isMaemoOS\' => \'1\',\n  \'isJavaOS\' => \'1\',\n  \'iswebOS\' => \'2\',\n  \'isbadaOS\' => \'2\',\n  \'isBREWOS\' => \'1\',\n  \'isOther\' => \'2\',\n  \'isTablet\' => \'4\',\n  \'isIE\' => \'5\',\n  \'isFirefox\' => \'5\',\n  \'isChrome\' => \'5\',\n  \'isSafari\' => \'5\',\n  \'isOpera\' => \'5\',\n  \'iswebOther\' => \'5\',\n)','0','0','0000-00-00','0000-00-00'), ('fujian','附件系统',NULL,'0','1.0',NULL,'array (\n  \'upload_maxsize\' => \'2048\',\n  \'watermark_enable\' => \'0\',\n  \'watermark_minwidth\' => \'300\',\n  \'watermark_minheight\' => \'300\',\n  \'watermark_pct\' => \'85\',\n  \'watermark_quality\' => \'80\',\n  \'watermark_pos\' => \'9\',\n  \'dosubmit\' => \'提交修改\',\n  \'watermark_img\' => \'uploadfiles/content/water/mark.png\',\n  \'thumb_width\' => \'\',\n  \'thumb_height\' => \'\',\n)','0','0','0000-00-00','0000-00-00'), ('page','伪静态页','&amp;c=page','1','1.0',NULL,'array (\n  \'KF_index\' => \n  array (\n    \'file\' => \'index.php\',\n    \'rewrite\' => \'index.html\',\n    \'url-1\' => \'0\',\n    \'url-2\' => \'0\',\n    \'url-3\' => \'0\',\n    \'url-4\' => \'0\',\n    \'url-5\' => \'0\',\n    \'alias\' => \'KF_index\',\n    \'body\' => \'网站首页\',\n  ),\n  \'KF_paibanpage\' => \n  array (\n    \'file\' => \'index.php\',\n    \'rewrite\' => \'index/($id).html\',\n    \'url-1\' => \'0\',\n    \'url-2\' => \'0\',\n    \'url-3\' => \'0\',\n    \'url-4\' => \'0\',\n    \'url-5\' => \'0\',\n    \'alias\' => \'KF_paibanpage\',\n    \'body\' => \'新建页面\',\n  ),\n  \'KF_neironglist\' => \n  array (\n    \'file\' => \'index.php\',\n    \'rewrite\' => \'list-($catid)-($page|1).html\',\n    \'url-1\' => \'0\',\n    \'url-2\' => \'0\',\n    \'url-3\' => \'0\',\n    \'url-4\' => \'0\',\n    \'url-5\' => \'0\',\n    \'alias\' => \'KF_neironglist\',\n    \'body\' => \'内容列表\',\n  ),\n  \'KF_neirongshow\' => \n  array (\n    \'file\' => \'index.php\',\n    \'rewrite\' => \'show-($catid)-($id)-($p|1).html\',\n    \'url-1\' => \'0\',\n    \'url-2\' => \'0\',\n    \'url-3\' => \'0\',\n    \'url-4\' => \'0\',\n    \'url-5\' => \'0\',\n    \'alias\' => \'KF_neirongshow\',\n    \'body\' => \'内容详情\',\n  ),\n  \'KF_neirongreply\' => \n  array (\n    \'file\' => \'index.php\',\n    \'rewrite\' => \'reply-($catid)-($id)-($page|1).html\',\n    \'url-1\' => \'0\',\n    \'url-2\' => \'0\',\n    \'url-3\' => \'0\',\n    \'url-4\' => \'0\',\n    \'url-5\' => \'0\',\n    \'alias\' => \'KF_neirongreply\',\n    \'body\' => \'评论列表\',\n  ),\n)','102','0','0000-00-00','0000-00-00'), ('biaoqing','表情系统','&amp;c=biaoqing','1','1.0',NULL,NULL,'103','0','0000-00-00','0000-00-00'), ('pinglun','评论系统','','0','1.0','','array (\n  \'pinglun_guest\' => \'1\',\n  \'pinglun_check\' => \'0\',\n  \'pinglun_code\' => \'0\',\n  \'pinglun_add_point\' => \'1\',\n  \'pinglun_del_point\' => \'2\',\n  \'pinglun_guest_del\' => \'1\',\n  \'pinglun_format\' => \'jpg|jpeg|gif|png|png|rar|zip\',\n  \'pinglun_format_num\' => \'5\',\n  \'pinglun_auser\' => \'0\',\n  \'pinglun_ubb\' => \'0\',\n  \'pinglun_browser\' => \'0\',\n)','0','0','0000-00-00','0000-00-00'), ('yanzhengma', '验证码', '', '0', '1.0', null, 'array (\n  \'zhuce\' => \'1\',\n  \'denglu\' => \'0\',\n  \'zhaohui\' => \'1\',\n  \'fabu\' => \'0\',\n  \'pinglun\' => \'0\',\n  \'xinxi\' => \'1\',\n  \'houtai\' => \'0\',\n)', '0', '0', '0000-00-00', '0000-00-00');
INSERT INTO `kf_peizhi_mokuai_url` VALUES ('1','huiyuan','1','会员中心','&amp;m=huiyuan'), ('2','huiyuan','2','会员登录','&amp;m=huiyuan&amp;c=denglu'), ('3','huiyuan','3','会员注册','&amp;m=huiyuan&amp;c=zhuce'), ('4','huiyuan','4','注册条款','&amp;m=huiyuan&amp;c=tiaokuan'), ('5','huiyuan','5','找回密码','&amp;m=huiyuan&amp;c=zhaohui'), ('6','huiyuan','6','修改邮箱/密码','&amp;m=huiyuan&amp;c=zhanghao&amp;a=mima'), ('7','huiyuan','7','自助升级','&amp;m=huiyuan&amp;c=zhanghao&amp;a=shengji'), ('8','huiyuan','10','修改头像','&amp;m=huiyuan&amp;c=zhanghao&amp;a=touxiang'), ('9','huiyuan','11','修改信息','&amp;m=huiyuan&amp;c=zhanghao&amp;a=xinxi'), ('10','huiyuan','12','修改支付密码','&amp;m=huiyuan&amp;c=zhanghao&amp;a=zhifumima'), ('11','chongzhi','1','我的订单','&amp;m=dingdan'), ('12','chongzhi','2','在线充值','&amp;m=dingdan&amp;c=chongzhi'), ('13','chongzhi','3','账户记录','&amp;m=dingdan&amp;c=jilu'), ('14','chongzhi','4','积分购买/兑换','&amp;m=dingdan&amp;c=duihuan'), ('15','lianjie','1','友情链接','&amp;m=lianjie'), ('16','sousuo','1','搜索首页','&amp;m=sousuo'), ('17','xinxi','1','发送短信息','&amp;m=xinxi&amp;c=fasong'), ('18','xinxi','2','收件箱','&amp;m=xinxi&amp;c=shoujian'), ('19','xinxi','3','发件箱','&amp;m=xinxi&amp;c=fajian'), ('20','xinxi','4','系统信息','&amp;m=xinxi&amp;c=xitong'), ('21','index','0','网站首页','&amp;m=index'), ('22','admin','0','后台地址','&amp;m=admin'), ('23','huiyuan','9','用户组','&amp;m=huiyuan&amp;c=zu'), ('24','huiyuan','8','会员动态','&amp;m=huiyuan&amp;c=dongtai');
INSERT INTO `kf_youxiang` VALUES ('1','mail','','array (\n  \'smtp_servers\' => \'smtp.163.com\',\n  \'smtp_username\' => \'18978941931\',\n  \'smtp_password\' => \'18978941931\',\n  \'smtp_from\' => \'18978941931@163.com\',\n  \'smtp_port\' => \'25\',\n  \'smtp_method\' => \'1\',\n)','1'), ('2','rule','','array (\n  \'mail_set_reg\' => \'0\',\n  \'mail_set_editpwd\' => \'0\',\n  \'mail_set_renzheng\' => \'0\',\n  \'mail_set_zhaohui\' => \'0\',\n  \'mail_set_order\' => \'0\',\n  \'mail_set_payment\' => \'0\',\n)','1'), ('3','templates','reg','array (\r\n  \'smtp_title\' => \'恭喜你成为{site_name}的会员\',\r\n  \'smtp_body\' => \'恭喜你成为{site_name}的会员<br/> 你的用户名是：{username}<br/> 你的密码是：{password}<br/> 此邮件由系统自动发出，请勿回复！\',\r\n)','1'), ('4','templates','editpwd','array (\n  \'smtp_title\' => \'密码修改成功！\',\n  \'smtp_body\' => \'亲爱的会员：<br />  你已经成功修改密码，新密码为：{newpassword}。<br />  此信息由系统自动发送，请勿回复！\',\n)','1'), ('5','templates','order','array (\n  \'smtp_title\' => \'充值订单添加成功\',\n  \'smtp_body\' => \'亲爱的会员：<br /> 你的订单已添加成功，请及时付款，订单: “{title}(订单号:{oid})”，付款类型为：{paymenttpye}，应付金额为：{amount} 。<br /> 付款后系统将为您自动开通相关服务。<br /> 请登录<a href=\"{site_domain}\" target=\"_blank\">会员中心</a>详细查看。<br /> 此信息由系统自动发送，请勿回复！\',\n)','1'), ('6','templates','payment','array (\n  \'smtp_title\' => \'您的订单“{title}(订单号:{oid})”付款成功！\',\n  \'smtp_body\' => \'亲爱的会员：<br /> 您的订单“{title}(订单号:{oid})”已经付款成功。<br /> 请登录<a href=\"{site_domain}\" target=\"_blank\">会员中心</a>详细查看。<br /> 此信息由系统自动发送，请勿回复！\',\n)','1'), ('7','templates','renzheng','array (\n  \'smtp_title\' => \'欢迎您注册成为{site_name}用户\',\n  \'smtp_body\' => \'欢迎您注册成为{site_name}用户，您的账号需要邮箱认证，点击下面链接进行认证：{click}<br /> 或者将网址复制到浏览器：{url} (本链接24小时内有效)\',\n)','1'), ('8','templates','zhaohui','array (\r\n  \'smtp_title\' => \'{site_name}密码找回\',\r\n  \'smtp_body\' => \'{site_name}密码找回，您的新密码是：{userpass}\',\r\n)','1');
INSERT INTO `kf_zhifu` VALUES ('1','银行转账','zhuanzhang','银行转账','银行转账或者汇款成功后请联系客服为您手动充值',NULL,NULL,'0','0','0','0','1.0.0',NULL,NULL,'1'), ('2','神州行卡充值','shenzhouxing','神州行充值卡充值','','array (\n  \'key\' => \'\',\n)','array (\r\n  \'key\' => \'商户密匙\',\r\n)','20','1','0','0','1.0.0','','神州行卡分成:84.00%<br/>\r\n<a href=\"index.php?m=shenzhouxing&amp;allow=GET[allow]&amp;vs=GET[vs]\">神州行充值管理中心</a><br/>','1');
INSERT INTO `kf_huiyuan_xunzhang` VALUES ('1','0','0','0','fenlei','社区论坛','1397834724','array (\n  \'body\' => \'社区论坛类荣誉勋章\',\n)'), ('2','1','0','0','xunzhang','优秀版主','1397834822','array (\n  \'body\' => \'历届十大优秀版主\',\n  \'catid_cn\' => \'社区论坛\',\n)'), ('3','1','0','0','xunzhang','社区模范','1397834855','array (\n  \'body\' => \'\',\n  \'catid_cn\' => \'社区论坛\',\n)'), ('4','1','0','0','xunzhang','社区帅哥','1397836093','array (\n  \'body\' => \'\',\n  \'catid_cn\' => \'社区论坛\',\n)'), ('5','1','0','0','xunzhang','时尚达人','1397836158','array (\n  \'body\' => \'\',\n  \'catid_cn\' => \'社区论坛\',\n)'), ('6','1','0','0','xunzhang','社区美女','1397836190','array (\n  \'body\' => \'\',\n  \'catid_cn\' => \'社区论坛\',\n)');
