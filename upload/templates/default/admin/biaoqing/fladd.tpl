{#include file="common/header.tpl" title_top="1" title=$TITLE#}

<a href="{#kuaifan getlink='a|catid'#}&amp;a=fenlei">返回列表</a><br/>
{#if $fenlei#}
<a href="{#kuaifan getlink='a'#}&amp;a=fldel">!删除此分类</a><br/>
{#/if#}
-------------<br/>

	{#form set="头" notvs="1"#}

	名称:{#form set="输入框|名称:title{#$TIME2#}" data_value={#$fenlei.title#}#}<br/>
	排序:{#form set="输入框|名称:listorder{#$TIME2#}" data_value={#$fenlei.listorder#}#}(越大越靠前)<br/>


	{#kuaifan vs="1" set="
	  <anchor>[提交保存]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='title' value='$(title{#$TIME2#})'/>
	  <postfield name='listorder' value='$(listorder{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}	
	{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
