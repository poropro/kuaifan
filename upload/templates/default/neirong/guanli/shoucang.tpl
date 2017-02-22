{#include file="common/header.tpl" title="我的收藏列表"#}

<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>

-------------<br/>
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

{#kuaifan_shoucang set="列表名:lists,ID列表:idlist,会员ID:{#$userid#},显示数目:20,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}

ID.标题(添加时间)<br/>
{#foreach from=$lists item=list#}
	{#$list.id#}.[<a href="{#get_link('del')#}&amp;del={#$list.id#}">删</a>]<a href="{#$list.url#}">{#$list.title#}</a>({#$list.adddate|date_format:"%Y-%m-%d %H:%M:%S"#})<br/>
{#foreachelse#}
	没有任何收藏记录。<br/>
{#/foreach#}

{#$pagelist#}
{#if $idlist#}
	<br/>*<a href="{#get_link('del')#}&amp;del={#$idlist#}">删除本页收藏</a>
{#/if#}
<br/>*<a href="{#get_link('del')#}&amp;del=all">删除全部收藏</a>

<br/>


{#kuaifan tongji="查看自己" title="收藏的内容"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
