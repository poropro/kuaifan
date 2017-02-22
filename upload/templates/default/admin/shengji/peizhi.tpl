{#include file="common/header.tpl" title_top="1" title="自动检测升级配置"#}

<a href="{#get_link('dosubmit|a')#}">返回升级</a><br/>

	{#form set="头" notvs="1"#}

	每隔(天)自动检测:<br/>
	{#form set="输入框|名称:shengjiauto{#$TIME2#}" data_value=$KUAIFAN.shengjiauto#}<br/>
	例如：输入7则每隔7天在后台自动检测新版本并提示，填写0则关闭自动检测。<br/>
	


	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交保存]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='shengjiauto' value='$(shengjiauto{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
