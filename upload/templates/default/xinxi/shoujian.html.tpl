{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="我的收件箱"#}

<div class="daohang">
<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a>
</div>

<div class="pnpage">
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

{#kuaifan_xinxi set="列表名:lists,ID列表:idlist,会员名:{#$username#},类型:GET[status],显示数目:10,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp,最大短消息数:message|{#$group_id.allowmessage#}" where="folder='inbox'"#}
{#$message#}

<a href="{#get_link('del')#}&amp;del=reply">全部标记已读</a><br/>
<a href="{#get_link('status')#}">全部</a>|<a href="{#get_link('status')#}&amp;status=0">已读</a>|<a href="{#get_link('status')#}&amp;status=1">未读</a><br/>
发件人:标题(回复)(添加时间)<br/>
{#$url = get_link('vs|sid','','1')#}
{#foreach from=$lists item=list#}
	{#$list.reply_ok#}[<a href="{#get_link('del')#}&amp;del={#$list.messageid#}">删</a>]{#$list.send_from_id|colorname#}:<a href="{#$url#}&amp;m=xinxi&amp;c=fasong&amp;messageid={#$list.messageid#}">{#$list.subject#}</a>({#$list.reply_num#})({#$list.message_time|date_format:"%Y-%m-%d %H:%M:%S"#})<br/>
{#foreachelse#}
	没有任何收信记录。<br/>
{#/foreach#}

{#$pagelist#}
{#if $idlist#}
	<br/>*<a href="{#get_link('del')#}&amp;del={#$idlist#}">删除本页收件</a>
{#/if#}
<br/>*<a href="{#get_link('del')#}&amp;del=all">删除全部收件</a>

</div>


{#kuaifan tongji="查看自己" title="收藏的内容"#}
{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
