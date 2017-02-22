{#include file="common/header.tpl" title="会员分组"#}

<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>


------------- <br/>
{#$url = get_link('c|groupid')#}
{#foreach from=$group_list item=list#}
	{#$list._n#}.<a href="{#$url#}&amp;c=zuxiangqing&amp;groupid={#$list.groupid#}">{#$list.name#}</a> <br/>
	{#if $list.description#}描述: {#$list.description#} <br/>{#/if#}
{#foreachelse#}
	没有可自助升级的会员组。<br/>
	-----<br/>
{#/foreach#}		




{#kuaifan tongji="正在" title="查看用户组"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
