{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="签名说明"#}

<div class="daohang">
<a href="{#kuaifan getlink='a'#}&amp;a=xinxi">返回修改资料</a>
</div>

<div class="pnpage">
{#foreach from=$group_list item=list#}
	【{#$list.name#}】 <br/>
	ubb语法:{#if $list.qianmingubb#}<u>支持</u>{#else#}不支持{#/if#} <br/>
	html语法:{#if $list.qianminghtml#}<u>支持</u>{#else#}不支持{#/if#} <br/>
	最多支持UBB语法个数:{#if $list.qianmingubbnum#}<u>{#$list.qianmingubbnum#}个</u>{#else#}不限{#/if#} <br/>
	最长签名字数:{#if $list.qianminglength#}<u>{#$list.qianminglength#}个字</u>{#else#}不限{#/if#} <br/>
	{#if $groupid == $list.groupid#}<u><b>*你当前属于此等级</b></u><br/>{#/if#}
{#foreachelse#}
	没有会员组。<br/>
{#/foreach#}		
</div>


{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
