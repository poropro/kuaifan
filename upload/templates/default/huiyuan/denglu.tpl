{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

登录｜<a href="{#kuaifan getlink='c'#}&amp;c=zhuce">快速注册</a><br/>
------------- <br/>
{#if $smarty.get.go_url#}
	<a href="{#$smarty.get.go_url|goto_url#}">返回来源地址</a><br/>
{#/if#}
{#form set="头" notvs="1"#}
ID/用户名/手机号码/邮箱<br/>
{#form set="输入框|名称:'username'"#}<br/>
密码<br/>
{#form set="密码框|名称:'userpass'"#}<br/>

{#if $yzmpeizhi.denglu#}
	<img src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma" />
	<a href="{#kuaifan getlink='yanzhengma'#}">换一张</a><br/>
	{#form set="输入框|名称:'yanzhengma'"#}<br/>
{#/if#}

保存Cookies<br/>
{#form set="列表框|名称:'miandenglu'" list="保存7天:7,保存15天:15,保存30天:30,不保存:1"#}<br/>

{#kuaifan vs="1" set="
	<anchor>登录
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='username' value='$(username)'/>
	<postfield name='userpass' value='$(userpass)'/>
	<postfield name='miandenglu' value='$(miandenglu)'/>
	<postfield name='yanzhengma' value='$(yanzhengma)'/>
	<postfield name='ip' value='{#yanzhengmaip()#}'/>
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:登录" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

<a href="{#kuaifan getlink='c'#}&amp;c=zhaohui">忘记密码?</a> <a href="{#kuaifan getlink='c'#}&amp;c=zhuce">注册新用户</a><br/>

最新注册用户：<br/>
{#foreach from=$zuixin item=list#}
	{#$list.n#}.<b>{#$list.title#}</b><br/>
{#foreachelse#}
	暂无会员注册信息。<br/>
{#/foreach#}



{#kuaifan tongji="正在" title="登录会员"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
