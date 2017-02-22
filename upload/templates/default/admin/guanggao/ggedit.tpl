{#include file="common/header.html.tpl" title_top="1" title="修改广告"#}

<a href="{#kuaifan getlink='a|id'#}&amp;a=guanggaowei">返回列表</a><br/>
{#if $guanggao.cattype=='images' || $guanggao.cattype=='link'#}
<a href="{#kuaifan getlink='a'#}&amp;a=jilu">广告点击纪录</a><br/>
{#/if#}
-------------<br/>
	{#form set="头|action:'{#str_replace(':', '\:', get_link())#}',enctype:'multipart/form-data'"#}

	广告名称:<br/>
	{#form set="输入框|名称:title" data_value=$guanggao.title#}<br/>
	上线时间:(广告开始投放时间)<br/>
	{#form set="输入框|名称:startdate" data_value=$guanggao.startdate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	下线时间:<br/>
	{#form set="输入框|名称:enddate" data_value=$guanggao.enddate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	广告状态:{#form set="列表框|名称:disabled" list="启用:0,禁用:1" default=$guanggao.disabled#}<br/>
	
	广告点击:{#form set="输入框|名称:clicks,宽:8" data_value=$guanggao.clicks#}<br/>
	.<br/>
	{#if $guanggao.cattype=='images'#}
		[图链配置]<br/>
		上传图片:{#form set="文件|名称:setting_upload_images"#}<br/>
		(或)地址:{#form set="输入框|名称:setting_images" data_value=$guanggao.setting.images#}<br/>
		链接地址:{#form set="输入框|名称:setting_link" data_value=$guanggao.setting.link#}<br/>
		每日点击赠送:{#form set="输入框|名称:setting_setmoney,宽:3" data_value=$guanggao.setting.setmoney#}{#form set="列表框|名称:setting_setmoney_type" list="积分:0,'{#$KUAIFAN.amountname#}':1"  default=$guanggao.setting.setmoney_type#}<br/>
	{#elseif $guanggao.cattype=='link'#}
		[链接配置]<br/>
		链接地址:{#form set="输入框|名称:setting_link" data_value=$guanggao.setting.link#}<br/>
		每日点击赠送:{#form set="输入框|名称:setting_setmoney,宽:3" data_value=$guanggao.setting.setmoney#}{#form set="列表框|名称:setting_setmoney_type" list="积分:0,'{#$KUAIFAN.amountname#}':1"  default=$guanggao.setting.setmoney_type#}<br/>
	{#elseif $guanggao.cattype=='ubb'#}
		UBB代码:<br/>
		{#form set="文本框|名称:setting_ubb" data_value=$guanggao.setting.ubb#}<br/>
	{#elseif $guanggao.cattype=='wml'#}
		WML代码:<br/>
		{#form set="文本框|名称:setting_wml" data_value=$guanggao.setting.wml#}<br/>
	{#/if#}
	
	{#form set="按钮|名称:dosubmit,值:提交保存"#}
	{#form set="尾"#}
	<br/>

{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
