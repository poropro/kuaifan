{#include file="common/header.tpl" title_top="1" title="排版设计中心"#}
[排版设计中心]<br/>

{#kuaifan_paiban set="列表名:paiban,显示数目:30,搜索变量名:key,标题长度:12,分类:0,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp,管理:1"#}
<a href="{#kuaifan getlink='a|pp|key'#}&amp;a=add">添加项目</a><br/>
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

排序|类型|名称<br/>
{#foreach from=$paiban item=list#}
	{#$list.order#}.<a href="{#kuaifan getlink='a|id'#}&amp;a=info&amp;id={#$list.id#}">[{#$list.type#}]{#$list.title#}</a><br/>
{#foreachelse#}
	没有任何信息,<a href="{#kuaifan getlink='a'#}&amp;a=add">点击添加</a><br/>
{#/foreach#}

{#$pagelist#}<br/>
[<a href="{#kuaifan getlink='a|paixu'#}&amp;a=info&amp;paixu=1">高级排序</a>]<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}