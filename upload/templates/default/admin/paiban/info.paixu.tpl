{#include file="common/header.tpl" title_top="1" title="排版排序"#}
[排版排序]<br/>

{#kuaifan_paiban set="列表名:paiban,显示数目:10000,标题长度:12,分类:{#$id#},填补字符:...,管理:1"#}
<a href="{#kuaifan getlink='paixu'#}">返回列表</a><br/>

排序|类型|名称<br/>
{#form set="头" notvs="1"#}

{#foreach from=$paiban item=list#}
	{#form set="输入框|名称:pai_{#$list.id#}{#$TIME2#},宽:3" data_value=$list.order#}{#$list.type#}|{#$list.title#}<br/>
{#foreachelse#}
	没有任何排版项目<br/>
{#/foreach#}

{#if $VS=='1'#}
  <anchor title="提交">[确定修改]
  <go href="{#get_link()#}" method="post" accept-charset="utf-8">
	{#foreach from=$paiban item=list#}
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