{#include file="common/header.tpl" title_top="1" title="短信息"#}

[发送信息给指定用户]<br/>
<a href="{#kuaifan getlink='a'#}&amp;a=index">返回列表</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}
	收信人:<br/>
	{#form set="列表框|名称:'usertype{#$TIME2#}'" list="用户名:username,用户ID:userid"#}{#form set="输入框|名称:'username{#$TIME2#}',宽:5"#}<br/>
	标 题:<br/>
	{#form set="输入框|名称:'subject{#$TIME2#}'"#}<br/>
	内 容:<br/>
	{#form set="输入框|名称:'content{#$TIME2#}'" vs="1"#}
	{#form set="文本框|名称:'content{#$TIME2#}'" notvs="1"#}<br/>

{#kuaifan vs="1" set="
	<anchor>发送
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='usertype' value='$(usertype{#$TIME2#})'/>
	<postfield name='username' value='$(username{#$TIME2#})'/>
	<postfield name='subject' value='$(subject{#$TIME2#})'/>
	<postfield name='content' value='$(content{#$TIME2#})'/>
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:发送" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>



{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
