{#include file="common/header.tpl" title_top="1" title="修改广告位"#}

<a href="{#kuaifan getlink='a'#}&amp;a=guanggaowei">返回广告列表</a><br/>
-------------<br/>

	{#form set="头" notvs="1"#}

	类型:{#fenleixing($guanggaowei.type)#}<br/>
	名称:{#form set="输入框|名称:title{#$TIME2#}" data_value=$guanggaowei.title#}<br/>
	描述:{#form set="输入框|名称:description{#$TIME2#}" data_value=$guanggaowei.description#}<br/>


	{#kuaifan vs="1" set="
	  <anchor>[提交保存]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='title' value='$(title{#$TIME2#})'/>
	  <postfield name='description' value='$(description{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	注: ubb代码和wml代码广告无点击统计功能。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
