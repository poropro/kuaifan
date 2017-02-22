{#include file="common/header.tpl" title_top="1" title="会员配置"#}

	[会员配置]<br/>
	{#form set="头" notvs="1"#}

	关闭会员注册:{#form set="列表框|名称:'closereg{#$TIME2#}'" list="是:1,否:0" default="0" data_value="{#$peizhi.closereg#}"#} <br/>
	注册选择模型:{#form set="列表框|名称:'info_choosemodel{#$TIME2#}'" list="否:0,是:1" default="0" data_value="{#$peizhi.choosemodel#}"#} <br/>
	新会员注册需要邮件验证:{#form set="列表框|名称:'info_enablemailcheck{#$TIME2#}'" list="否:0,是:1" default="0" data_value="{#$peizhi.enablemailcheck#}"#} <br/>
	*需填写<a href="{#$admin_indexurl#}&amp;c=youxiang">邮箱配置</a>; 开启后会员注册审核功能无效。<br/>
	新会员注册需要管理员审核:{#form set="列表框|名称:'info_registerverify{#$TIME2#}'" list="否:0,是:1" default="0" data_value="{#$peizhi.registerverify#}"#} <br/>
	前台显示会员sid标识:{#form set="列表框|名称:'hideusersid{#$TIME2#}'" list="显示:0,隐藏:1" default="0" data_value="{#$peizhi.hideusersid#}"#} <br/>
	.<br/>
	网站会员金币名称:{#form set="输入框|名称:'info_amountname{#$TIME2#}',宽:5" data_value="{#$peizhi.amountname#}"#} <br/>
	是否启用{#$peizhi.amountname#}购买积分:{#form set="列表框|名称:'info_showapppoint{#$TIME2#}'" list="否:0,是:1" default="0" data_value="{#$peizhi.showapppoint#}"#} <br/>
	1{#$peizhi.amountname#}购买积分数量:{#form set="输入框|名称:'info_rmb_point_rate{#$TIME2#}',宽:5" data_value="{#$peizhi.rmb_point_rate#}"#} <br/>
	新会员默认积分:{#form set="输入框|名称:'info_defualtpoint{#$TIME2#}',宽:5" data_value="{#$peizhi.defualtpoint#}"#} <br/>
	新会员注册默认赠送{#$peizhi.amountname#}:{#form set="输入框|名称:'info_defualtamount{#$TIME2#}',宽:5" data_value="{#$peizhi.defualtamount#}"#} <br/>
	.<br/>
	是否显示注册协议:{#form set="列表框|名称:'info_showregprotocol{#$TIME2#}'" list="否:0,是:1" default="0" data_value="{#$peizhi.showregprotocol#}"#} <br/>
	----------<br/>
	会员注册协议:<br/>
	{#form set="输入框|名称:info_regprotocol{#$TIME2#}" vs="1" data_value="{#$peizhi.regprotocol|nl2br#}"#}
	{#form set="文本框|名称:info_regprotocol{#$TIME2#}" notvs="1" data_value="{#$peizhi.regprotocol#}"#}<br/>
	
	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交保存]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
      <postfield name='closereg' value='$(closereg{#$TIME2#})'/>
      <postfield name='hideusersid' value='$(hideusersid{#$TIME2#})'/>
      <postfield name='info_choosemodel' value='$(info_choosemodel{#$TIME2#})'/>
      <postfield name='info_enablemailcheck' value='$(info_enablemailcheck{#$TIME2#})'/>
      <postfield name='info_registerverify' value='$(info_registerverify{#$TIME2#})'/>
      <postfield name='info_amountname' value='$(info_amountname{#$TIME2#})'/>
      <postfield name='info_showapppoint' value='$(info_showapppoint{#$TIME2#})'/>
      <postfield name='info_rmb_point_rate' value='$(info_rmb_point_rate{#$TIME2#})'/>
      <postfield name='info_defualtpoint' value='$(info_defualtpoint{#$TIME2#})'/>
      <postfield name='info_defualtamount' value='$(info_defualtamount{#$TIME2#})'/>
      <postfield name='info_showregprotocol' value='$(info_showregprotocol{#$TIME2#})'/>
      <postfield name='info_regprotocol' value='$(info_regprotocol{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
