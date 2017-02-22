{#include file="common/header.tpl" title_top="1" title="字段排序"#}

[字段排序-{#$field.title#}]<br/>
<a href="{#kuaifan getlink='paixu'#}">返回管理</a><br/>
-------------<br/>
排序|别名|字段名(类型)<br/>
{#form set="头" notvs="1"#}
{#foreach from=$ziduan item=list#}
	{#form set="输入框|名称:pai_{#$list.id#}{#$TIME2#},宽:3" data_value=$list.listorder#}{#$list.name#}|{#$list.field#}({#$list.type#})<br/>
{#foreachelse#}
	没有任何字段<br/>
{#/foreach#}

{#if $VS=='1'#}
  <anchor title="提交">[确定修改]
  <go href="{#get_link()#}" method="post" accept-charset="utf-8">
	{#foreach from=$ziduan item=list#}
		<postfield name="pai_{#$list.id#}" value="$(pai_{#$list.id#}{#$TIME2#})"/>
	{#/foreach#}
	<postfield name="dosubmit" value="1"/>
  </go> </anchor>
{#/if#}
{#form set="按钮|名称:dosubmit,值:确定修改" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
