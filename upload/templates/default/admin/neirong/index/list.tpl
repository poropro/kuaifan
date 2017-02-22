{#include file="common/header.tpl" title_top="1" title="内容管理"#}

[内容管理中心]<br/>
<small><a href="{#kuaifan getlink='catid|pp|key'#}">内容管理</a> &gt; {#$lanmudb.title#}</small><br/>
-------------<br/>
<a href="{#kuaifan getlink='add|pp|key'#}&amp;add=1"><u>添加内容</u></a><br/>

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

<a href="{#kuaifan getlink='status'#}&amp;status=1">通过</a>|<a href="{#kuaifan getlink='status'#}&amp;status=0">退稿</a>|<a href="{#kuaifan getlink='status'#}&amp;status=98">草稿</a>|<a href="{#kuaifan getlink='status'#}&amp;status=99">审核中</a><br/>
{#if $smarty.get.status=='1'#}
	已选类型:通过<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='98'#}
	已选类型:草稿<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='99'#}
	已选类型:审核中<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='0'#}
	已选类型:退稿<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#/if#}

{#kuaifan_neirong set="列表名:lists,ID列表:idlists,显示数目:15,模型:{#$lanmudb.module#},分类:GET[catid],状态:GET[status],搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp,管理:1"#}
{#foreach from=$lists item=list#}
	{#$list._n#}.{#$list.status_cn#}<a href="{#kuaifan getlink='edit|status'#}&amp;edit={#$list.id#}" title="{#$list.title_#}">{#$list.title#}{#if $list.thumb#}[图]{#/if#}</a><br/>
{#foreachelse#}
	没有任何内容,<a href="{#kuaifan getlink='add|pp|key'#}&amp;add=1">点击添加</a><br/>.<br/>
{#/foreach#}

{#$pagelist#}
<br/>
{#if $idlists#}
*<a href="{#kuaifan getlink='dels'#}&amp;dels={#$idlists#}">删除本页内容</a> <br/>
{#/if#}

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
