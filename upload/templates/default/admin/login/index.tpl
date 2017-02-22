{#include file="common/header.tpl" title_top="1" title="系统后台"#}
手机管理后台<br/>
-------------<br/>

{#form set="头" notvs="1"#}

帐号:<br/>
{#form set="输入框|名称:'username{#$TIME2#}'"#}<br/>
密码:<br/>
{#form set="密码框|名称:'userpass{#$TIME2#}'"#}<br/>

{#if $yzmpeizhi.houtai#}
	<img src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma" />
	<a href="{#kuaifan getlink='yanzhengma'#}">换一张</a><br/>
	{#form set="输入框|名称:'yanzhengma'"#}<br/>
{#/if#}

{#kuaifan vs="1" set="
	<anchor title='确定'>[-登陆-]
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='username' value='$(username{#$TIME2#})'/>
	<postfield name='userpass' value='$(userpass{#$TIME2#})'/>
	<postfield name='yanzhengma' value='$(yanzhengma)'/>
	<postfield name='ip' value='{#yanzhengmaip()#}'/>
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:登录" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

欢迎使用快范CMS建站系统！ 
<br/>返回:<a href="index.php">网站首页</a>
<br/>切换:<a href="{#kuaifan getlink='vs'#}&amp;vs=5">电脑后台</a>
<br/><a href="http://wap.kuaifan.net/">KuaiFan©2014</a>

{#include file="common/footer.tpl"#}
