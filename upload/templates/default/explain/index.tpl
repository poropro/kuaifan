{#include file="common/header.tpl" title="帮助中心" title_top=1#}

{#if $smarty.get.go_url#}
	<a href="{#$smarty.get.go_url|goto_url#}">返回来源地址</a>
{#else#}
	<a href="{#kf_url('index')#}">返回网站首页</a>
{#/if#}
<br/>-------------<br/>
{#kuaifan_bangzhu set="列表名:bangzhu,显示数目:15,l:GET[l],a:GET[a],搜索变量名:key,标题长度:15,分类:0,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:page"#}

{#if $get_val#}
	{#if $smarty.get.id#}
		<a href="{#kuaifan getlink='id'#}">返回列表</a><br/>
	{#/if#}
	[主题]:{#$get_val.title#}<br/>
	[版本]:{#$get_val.v#}<br/>
	[详情]:{#$get_val.body#}<br/>
	
{#else#}
	{#form set="头|地址:'{#str_replace(':','\:',get_link('page|key'))#}'" notvs="1"#}
	{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}',宽:12"#}
	{#kuaifan vs="1" set="
	  <anchor>搜索
	  <go href='{#get_link('page|key')#}' method='post' accept-charset='utf-8'>
	  <postfield name='key' value='$(key{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}

	{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	-------------<br/>
	{#foreach from=$bangzhu item=list#}
		{#$list._n#}.<a href="{#kuaifan getlink='id'#}&amp;id={#$list.id#}">{#if $list.l_cn#}[{#$list.l_cn#}]{#/if#}{#$list.title#}</a>(适合:{#$list.v#})<br/>
	{#foreachelse#}
		没有任何相关的帮助信息。<br/>
	{#/foreach#}

	{#$pagelist#}<br/>
{#/if#}
{#if $VS=='1'#}
	-------------<br/>
	切换:WML.<a href="{#kuaifan getlink='vs'#}&amp;vs=2">HTML</a><br/>
{#else#}
	<div style="background: #e97e00;border-top: 0px solid #ba5c18;color: #FC0;">
	切换: <a href="{#kuaifan getlink='vs'#}&amp;vs=1">WML</a>.HTML
	</div>
{#/if#}

程序版本:KuaiFan {#$KUAIFAN.version#}<br/>
{#include file="common/footer.tpl"#}