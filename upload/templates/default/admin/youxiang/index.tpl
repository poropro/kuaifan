{#include file="common/header.tpl" title_top="1" title="邮件配置"#}

{#if $smarty.get.thisname!=''#}
	<a href="{#kuaifan getlink='thisname|smstitle'#}">返回</a><br/>
	{#form set="头" notvs="1"#}
	[编辑邮件模板-{#$smarty.get.smstitle#}]<br/>
	<a href="{#kuaifan getlinks='vs'#}&amp;m=explain&amp;l=youjian&amp;a={#$smarty.get.thisname#}&amp;go_url={#urlencode(get_link('','&'))#}">=变量说明=</a><br/>
	邮件标题模版:<br/>
	{#form set="输入框|名称:smtp_title{#$TIME2#}" data_value=$muban.smtp_title#}<br/>
	邮件内容模版:<br/>
	{#form set="输入框|名称:smtp_body{#$TIME2#}" data_value=$muban.smtp_body vs="1"#}
	{#form set="文本框|名称:smtp_body{#$TIME2#}" data_value=$muban.smtp_body notvs="1"#}<br/>
	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='smtp_title' value='$(smtp_title{#$TIME2#})'/>
	  <postfield name='smtp_body' value='$(smtp_body{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	
	注明:标题和内容模板均支持wml语法。<br/>
{#else#}
	{#form set="头" notvs="1"#}

	1.通过连接 SMTP 服务器发送邮件<br/>
	SMTP服务器地址:(如：smtp.qq.com)<br/>
	{#form set="输入框|名称:smtp_servers{#$TIME2#}" data_value=$youxiang.smtp_servers#}<br/>
	SMTP服务帐户名:<br/>
	{#form set="输入框|名称:smtp_username{#$TIME2#}" data_value=$youxiang.smtp_username#}<br/>
	SMTP服务密码:<br/>
	{#form set="密码框|名称:smtp_password{#$TIME2#}" data_value=$youxiang.smtp_password#}<br/>
	发信人邮件地址:<br/>
	{#form set="输入框|名称:smtp_from{#$TIME2#}" data_value=$youxiang.smtp_from#}<br/>
	SMTP 端口:(默认25)<br/>
	{#form set="输入框|名称:smtp_port{#$TIME2#}" data_value=$youxiang.smtp_port#}<br/>
	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='smtp_servers' value='$(smtp_servers{#$TIME2#})'/>
	  <postfield name='smtp_username' value='$(smtp_username{#$TIME2#})'/>
	  <postfield name='smtp_password' value='$(smtp_password{#$TIME2#})'/>
	  <postfield name='smtp_from' value='$(smtp_from{#$TIME2#})'/>
	  <postfield name='smtp_port' value='$(smtp_port{#$TIME2#})'/>
	  <postfield name='smtp_method' value='1'/>
	  <postfield name='subtype' value='1'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	{#form set="隐藏|名称:smtp_method,值:1" notvs="1"#}
	{#form set="隐藏|名称:subtype,值:1" notvs="1"#}
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	
	-------------	<br/>
	
	{#form set="头" notvs="1"#}

	2.发送测试邮件<br/>
	收件人地址:<br/>
	{#form set="输入框|名称:check_smtp{#$TIME2#}"#}<br/>
	{#kuaifan vs="1" set="
	  <anchor title='立即测试'>[立即测试]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='check_smtp' value='$(check_smtp{#$TIME2#})'/>
	  <postfield name='subtype' value='2'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	{#form set="隐藏|名称:subtype,值:2" notvs="1"#}
	{#form set="按钮|名称:dosubmit,值:立即测试" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	
	-------------	<br/>

	{#form set="头" notvs="1"#}
	3.发送规则<br/>
	1)<u>注册会员</u>:{#form set="列表框|名称:mail_set_reg{#$TIME2#}" list="通知:1,不通知:0" default="0" data_value=$guize.mail_set_reg#}<br/>
	注册会员成功后发送邮件通知 - <a href="{#kuaifan getlink='thisname|smstitle'#}&amp;thisname=reg&amp;smstitle={#'注册会员'|escape:url#}">模板</a><br/>
	
	2)<u>修改密码</u>:{#form set="列表框|名称:mail_set_editpwd{#$TIME2#}" list="通知:1,不通知:0" default="0" data_value=$guize.mail_set_editpwd#}<br/>
	会员修改密码后是否邮件通知 - <a href="{#kuaifan getlink='thisname|smstitle'#}&amp;thisname=editpwd&amp;smstitle={#'修改密码'|escape:url#}">模板</a><br/>

	3)<u>邮箱认证</u>:{#form set="列表框|名称:mail_set_renzheng{#$TIME2#}" list="通知:1,不通知:0" default="0" data_value=$guize.mail_set_renzheng#}<br/>
	开通注册邮件验证的认证通知 - <a href="{#kuaifan getlink='thisname|smstitle'#}&amp;thisname=renzheng&amp;smstitle={#'邮箱认证'|escape:url#}">模板</a><br/>

	4)<u>密码找回</u>:{#form set="列表框|名称:mail_set_zhaohui{#$TIME2#}" list="开通:1,不开通:0" default="0" data_value=$guize.mail_set_zhaohui#}<br/>
	会员找回新密码后的邮件通知 - <a href="{#kuaifan getlink='thisname|smstitle'#}&amp;thisname=zhaohui&amp;smstitle={#'密码找回'|escape:url#}">模板</a><br/>

	5)<u>新增订单</u>:{#form set="列表框|名称:mail_set_order{#$TIME2#}" list="通知:1,不通知:0" default="0" data_value=$guize.mail_set_order#}<br/>
	会员下支付订单是否邮件通知 - <a href="{#kuaifan getlink='thisname|smstitle'#}&amp;thisname=order&amp;smstitle={#'新增订单'|escape:url#}">模板</a><br/>
	
	6)<u>付款成功</u>:{#form set="列表框|名称:mail_set_payment{#$TIME2#}" list="通知:1,不通知:0" default="0" data_value=$guize.mail_set_payment#}<br/>
	会员订单付款完成是否邮件通知 - <a href="{#kuaifan getlink='thisname|smstitle'#}&amp;thisname=payment&amp;smstitle={#'付款成功'|escape:url#}">模板</a><br/>

	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='mail_set_reg' value='$(mail_set_reg{#$TIME2#})'/>
	  <postfield name='mail_set_editpwd' value='$(mail_set_editpwd{#$TIME2#})'/>
	  <postfield name='mail_set_renzheng' value='$(mail_set_renzheng{#$TIME2#})'/>
	  <postfield name='mail_set_zhaohui' value='$(mail_set_zhaohui{#$TIME2#})'/>
	  <postfield name='mail_set_order' value='$(mail_set_order{#$TIME2#})'/>
	  <postfield name='mail_set_payment' value='$(mail_set_payment{#$TIME2#})'/>
	  <postfield name='subtype' value='3'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="隐藏|名称:subtype,值:3" notvs="1"#}
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	
{#/if#}	
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
