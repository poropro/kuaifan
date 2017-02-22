{#include file="common/header.tpl" title_top="1" title="短信息"#}

[会员短信息]<br/>
<a href="{#get_link('a')#}&amp;a=danfa">单发信息</a><br/>
<a href="{#get_link('a')#}&amp;a=xitong">群发信息管理</a><br/>
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


{#kuaifan_xinxi set="列表名:lists,ID列表:idlist,显示数目:15,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}

序号.标题/发件人→收信人<br/>
{#$url = get_link('a|messageid')#}
{#foreach from=$lists item=list#}
	{#$list._n#}.{#$list.reply_ok#}{#if $list.folder=='outbox'#}(回收站){#/if#}<a href="{#$url#}&amp;a=xiangqing&amp;messageid={#$list.messageid#}">{#$list.subject#}</a>/{#$list.send_from_id#}→{#$list.send_to_id#}<br/>
{#foreachelse#}
	没有任何信息记录。<br/>
{#/foreach#}

{#$pagelist#}
{#if $idlist#}
	<br/>*<a href="{#get_link('del')#}&amp;del={#$idlist#}">删除本页统计</a>
{#/if#}
<br/>*<a href="{#get_link('del')#}&amp;del=all">删除全部统计</a>
<br/>*<a href="{#get_link('del')#}&amp;del=huishou">清空回收站信息</a>

<br/>注明:删除无法恢复。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
