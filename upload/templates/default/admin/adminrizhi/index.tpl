{#include file="common/header.tpl" title_top="1" title="后台日志"#}

[管理员操作日志]<br/>
-------------<br/>
{#if $smarty.get.name#}
	已选[{#$smarty.get.name#}]日志.<a href="{#get_link('pp|name')#}">取消选择</a><br/>
{#/if#}
{#form set="头|地址:'{#str_replace(':','\:',get_link('pp|key'))#}'" notvs="1"#}
{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}',宽:12"#}
{#kuaifan vs="1" set="
  <anchor>搜索
  <go href='{#get_link('pp|key')#}' method='post' accept-charset='utf-8'>
  <postfield name='key' value='$(key{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

{#kuaifan_adminrizhi set="列表名:lists,ID列表:idlist,管理员:GET[name],显示数目:20,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}
{#foreach from=$lists item=list#}
	{#$list.name#}({#$list.ip#}):<br/>
	{#$list.body_#}({#$list.time|date_format:"%Y-%m-%d %H:%M:%S"#})<br/>-----<br/>
{#foreachelse#}
	没有任何日志。<br/>
{#/foreach#}

{#$pagelist#}
{#if $idlist#}
	<br/>*<a href="{#get_link('del')#}&amp;del={#$idlist#}">删除本页日志</a>
{#/if#}
<br/>*<a href="{#get_link('del')#}&amp;del=all">删除全部日志</a>

<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
