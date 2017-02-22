{#include file="common/header.tpl" title_top="1" title="评论管理"#}

[评论管理中心]<br/>
-------------<br/>
<a href="{#kuaifan getlink='config'#}&amp;config=1">评论相关配置</a><br/>
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

{#if $smarty.get.status=='0'#}
<a href="{#get_link('status')#}">全部</a>|未审核
{#else#}
全部|<a href="{#get_link('status')#}&amp;status=0">未审核</a>
{#/if#}<br/>

评论/原文标题<br/>
{#kuaifan_neirong_pinglun set="列表名:lists,显示数目:15,状态:GET[status],搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp,原文标题:title"#}
{#foreach from=$lists item=list#}
	{#$list._n#}.[<a href="{#get_link('id')#}&amp;id={#$list.id#}">详</a>]{#$list.content#}/<a href="{#get_link('pp|key|a|catid|edit')#}&amp;catid={#$list.commentid_.1#}&amp;edit={#$list.commentid_.2#}" target="_blank">{#$list.a.title#}</a><br/>
	{#if $list.status == 0#}
		<a href="{#get_link('to')#}&amp;to={#$list.id#}">审核通过</a>|<a href="{#get_link('del')#}&amp;del={#$list.id#}">不通过删除</a><br/>
	{#/if#}
	({#$list.username#}/{#$list.ip#}){#$list.creat_at|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
{#foreachelse#}
	没有任何评论。<br/>
{#/foreach#}

{#$pagelist#}
<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
