{#include file="common/header.tpl" title_top="1" title="会员管理"#}

[会员管理]<br/>
<a href="{#get_link('a')#}&amp;a=add">添加会员</a><br/>
-------------<br/>
{#if $smarty.get.userid#}
	已选[ID:{#$smarty.get.userid#}]会员.<a href="{#get_link('pp|userid')#}">取消选择</a><br/>
{#/if#}
{#if $smarty.get.username#}
	已选[用户名:{#$smarty.get.username#}]会员.<a href="{#get_link('pp|username')#}">取消选择</a><br/>
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
{#kuaifan_huiyuan set="列表名:lists,ID列表:idlist,会员ID:GET[userid],会员名:GET[username],显示数目:20,搜索变量名:key,深度搜索:1,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}
{#foreach from=$lists item=list#}
	{#if $list.vip_img#}<img src="{#$list.vip_img#}" alt="{#$list.vip_cn#}"/>{#/if#}{#if $list.islock#}<b>[锁]</b>{#/if#}<a href="{#get_link('a|userid')#}&amp;a=xiangqing&amp;userid={#$list.userid#}">{#$list.userid#}.{#$list.username#}|{#$list|colorname#}</a><br/>
	{#$grouplist[$list.groupid]#},{#$modellistarr[$list.modelid]#},最后登录:{#if $list.lastdate#}{#$list.lastdate|date_format:"%Y-%m-%d %H:%M:%S"#}{#else#}无{#/if#}<br/>-----<br/>
{#foreachelse#}
	没有任何会员。<br/>
{#/foreach#}

{#$pagelist#}
<br/>

{#if $smarty.get.pp <= 1 || $smarty.post.pp <= 1#}
	<a href="{#get_link('a')#}&amp;a=updatesid">一键更新会员sid</a><br/>
{#/if#}

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
