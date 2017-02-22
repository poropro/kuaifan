{#include file="common/header.tpl" title_top="1" title="短信配置"#}

	<a href="{#kuaifan getlinks='vs'#}&amp;l=duanxin&amp;a=zhuce&amp;m=explain&amp;go_url={#urlencode(get_link('','&'))#}">系统使用说明</a><br/>

	{#form set="头" notvs="1"#}

	{#if $dosubmit == '1'#}<b>修改成功！</b><br/>-------------<br/>{#/if#}
	{#if $dosubmit == '2'#}<b>修改失败！</b><br/>-------------<br/>{#/if#}
	
	效验码:<br/>
	{#form set="输入框|名称:sms_key{#$TIME2#}" data_value=$sms.sms_key#}<br/>
	短信指令:<br/>
	{#form set="输入框|名称:sms_zl{#$TIME2#}" data_value=$sms.sms_zl#}<br/>
	-------------<br/>
	
	短信注册说明:<br/>
	{#form set="输入框|名称:zhuce{#$TIME2#}" data_value=$peizhi.zhuce vs="1"#}
	{#form set="文本框|名称:zhuce{#$TIME2#}" data_value=$peizhi.zhuce notvs="1"#}<br/>
	短信注册:{#form set="列表框|名称:zhuce_open{#$TIME2#}" list="关闭:0,开通:2" default=$peizhi.zhuce_open#}<br/>
	默认短信注册:{#form set="列表框|名称:zhuce_sms{#$TIME2#}" list="否:0,是:1" default=$peizhi.zhuce_sms#}<br/>
	.<br/>
	短信找回密码说明:<br/>
	{#form set="输入框|名称:zhaohui{#$TIME2#}" data_value=$peizhi.zhaohui vs="1"#}
	{#form set="文本框|名称:zhaohui{#$TIME2#}" data_value=$peizhi.zhaohui notvs="1"#}<br/>
	短信找回密码:{#form set="列表框|名称:zhaohui_open{#$TIME2#}" list="关闭:0,开通:2" default=$peizhi.zhaohui_open#}<br/>


	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='sms_zl' value='$(sms_zl{#$TIME2#})'/>
	  <postfield name='sms_key' value='$(sms_key{#$TIME2#})'/>
	  <postfield name='zhuce' value='$(zhuce{#$TIME2#})'/>
	  <postfield name='zhuce_open' value='$(zhuce_open{#$TIME2#})'/>
	  <postfield name='zhuce_sms' value='$(zhuce_sms{#$TIME2#})'/>
	  <postfield name='zhaohui' value='$(zhaohui{#$TIME2#})'/>
	  <postfield name='zhaohui_open' value='$(zhaohui_open{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	<a href="{#kuaifan getlink='a'#}&amp;a=yulan">点此预览说明效果</a><br/>
	注明:说明均支持<a href="{#kuaifan getlinks='vs'#}&amp;l=paiban&amp;a=ubb&amp;m=explain&amp;go_url={#urlencode(get_link('','&'))#}">ubb语法</a>。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
