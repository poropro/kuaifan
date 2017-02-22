{#include file="common/header.tpl" title_top="1" title="后台日志"#}

[会员访问统计]<br/>
{#if $KUAIFAN.tjopen=="1"#}
	统计功能:开|<a href="{#get_link('open')#}&amp;tjopen=0">关</a><br/>
{#else#}
	统计功能:<a href="{#get_link('open')#}&amp;tjopen=1">开</a>|关<br/>
{#/if#}
-------------<br/>
{#if $smarty.get.id#}
	已选[ID:{#$smarty.get.id#}]统计.<a href="{#get_link('pp|id')#}">取消选择</a><br/>
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

{#kuaifan_tongji set="列表名:lists,ID列表:idlist,会员ID:GET[id],显示数目:20,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}
{#foreach from=$lists item=list#}
	{#$list.username_#}({#$list.ip#}):<br/>
	{#$list.type#}<a href="{#$list.url#}">{#$list.title#}</a>({#$list.time|date_format:"%Y-%m-%d %H:%M:%S"#})<br/>-----<br/>
{#foreachelse#}
	没有任何统计记录。<br/>
{#/foreach#}

{#$pagelist#}
{#if $idlist#}
	<br/>*<a href="{#get_link('del')#}&amp;del={#$idlist#}">删除本页统计</a>
{#/if#}
<br/>*<a href="{#get_link('del')#}&amp;del=all">删除全部统计</a>

<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
