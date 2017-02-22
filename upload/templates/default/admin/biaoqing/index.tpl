{#include file="common/header.tpl" title_top="1" title="表情管理"#}

[表情管理中心]<br/>
-------------<br/>
{#$url = get_link('a|id')#}
<a href="{#$url#}&amp;a=add"><u>添加表情</u></a>|<a href="{#$url#}&amp;a=fenlei">分类</a><br/>

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

{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:biaoqing,排序:{#$ordersql#}" where="{#$wheresql#}"#}
代号(分类)<br/>
{#$_url = get_link("hide")#}
{#foreach from=$lists item=list#}
	[<a href="{#$_url#}&amp;hide={#$list.id#}">{#if $list.is#}<b>隐</b>{#else#}显{#/if#}</a>]<a href="{#$url#}&amp;a=edit&amp;id={#$list.id#}">{#$list.em#}</a>{#$list.em|get_em#}({#$list.catid_cn#})<br/>
{#foreachelse#}
	<u>没有任何表情</u><br/>
{#/foreach#}

{#$pagelist#}
<br/>
[*<a href="{#kuaifan getlinks='vs'#}&amp;l=biaoqing&amp;a=shuoming&amp;m=explain&amp;go_url={#urlencode(get_link('','&'))#}">系统说明</a>*]<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
