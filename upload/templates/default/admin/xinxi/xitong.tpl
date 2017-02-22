{#include file="common/header.tpl" title_top="1" title="短信息"#}

[系统短信息]<br/>
<a href="{#$admin_indexurl#}&amp;c=xinxi">返回</a>|<a href="{#get_link('a')#}&amp;a=qunfa">群发新信息</a><br/>
-------------<br/>
{#form set="头|地址:'{#str_replace(':','\:',get_link('ppp|key'))#}'" notvs="1"#}
{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}',宽:12"#}
{#kuaifan vs="1" set="
  <anchor>搜索
  <go href='{#get_link('ppp|key')#}' method='post' accept-charset='utf-8'>
  <postfield name='key' value='$(key{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>


{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:ppp,数据表:xinxi_xitong,排序:inputtime DESC" where={#$wheresql#}#}

序号.标题<br/>
{#$url = get_link('a|id')#}
{#foreach from=$lists item=list#}
	{#$list._n#}.<a href="{#$url#}&amp;a=xtxiangqing&amp;id={#$list.id#}">{#$list.subject#}</a>→{#$list.groupid_cn#}<br/>
{#foreachelse#}
	没有任何信息记录。<br/>
{#/foreach#}

{#$pagelist#}
{#if $idlist#}
	<br/>*<a href="{#get_link('xtdel')#}&amp;xtdel={#$idlist#}">删除本页统计</a>
{#/if#}
<br/>*<a href="{#get_link('xtdel')#}&amp;xtdel=all">删除全部统计</a>

<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
