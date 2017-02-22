{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="找回登录密码"#}

<div class="daohang">
<a href="{#kuaifan getlink='c'#}&amp;c=denglu">登录</a> &gt; 找回登录密码
</div>

<div class="pnpage">
{#if $smarty.get.go_url#}
	<a href="{#$smarty.get.go_url|goto_url#}">返回来源地址</a><br/>
{#/if#}

{#if $sms.zhaohui_open#}
	{#ubb($sms.zhaohui)#}<br/>
	------------- <br/>
{#/if#}

{#if $mail.mail_set_zhaohui#}
	<b>通过邮箱找回密码</b><br/>
	{#form set="头" notvs="1"#}
	用户名或手机号码：<br/>
	{#form set="输入框|名称:'username'"#}<br/>
	注册时设置的邮箱：<br/>
	{#form set="输入框|名称:'email'"#}<br/>
	
	{#if $yzmpeizhi.zhaohui#}
		<img src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma" />
		<a href="{#kuaifan getlink='yanzhengma'#}">换一张</a><br/>
		{#form set="输入框|名称:'yanzhengma'"#}<br/>
	{#/if#}

	{#kuaifan vs="1" set="
		<anchor>立即找回
		<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
		<postfield name='username' value='$(username)'/>
		<postfield name='email' value='$(email)'/>
		<postfield name='yanzhengma' value='$(yanzhengma)'/>
		<postfield name='ip' value='{#yanzhengmaip()#}'/>
		<postfield name='dosubmit' value='1'/>
		</go> </anchor>
	"#}
	{#form set="按钮|名称:dosubmit,值:立即找回" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	<b>*温馨提示*</b><br/>
	找回密码功能系统将自动生成一个新密码发送到您注册时设置的邮箱<br/>
	------------- <br/>
{#/if#}
{#if !$sms.zhaohui_open && !$mail.mail_set_zhaohui#}
	<b>*系统未开通找回密码功能！</b><br/>
	------------- <br/>
{#/if#}
已取回密码？<a href="{#kuaifan getlink='c'#}&amp;c=denglu">登录会员</a><br/>
还没帐号？<a href="{#kuaifan getlink='c'#}&amp;c=zhuce">注册新用户</a><br/>

</div>

{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
