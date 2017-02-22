{#include file="common/header.tpl" title_top="1" title="账户记录"#}


<a href="{#$admin_indexurl#}&amp;c=chongzhi">在线充值</a>&gt;账户记录<br/>
------------- <br/>
{#form set="头|地址:'{#str_replace(':','\:',get_link('pp|key|status'))#}'" notvs="1"#}
{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}',宽:12"#}
{#kuaifan vs="1" set="
  <anchor>搜索
  <go href='{#get_link('pp|key')#}' method='post' accept-charset='utf-8'>
  <postfield name='key' value='$(key{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>

{#form set="头" notvs="1"#}
{#form set="输入框|名称:userid{#$TIME2#},值:'{#$smarty.request.userid#}',宽:12"#}
{#kuaifan vs="1" set="
  <anchor>会员ID
  <go href='{#get_link('userid')#}' method='post' accept-charset='utf-8'>
  <postfield name='userid' value='$(userid{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:会员ID" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>

{#kuaifan_jiangfa set="列表名:lists,会员ID:GET[userid],币种:GET[type],类型:GET[add],搜索变量名:key,显示数目:10,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}


{#if $smarty.get.type=='0'#}
	币种:<a href="{#kuaifan getlink='type'#}">不限</a>|<a href="{#kuaifan getlink='type'#}&amp;type=1">{#$KUAIFAN.amountname#}</a>|积分<br/>
{#elseif $smarty.get.type=='1'#}
	币种:<a href="{#kuaifan getlink='type'#}">不限</a>|{#$KUAIFAN.amountname#}|<a href="{#kuaifan getlink='type'#}&amp;type=0">积分</a><br/>
{#else#}
	币种:<a href="{#kuaifan getlink='type'#}">不限</a>|<a href="{#kuaifan getlink='type'#}&amp;type=1">{#$KUAIFAN.amountname#}</a>|<a href="{#kuaifan getlink='type'#}&amp;type=0">积分</a><br/>
{#/if#}

{#if $smarty.get.add=='add'#}
	类型:<a href="{#kuaifan getlink='add'#}">不限</a>|<a href="{#kuaifan getlink='add'#}&amp;add=cut">支出</a>|收入<br/>
{#elseif $smarty.get.add=='cut'#}
	类型:<a href="{#kuaifan getlink='add'#}">不限</a>|支出|<a href="{#kuaifan getlink='add'#}&amp;add=add">收入</a><br/>
{#else#}
	类型:<a href="{#kuaifan getlink='add'#}">不限</a>|<a href="{#kuaifan getlink='add'#}&amp;add=cut">支出</a>|<a href="{#kuaifan getlink='add'#}&amp;add=add">收入</a><br/>
{#/if#}

【账户记录】<br/>
{#$url = get_link('a|id')#}
{#foreach from=$lists item=list#}
	<a href="{#$url#}&amp;a=jiluinfo&amp;id={#$list.id#}">{#$list.title#}</a><br/>
	时间:{#$list.time|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	{#$list.type_cn2#}:{#$list.add_cn2#}{#$list.num#}{#$list.type_cn#}(余{#$list.nums#}{#$list.type_cn#})<br/>
	-----<br/>
{#foreachelse#}
	<u>没有任何记录。</u><br/>
{#/foreach#}

{#$pagelist#}<br/>
注明:括号里面的余额是消费以后的余额。<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}