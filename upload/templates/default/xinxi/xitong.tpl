{#include file="common/header.tpl" title="系统信息"#}

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

{#kuaifan_xinxi_xitong set="列表名:lists,会员ID:{#$userid#},会员组:{#$groupid#},类型:GET[status],显示数目:10,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}
<a href="{#get_link('del')#}&amp;del=reply">全部标记已读</a><br/>
<a href="{#get_link('status')#}">全部</a>|<a href="{#get_link('status')#}&amp;status=0">已读</a>|<a href="{#get_link('status')#}&amp;status=1">未读</a><br/>
标题(发送时间)<br/>
{#$url = get_link('id')#}
{#foreach from=$lists item=list#}
	{#if $list.reply_ok#}【{#$list.reply_ok#}】{#/if#}<a href="{#$url#}&amp;id={#$list.id#}">{#$list.subject#}</a>({#$list.inputtime|date_format:"%Y-%m-%d %H:%M:%S"#})<br/>
{#foreachelse#}
	没有任何系统信息。<br/>
{#/foreach#}

{#$pagelist#}

<br/>


{#kuaifan tongji="正在查看" title="系统信息"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
