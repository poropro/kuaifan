{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

<a href="{#kuaifan getlink='c|a'#}&amp;c=denglu">登录</a>｜快速注册<br/>
------------- <br/>
{#if $smarty.get.go_url#}
	<a href="{#$smarty.get.go_url|goto_url#}">返回来源地址</a><br/>
{#/if#}
{#if $smarty.get.a=='duanxin' || ($smarty.get.a=='' && $sms.zhuce_sms=='1')#}
	<a href="{#kuaifan getlink='a'#}&amp;a=index">快速注册</a>｜短信注册<br/>
	{#if $sms.zhuce_open#}
		{#ubb($sms.zhuce)#}<br/>		
	{#else#}
		<b>*系统未开通短信注册功能！</b><br/>
	{#/if#}
	------------- <br/>
	
{#else#}
	快速注册｜<a href="{#kuaifan getlink='a'#}&amp;a=duanxin">短信注册</a><br/>

	
	{#form set="头" notvs="1"#}
	{#if $modelarr#}
		会员模型:<br/>
		{#form set="列表框|名称:'modelid'" list=$modelarr#}<br/>
	{#/if#}
	手机号码:<br/>
	{#form set="输入框|名称:'mobile'"#}<br/>
	邮箱地址:<br/>
	{#form set="输入框|名称:'email'"#}<br/>
	用户名:<br/>
	{#form set="输入框|名称:'username'"#}<br/>
	昵称:<br/>
	{#form set="输入框|名称:'nickname'"#}<br/>
	密码(6-10位):<br/>
	{#form set="输入框|名称:'userpass'"#}<br/>
	
	{#if $yzmpeizhi.zhuce#}
		<img src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma" />
		<a href="{#kuaifan getlink='yanzhengma'#}">换一张</a><br/>
		{#form set="输入框|名称:'yanzhengma'"#}<br/>
	{#/if#}
	
	{#kuaifan vs="1" set="
		<anchor>确认注册
		<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
		<postfield name='modelid' value='$(modelid)'/>
		<postfield name='mobile' value='$(mobile)'/>
		<postfield name='email' value='$(email)'/>
		<postfield name='username' value='$(username)'/>
		<postfield name='nickname' value='$(nickname)'/>
		<postfield name='userpass' value='$(userpass)'/>
		<postfield name='yanzhengma' value='$(yanzhengma)'/>
		<postfield name='ip' value='{#yanzhengmaip()#}'/>
		<postfield name='dosubmit' value='1'/>
		</go> </anchor>
	"#}
	{#form set="按钮|名称:dosubmit,值:确认注册" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>

{#/if#}
{#if $peizhiarr.showregprotocol#}
	我接受<a href="{#kuaifan getlink='c|a'#}&amp;c=tiaokuan">服务条款</a><br/>
{#/if#}


{#kuaifan tongji="正在" title="注册会员"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
