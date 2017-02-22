{#include file="common/header.tpl" title="会员自助升级"#}

<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>


------------- <br/>
{#if $group_id#}
	{#$url = get_link('a|groupid')#}
	<a href="{#$url#}&amp;a=shengji">返回列表</a><br/>.<br/>
	名称: {#$group_id.name#}<br/>
	{#if $group_id.description#}描述: {#$group_id.description#} <br/>{#/if#}
	星星数: <img src="{#$group_id.starimg#}" />({#$group_id.starnum#})<br/>
	积分要求: {#$group_id.point_back#}~{#$group_id.point#}<br/>
	最大短消息数: {#$group_id.allowmessage#}<br/>
	日最大投稿数: {#if $group_id.allowpostnum#}{#$group_id.allowpostnum#}{#else#}不限{#/if#}<br/>
	用户名颜色: {#if $group_id.usernamecolor#}
		<img src="index.php?m=api&amp;c=yanse&amp;a={#$group_id.usernamecolor#}"/>(颜色)
		<img src="index.php?m=api&amp;c=yansezi&amp;str={#$huiyuan.nickname|urlencode#}&amp;color={#$group_id.usernamecolor#}"/>(效果)
	{#else#}
		默认
	{#/if#}<br/>
	用户组图标: {#if $group_id.icon#}<img src="{#$group_id.icon#}"/>{#else#}默认{#/if#}<br/>
	允许投稿内容: {#if $group_id.allowpost#}是{#else#}否{#/if#}<br/>
	投稿不需审核: {#if $group_id.allowpostverify#}是{#else#}否{#/if#}<br/>
	允许自助升级: {#if $group_id.allowupgrade#}是{#else#}否{#/if#}<br/>
	允许发短消息: {#if $group_id.allowsendmessage#}是{#else#}否{#/if#}<br/>
	允许上传附件: {#if $group_id.allowattachment#}是{#else#}否{#/if#}<br/>
	搜索内容权限: {#if $group_id.allowsearch#}是{#else#}否{#/if#}<br/>
	资费: {#$group_id.price_y#}/年、{#$group_id.price_m#}/月、{#$group_id.price_d#}/日<br/>
	-----<br/>
	购买时限:<br/>

	{#form set="头" notvs="1"#}

	{#form set="输入框|名称:'upgradedate{#$TIME2#}',宽:12,值:1"#}{#form set="列表框|名称:'upgradetype{#$TIME2#}'" list="年:1,月:2,日:3"#}<br/>

	{#kuaifan vs="1" set="
	<anchor>立即购买开通
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='upgradedate' value='$(upgradedate{#$TIME2#})'/>
	<postfield name='upgradetype' value='$(upgradetype{#$TIME2#})'/>
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
	"#}
	{#form set="按钮|名称:dosubmit,值:立即购买开通" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
{#else#}
	{#$url = get_link('groupid')#}
	{#foreach from=$group_list item=list#}
		名称: {#$list.name#} <br/>
		{#if $list.description#}描述: {#$list.description#} <br/>{#/if#}
		资费: {#$list.price_y#}/年、{#$list.price_m#}/月、{#$list.price_d#}/日<br/>
		操作: <a href="{#$url#}&amp;groupid={#$list.groupid#}">查看详情&gt;&gt;</a><br/>
		-----<br/>
	{#foreachelse#}
		没有可自助升级的会员组。<br/>
		-----<br/>
	{#/foreach#}		
	注意: 自助升级后立即成为vip会员。<br/>
{#/if#}




{#kuaifan tongji="正在" title="查看自助升级"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
