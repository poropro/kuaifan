{#include file="common/header.tpl" title_top="1" title="广告位管理"#}

[<a href="{#$admin_indexurl#}&amp;c=guanggao">广告位管理</a>]<br/>
{#$url = get_link('a')#}
-------------<br/>
{#if $smarty.get.pp < 2#}
	<u>广告位信息</u><br/>
	类型:{#fenleixing($guanggaowei.type)#}<br/>
	名称:{#$guanggaowei.title#}<br/>
	{#if $guanggaowei.description#}描述:{#$guanggaowei.description#}<br/>{#/if#}
	操作:<a href="{#$url#}&amp;a=edit">修改</a>.<a href="{#$url#}&amp;a=del">删除</a><br/>
	-------------<br/>
{#/if#}
<a href="{#$url#}&amp;a=ggadd"><u>添加广告</u></a><br/>

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

{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:guanggao,排序:listorder DESC" where="{#$wheresql#}"#}
ID.名称(点击数)<br/>
{#foreach from=$lists item=list#}
	[<a href="{#$url#}&amp;a=ggdel&amp;id={#$list.id#}">删</a>]<a href="{#$url#}&amp;a=ggedit&amp;id={#$list.id#}">{#$list.id#}.{#$list.title#}</a>({#$list.clicks#})<br/>
{#foreachelse#}
	<u>没有任何广告,请<a href="{#$url#}&amp;a=ggadd">添加广告</a></u><br/>
{#/foreach#}

{#$pagelist#}
<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
