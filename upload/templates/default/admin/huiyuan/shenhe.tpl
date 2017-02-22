{#include file="common/header.tpl" title_top="1" title="审核会员"#}

[审核会员]<br/>
-------------<br/>
{#if $smarty.get.userid#}
	已选[ID:{#$smarty.get.userid#}]会员.<a href="{#get_link('pp|userid')#}">取消选择</a><br/>
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
ID.用户名|昵称<br/>
{#kuaifan_huiyuan_shenhe set="列表名:lists,ID列表:idlist,会员ID:GET[userid],显示数目:20,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}
{#foreach from=$lists item=list#}
	<a href="{#get_link('a|userid')#}&amp;a=xiangqing_sh&amp;userid={#$list.userid#}">{#$list.userid#}.{#$list.username#}|{#$list.nickname|htmlspecialchars#}{#if $list.email_encrypt#}(邮箱){#/if#}</a><br/>
	注册时间:{#$list.regdate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	<a href="{#get_link('n|userid')#}&amp;userid={#$list.userid#}&amp;n=tg">通过</a> | <a href="{#get_link('n|userid')#}&amp;userid={#$list.userid#}&amp;n=btg">未通过</a><br/>
	-----<br/>
{#foreachelse#}
	没有任何等待审核的会员。<br/>
{#/foreach#}

{#$pagelist#}

<br/>
注明:昵称后面带有“(邮箱)”为邮箱认证注册。
<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
