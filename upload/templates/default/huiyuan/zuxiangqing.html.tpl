{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="会员分组"#}

<div class="daohang">
<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a>
</div>


<div class="pnpage">
<a href="{#kuaifan getlink='c|groupid'#}&amp;c=zu">返回列表</a><br/>
名称: {#$group_id.name#}<br/>
{#if $group_id.description#}描述: {#$group_id.description#} <br/>{#/if#}
星星数: <img src="{#$group_id.starimg#}" />({#$group_id.starnum#})<br/>
积分要求: {#$group_id.point_back#}~{#$group_id.point#}<br/>
最大短消息数: {#$group_id.allowmessage#}<br/>
日最大投稿数: {#if $group_id.allowpostnum#}{#$group_id.allowpostnum#}{#else#}不限{#/if#}<br/>
用户名颜色: {#if $group_id.usernamecolor#}
	<img src="index.php?m=api&amp;c=yanse&amp;a={#$group_id.usernamecolor#}"/>(颜色)
	<img src="index.php?m=api&amp;c=yansezi&amp;str={#'用户名'|urlencode#}&amp;color={#$group_id.usernamecolor#}"/>(效果)
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
</div>



{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
