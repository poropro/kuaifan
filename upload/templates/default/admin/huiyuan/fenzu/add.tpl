{#include file="common/header.tpl" title_top="1" title="添加分组"#}

[添加分组]<br/>
<a href="{#kuaifan getlink='add'#}">返回</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}

会员组名称:(用户组名应该为2-8位之间)<br/>
{#form set="输入框|名称:info_name{#$TIME2#}"#}<br/>
积分小于:(积分点数应该为1-8位的数字)<br/>
{#form set="输入框|名称:info_point{#$TIME2#}"#}<br/>
星星数:(星星数应该为1-8位的数字)<br/>
{#form set="输入框|名称:info_starnum{#$TIME2#}"#}<br/>

升级价格:<br/>
包日:{#form set="输入框|名称:info_price_d{#$TIME2#},宽:5"#}包月:{#form set="输入框|名称:info_price_m{#$TIME2#},宽:5"#}包年:{#form set="输入框|名称:info_price_y{#$TIME2#},宽:5"#}<br/>

最大短消息数:<br/>
{#form set="输入框|名称:info_allowmessage{#$TIME2#}"#}<br/>

日最大投稿数:(日最大投稿数应该为1-8位的数字,0为不限制)<br/>
{#form set="输入框|名称:info_allowpostnum{#$TIME2#}"#}<br/>

用户名颜色:<br/>
{#form set="输入框|名称:info_usernamecolor{#$TIME2#}"#}<br/>

用户组图标:<br/>
{#form set="输入框|名称:info_icon{#$TIME2#}"#}<br/>

简洁描述:<br/>
{#form set="输入框|名称:info_description{#$TIME2#}" vs="1"#}
{#form set="文本框|名称:info_description{#$TIME2#}" notvs="1"#}<br/>

【内容权限】<br/>
允许投稿内容:{#form set="列表框|名称:'info_allowpost{#$TIME2#}'" list="否:0,是:1" default="0"#} <br/>
投稿不需审核:{#form set="列表框|名称:'info_allowpostverify{#$TIME2#}'" list="否:0,是:1" default="0"#} <br/>
允许自助升级:{#form set="列表框|名称:'info_allowupgrade{#$TIME2#}'" list="否:0,是:1" default="0"#} <br/>
允许发短消息:{#form set="列表框|名称:'info_allowsendmessage{#$TIME2#}'" list="否:0,是:1" default="0"#} <br/>
允许上传附件:{#form set="列表框|名称:'info_allowattachment{#$TIME2#}'" list="否:0,是:1" default="0"#} <br/>
搜索内容权限:{#form set="列表框|名称:'info_allowsearch{#$TIME2#}'" list="否:0,是:1" default="0"#} <br/>

【签名语法】<br/>
UBB语法:{#form set="列表框|名称:'info_qianmingubb{#$TIME2#}'" list="不支持:0,支持:1" default="0"#} <br/>
HTML语法:{#form set="列表框|名称:'info_qianminghtml{#$TIME2#}'" list="不支持:0,支持:1" default="0"#} <br/>
最多支持UBB语法个数:{#form set="输入框|名称:info_qianmingubbnum{#$TIME2#},宽:5" data_value="0"#}(0不限) <br/>
最多支持UBB语法个数:{#form set="输入框|名称:info_qianmingubbnum{#$TIME2#},宽:5" data_value="0"#}(0不限) <br/>
最长签名字数:{#form set="输入框|名称:info_qianminglength{#$TIME2#},宽:5" data_value="0"#}(0不限) <br/>


{#kuaifan vs="1" set="
	<anchor title='提交'>[提交保存]
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='info_name' value='$(info_name{#$TIME2#})'/>
	<postfield name='info_point' value='$(info_point{#$TIME2#})'/>
	<postfield name='info_starnum' value='$(info_starnum{#$TIME2#})'/>

	<postfield name='info_price_d' value='$(info_price_d{#$TIME2#})'/>
	<postfield name='info_price_m' value='$(info_price_m{#$TIME2#})'/>
	<postfield name='info_price_y' value='$(info_price_y{#$TIME2#})'/>

	<postfield name='info_allowmessage' value='$(info_allowmessage{#$TIME2#})'/>
	<postfield name='info_allowpostnum' value='$(info_allowpostnum{#$TIME2#})'/>
	<postfield name='info_usernamecolor' value='$(info_usernamecolor{#$TIME2#})'/>
	<postfield name='info_icon' value='$(info_icon{#$TIME2#})'/>
	<postfield name='info_description' value='$(info_description{#$TIME2#})'/>

	<postfield name='info_allowpost' value='$(info_allowpost{#$TIME2#})'/>
	<postfield name='info_allowpostverify' value='$(info_allowpostverify{#$TIME2#})'/>
	<postfield name='info_allowupgrade' value='$(info_allowupgrade{#$TIME2#})'/>
	<postfield name='info_allowsendmessage' value='$(info_allowsendmessage{#$TIME2#})'/>
	<postfield name='info_allowattachment' value='$(info_allowattachment{#$TIME2#})'/>
	<postfield name='info_allowsearch' value='$(info_allowsearch{#$TIME2#})'/>

	<postfield name='info_qianmingubb' value='$(info_qianmingubb{#$TIME2#})'/>
	<postfield name='info_qianminghtml' value='$(info_qianminghtml{#$TIME2#})'/>
	<postfield name='info_qianmingubbnum' value='$(info_qianmingubbnum{#$TIME2#})'/>
	<postfield name='info_qianminglength' value='$(info_qianminglength{#$TIME2#})'/>
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
注意:用户名颜色请填写6位颜色代码,不包含#符号; 如: ff0000。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
