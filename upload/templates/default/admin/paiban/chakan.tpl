{#kuaifan_paiban_show set="列表名:show,ID:GET[id],父级:1"#}
{#include file="common/header.tpl" title_top="1" title="排版设计中心"#}
[<a href="{#$admin_indexurl#}&amp;c=paiban">排版设计中心</a>]<br/>

-------------<br/>
编号: {#$show.id#}<br/>
类型: {#$show.type#}<br/>
名称: {#$show.title|strip_tags#}<br/>
位置:
<a href="{#kuaifan getlink='id|detail'#}">顶级</a>
{#foreach from=$show.parent item=list name=foo#}
	&gt;
	<a href="{#kuaifan getlink='id'#}&amp;id={#$list.id#}">{#$list.title|strip_tags#}</a>
{#/foreach#}<br/>
排序: {#$show.order#}<br/>
显隐: {#if $show.hide#}前台隐藏{#else#}前台显示{#/if#}<br/>
{#if $smarty.get.detail==1#}
	创建时间: {#$show.addtime|date_format:"%Y-%m-%d %H:%M"#}<br/>
	创建I P: {#$show.addip#}<br/>
	创建人员: {#$show.adminuser#}<br/>
	{#if $show.lasttime#}最后修改: {#$show.lastadmin#},{#$show.lasttime|date_format:"%Y-%m-%d %H:%M"#}<br/>{#/if#}
	<a href="{#kuaifan getlink='detail'#}">简单↑</a>{#else#}<a href="{#kuaifan getlink='detail'#}&amp;detail=1">详情↓</a>
{#/if#} |
<a href="{#kuaifan getlink='a'#}&amp;a=edit">编辑</a> |
<a href="{#kuaifan getlink='a'#}&amp;a=del">删除</a><br/>
{#if $show.type=='新的页面'#}
	-------------<br/>
	{#kuaifan_paiban set="列表名:paiban,显示数目:20,搜索变量名:keyn,标题长度:12,分类:GET[id],填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pn,管理:1"#}
	<a href="{#kuaifan getlink='a|pn|keyn'#}&amp;a=add">添加项目</a><br/>
	{#form set="头|地址:'{#str_replace(':','\:',get_link('pn|keyn'))#}'" notvs="1"#}
	{#form set="输入框|名称:keyn{#$TIME2#},值:'{#$smarty.request.keyn#}',宽:12"#}
	{#kuaifan vs="1" set="
	  <anchor>搜索
	  <go href='{#get_link('pn|keyn')#}' method='post' accept-charset='utf-8'>
	  <postfield name='keyn' value='$(keyn{#$TIME2#})'/>
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
{#/if#}


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}