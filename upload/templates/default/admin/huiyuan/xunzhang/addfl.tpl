{#include file="common/header.tpl" title_top="1" title=$title#}

[{#$title#}]<br/>
	<a href="{#$URL#}&amp;z=fenlei">返回</a><br/>
{#if $row.id#}
	<a href="{#$URL#}&amp;z=delfl&amp;id={#$row.id#}">删除此分类</a><br/>
{#/if#}
-------------<br/>
{#form set="头" notvs="1"#}

分类名称:<br/>
{#form set="输入框|名称:title{#$TIME2#}" data_value=$row.title#}<br/>
描述:<br/>
{#form set="输入框|名称:body{#$TIME2#}" data_value=$row.setting.body vs="1"#}
{#form set="文本框|名称:body{#$TIME2#}" data_value=$row.setting.body notvs="1"#}<br/>

{#kuaifan vs="1" set="
  <anchor title='提交'>[提交]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='title' value='$(title{#$TIME2#})'/>
  <postfield name='body' value='$(body{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
