-- 主表
CREATE TABLE `$basic_table` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `catid` smallint(5) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL,
  `title` char(200) NOT NULL default '',
  `subtitle` varchar(255) NOT NULL default '',
  `style` char(24) NOT NULL default '',
  `thumb` varchar(600) NOT NULL default '',
  `keywords` char(200) NOT NULL default '',
  `description` char(255) NOT NULL default '',
  `posids` tinyint(1) unsigned NOT NULL default '0',
  `url` char(255) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL default '0',
  `status` tinyint(2) unsigned NOT NULL default '1',
  `sysadd` tinyint(1) unsigned NOT NULL default '0',
  `islink` varchar(255) NOT NULL default '',
  `username` char(20) NOT NULL,
  `reply` int(10) unsigned NOT NULL default '0',
  `replytime` int(10) unsigned NOT NULL default '0',
  `replyuid` int(10) unsigned NOT NULL default '0',
  `replyname` char(20) NOT NULL,
  `read` int(10) unsigned NOT NULL default '0',
  `readtime` int(10) unsigned NOT NULL default '0',
  `inputtime` int(10) unsigned NOT NULL default '0',
  `updatetime` int(10) unsigned NOT NULL default '0',
  `dingzhi` tinyint(3) unsigned NOT NULL default '0',
  `jinghua` tinyint(3) unsigned NOT NULL default '0',
  `tongzhiwo` tinyint(3) unsigned NOT NULL default '0',
  `site` int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) TYPE=MyISAM;

-- 从表
CREATE TABLE `$table_data` (
  `id` mediumint(8) unsigned default '0',
  `content` text NOT NULL,
  `subcontent` text NOT NULL,
  `downfile` text NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL default '0',
  `groupids_view` varchar(100) NOT NULL,
  `paginationtype` tinyint(1) NOT NULL,
  `pages` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL default '0',
  `allow_comment` tinyint(1) unsigned NOT NULL default '1',
  `relation` varchar(255) NOT NULL default '',
  `site` int(10) unsigned NOT NULL default '1',
  KEY `id` (`id`)
) TYPE=MyISAM;


INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'catid', '2', '1', 'catid', '栏目', '请选择栏目', null, '0', '0', '1', '0', '1', '1', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'typeid', '0', '1', 'typeid', '类别', null, null, '0', '0', '1', '1', '0', '1', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'title', '2', '1', 'title', '标题', '请输入标题', null, '1', '100', '1', '0', '2', '1', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'keyword', '0', '1', 'keywords', '关键词', '多关键词之间用空格或者“,”隔开', null, '0', '200', '1', '0', '3', '1', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'textarea', '0', '1', 'description', '摘要', null, null, '0', '200', '1', '0', '4', '1', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'datetime', '2', '1', 'readtime', '最后阅读', null, 'array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)', '0', '0', '1', '1', '0', '1', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'datetime', '2', '1', 'updatetime', '更新时间', null, 'array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)', '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'textarea', '0', '0', 'content', '内容', null, null, '0', '0', '1', '0', '6', '1', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'image', '0', '1', 'thumb', '缩略图', null, null, '0', '0', '1', '0', '8', '1', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'text', '2', '1', 'url', 'URL', null, null, '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'number', '2', '1', 'listorder', '排序', null, 'array (\r\n  \'defaultvalue\' => \'0\',\r\n)', '0', '0', '1', '0', '10', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'number', '2', '1', 'read', '点击数', null, 'array (\r\n  \'defaultvalue\' => \'0\',\r\n)', '0', '0', '1', '0', '11', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'number', '2', '1', 'reply', '回复数', null, 'array (\r\n  \'defaultvalue\' => \'0\',\r\n)', '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'box', '0', '1', 'status', '状态', null, null, '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'text', '2', '1', 'username', '用户名', null, null, '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'islink', '0', '1', 'islink', '转向链接', '输入则访问该内容自动转跳改此链接', null, '0', '0', '1', '0', '12', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'datetime', '2', '1', 'inputtime', '发布时间', null, 'array (\r\n  \'defaultvalue\' => \'Y-m-d H:i:s\',\r\n)', '0', '0', '1', '0', '5', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'readpoint', '0', '0', 'readpoint', '阅读收费', null, null, '0', '0', '1', '0', '14', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'relation', '0', '0', 'relation', '相关文章', null, null, '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'pages', '0', '0', 'pages', '分页字数', '留空或者填“0”则默认1000字', 'array (\r\n  \'defaultvalue\' => \'1000\',\r\n)', '0', '0', '1', '0', '7', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'groupid', '0', '0', 'groupids_view', '阅读权限', null, null, '0', '0', '1', '1', '0', '1', '0');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'template', '2', '0', 'template', '内容页模板', null, null, '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'box', '0', '0', 'allow_comment', '允许评论', null, 'array (\r\n  \'options\' => \'允许评论|1\\r\\n不允许评论|0\',\r\n  \'defaultvalue\' => \'1\',\r\n)', '0', '0', '1', '0', '13', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'downfile', '0', '0', 'downfile', '文件上传', null, 'array (\r\n  \'defaultfile\' => \'3\',\r\n  \'maxfile\' => \'10\',\r\n  \'options\' => \'直接下载|1\\r\\n跳转下载|0\',\r\n  \'defaultvalue\' => \'1\',\r\n)', '0', '0', '1', '0', '9', '1', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'text', '0', '1', 'subtitle', '副标题', null, null, '0', '0', '1', '1', '2', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'number', '2', '1', 'replytime', '最后回复时间', null, null, '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'number', '2', '1', 'replyuid', '最后回复会员ID', null, null, '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'text', '2', '1', 'replyname', '最后回复会员名', null, null, '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'textarea', '0', '0', 'subcontent', '副内容', null, null, '0', '0', '1', '1', '0', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'box', '2', '1', 'dingzhi', '顶置', '', 'array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)', '0', '0', '1', '1', '16', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'box', '2', '1', 'jinghua', '精华', '', 'array (\n  \'defaultvalue\' => \'0\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'是|1\r\n否|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)', '0', '0', '1', '1', '15', '0', '1');
INSERT INTO `$table_model_field` (`modelid`, `type`, `status`, `issystem`, `field`, `name`, `tips`, `setting`, `minlength`, `maxlength`, `del`, `hide`, `listorder`, `isadd`, `site`) VALUES($modelid, 'box', '2', '1', 'tongzhiwo', '有评论通知我', '', 'array (\n  \'defaultvalue\' => \'1\',\n  \'ispassword\' => \'\',\n  \'enablehtml\' => \'\',\n  \'options\' => \'通知|1\r\n不通知|0\',\n  \'boxtype\' => \'0\',\n  \'fieldtype\' => \'tinyint\',\n  \'outputtype\' => \'1\',\n  \'minnumber\' => \'0\',\n  \'maxnumber\' => \'0\',\n  \'decimaldigits\' => \'\',\n  \'format\' => \'\',\n  \'upload_allowext\' => \'\',\n  \'upload_number\' => \'\',\n  \'downloadtype\' => \'\',\n  \'pathlist\' => \'\',\n)', '0', '0', '1', '0', '17', '0', '1');