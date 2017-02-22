{#include file="common/header.tpl" title="支付密码管理"#}

<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>

------------- <br/>
{#form set="头" notvs="1"#}
{#if $huiyuan.passwordpay#}
	【修改支付密码】<br/>
	新支付密码:<br/>
	{#form set="密码框|名称:'newpasswordpay{#$TIME2#}'"#}<br/>
	重复新支付密码:<br/>
	{#form set="密码框|名称:'renewpasswordpay{#$TIME2#}'"#}<br/>
	【输入密保答案】<br/>
	{#foreach from=$wentiarr item=list#}
		问题{#$list.n#}:{#$list.question#}<br/>
		答案{#$list.n#}:{#form set="输入框|名称:'daan_{#$list.questionid#}{#$TIME2#}'"#}<br/>
	{#/foreach#}
{#else#}
	【设置支付密码】<br/>
	支付密码:<br/>
	{#form set="密码框|名称:'newpasswordpay{#$TIME2#}'"#}<br/>
	重复支付密码:<br/>
	{#form set="密码框|名称:'renewpasswordpay{#$TIME2#}'"#}<br/>
	【设置密保答案】<br/>
	问题1:{#form set="列表框|名称:'wenti1{#$TIME2#}'" list=$wentiarr#}<br/>
	答案1:{#form set="输入框|名称:'daan1{#$TIME2#}'"#}<br/>
	问题2:{#form set="列表框|名称:'wenti2{#$TIME2#}'" list=$wentiarr#}<br/>
	答案2:{#form set="输入框|名称:'daan2{#$TIME2#}'"#}<br/>
	问题3:{#form set="列表框|名称:'wenti3{#$TIME2#}'" list=$wentiarr#}<br/>
	答案3:{#form set="输入框|名称:'daan3{#$TIME2#}'"#}<br/>
	(不能选任意相同的密保问题)<br/>
{#/if#}

{#if $VS=="1"#}
	<anchor>提交保存
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='newpasswordpay' value='$(newpasswordpay{#$TIME2#})'/>
	<postfield name='renewpasswordpay' value='$(renewpasswordpay{#$TIME2#})'/>
	<postfield name='wenti1' value='$(wenti1{#$TIME2#})'/>
	<postfield name='wenti2' value='$(wenti2{#$TIME2#})'/>
	<postfield name='wenti3' value='$(wenti3{#$TIME2#})'/>
	<postfield name='daan1' value='$(daan1{#$TIME2#})'/>
	<postfield name='daan2' value='$(daan2{#$TIME2#})'/>
	<postfield name='daan3' value='$(daan3{#$TIME2#})'/>
	{#foreach from=$wentiarr item=list#}
		<postfield name='daan_{#$list.questionid#}' value='$(daan_{#$list.questionid#}{#$TIME2#})'/>
	{#/foreach#}
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
{#/if#}
{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
注意:支付密码应该为6-20位之间。<br/>
<a href="{#kuaifan getlink='a'#}&amp;a=mima">进入登录密码管理</a><br/>


{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
