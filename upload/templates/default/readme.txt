admin
#后台模板

common
#公共模板

explain
#帮助模板

huiyuan
#会员模板

index
#首页模板

neirong
#内容模板

statics
#静态文件

xinxi
#信息模块

warning.tpl
#提示页模板文件

  
【多版本模板文件】
在“.tpl”前面什么都不加则是WAP1.0默认模板，如：index.tpl
在“.tpl”前面加上“.html”则是WAP2.0版(3G彩版)的模板，如：index.html.tpl
在“.tpl”前面加上“.html5”则是触屏版的模板，如：index.html5.tpl
在“.tpl”前面加上“.pad”则是平板版的模板，如：index.pad.tpl
在“.tpl”前面加上“.web”则是电脑版的模板，如：index.web.tpl
注意：如果html5、pad、web版本所需要的模板文件不存在系统则自动使用3G彩版的模板；如果3G彩版所需要的模板文件也不存在系统则自动使用默认的模板。


【会员动态统计】
{kuaifan tongji="正在访问" title="自定义标题" vs="1"} 
tongji:必填
title:选填    （填写-1则没有，留空则当前页面标题）
vs:选填    （显示版本，默认或者填0则支持所有版本。1:简版、2:彩版、3:触屏版、4:平板、5:电脑版）


【对应版本显示】
{kuaifan set="自定义显示内容" vs="1"} 
set:必填
vs:选填    （显示版本，默认或者填0则支持所有版本。1:简版、2:彩版、3:触屏版、4:平板、5:电脑版）


【显示版本切换】
{kuaifan beta="1,3,2" beta_cn="WML简版,HTML触屏版,3G彩版" beta_cut="-" beta_dot="0" vs="1"}
beta:必填    （切换版本）
beta_cn:选填    （切换名称）
beta_cut:选填    （分割符号）
beta_dot:选填    （当前版本无链接）
vs:选填    （显示版本，默认或者填0则支持所有版本。1:简版、2:彩版、3:触屏版、4:平板、5:电脑版）

【页面执行时间】
{kuaifan runtime="0"} 
runtime:必填    （1返回带两位小数点的秒数，0返回带两位小数点的毫秒数）

【数据库执行次数】
{kuaifan dbnum="0"} 
dbnum:必填    （0返回执行查询次数，1返回执行更新次数，2返回执行插入次数，3返回执行统计次数，4返回执行删除次数，5返回所有次数）

【模板用使用ubb标签】
{ubb}ubb内容{/ubb}

【未读信息】
{weiduxx(0)}（未读信息）
{weiduxx(0,1)} （带链接未读信息）
{weiduxx(1)} （未读系统信息）
{weiduxx(1,1)} （带链接未读系统信息）

【在线会员】
{zxhys(XXX)} （XXX填写整数，表示XXX分钟内在线人数）
