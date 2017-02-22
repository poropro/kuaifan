(一) 运行环境需求：
PHP 5.2 及以上 
MySQL 5.0 及以上

(二) 安装步骤：

(1) 文件夹结构说明
    rewrite  ~~~~~~~~~~~~ 伪静态说明和规则
    upgrade  ~~~~~~~~~~~~ 上一个版本升级数据目录
    upload   ~~~~~~~~~~~~ 网站根目录(程序目录)
    ├─ caches ~~~~~~~ 缓存目录
    ├─ include ~~~~~~ 程序主要引入目录
    ├─ install ~~~~~~ 安装目录
    ├─ kuaifan ~~~~~~ 模块存放目录
    │    ├─ api ~~~~~~~~ 接口存放目录
    │    ├─ module ~~~~~ 模块存放目录
    │    └─ resources ~~ 数据资源目录
    ├─ templates ~~~~ 模板目录
    │    ├─ default ~~~~~~~~~~~~ 默认模板
    │    ├─ templet_backup ~~~~~ 模板备份目录
    │    └─ templet_cache ~~~~~~ 模板缓存目录
    ├─ uploadfiles ~~~ 上传目录
    ├─ admin.php ~~~~~ 后台入口
    ├─ index.php ~~~~~ 快范系统入口
    ├─ robots.txt ~~~~ 蜘蛛协议
    └─ smsreg.php ~~~~ 短信注册接口文件

(2) Linux 或 Freebsd 服务器下安装方法。
    第一步：使用ftp工具，将upload文件夹内所有文件上传至你的网站空间。
    第二步：先确认将以下目录以及子目录属性设置为 (777) 可写模式。
        index.htm
        caches/*
        templates/*
        uploadfiles/*
    第三步：运行 http://yourwebsite/安装目录/install，填入安装相关信息与资料，完成安装！
    
(3) Windows 服务器下安装方法。
    第一步：使用ftp工具，将upload文件夹内所有文件上传至你的网站空间。
    第二步：运行 http://yourwebsite/安装目录/install，填入安装相关信息与资料，完成安装！
    
(4) 后台管理地址
	http://yourwebsite/安装目录/admin.php
        可在后台设置绑定后台域名,绑定之后只能通过http://绑定的域名/安装目录/admin.php

(5) 安装完成,请删除install文件夹

	
(三) 使用说明
	更多使用说明请登陆http://www.kuaifan.net/查看参考。

(四) 相关帮助:
     官方网站：http://www.kuaifan.net/
     官方论坛：http://bbs.kuaifan.net/
     旗舰网站：http://phpzzz.com/