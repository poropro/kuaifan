{#include file="common/header.tpl" title_top="1" title=$title#}

[{#$title#}]<br/>
	<a href="{#$URL#}&amp;z=list">返回</a><br/>
{#if $row.id#}
	<a href="{#$URL#}&amp;z=delhy&amp;id={#$row.id#}">删除此颁发</a><br/>
{#/if#}
-------------<br/>
{#if $row.img#}({#$row.title#}:<img src="{#$row.img#}"/>)<br/>-------------<br/>{#/if#}

{#form set="头" notvs="1"#}

会员ID:<br/>
{#form set="输入框|名称:dataid{#$TIME2#}" data_value=$row.dataid#}<br/>
选择勋章:<br/>
{#kuaifan_pc set="列表名:lists,显示数目:1000,数据表:huiyuan_xunzhang,排序:intime DESC" where="type='xunzhang'"#}
{#if $lists#}
	<select name="catid{#$TIME2#}"{#if $row.catid && $smarty.get.vs==1#} value="{#$row.catid#}"{#/if#}>
	{#foreach from=$lists item=list#}
		<option value="{#$list.id#}"{#if $row.catid==$list.id && $smarty.get.vs>1#} selected="selected"{#/if#}>{#$list.title#}</option>
	{#/foreach#}
	</select>
{#else#}
	<u>无,请先<a href="{#$URL#}&amp;z=add">添加勋章</a></u>
{#/if#}<br/>
描述:<br/>
{#form set="输入框|名称:body{#$TIME2#}" data_value=$row.setting.body vs="1"#}
{#form set="文本框|名称:body{#$TIME2#}" data_value=$row.setting.body notvs="1"#}<br/>

{#kuaifan vs="1" set="
  <anchor title='提交'>[提交]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='dataid' value='$(dataid{#$TIME2#})'/>
  <postfield name='catid' value='$(catid{#$TIME2#})'/>
  <postfield name='body' value='$(body{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
