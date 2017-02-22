{#include file="common/header.tpl" title_top="1" title="短信息"#}

[群发信息给会员组]<br/>
<a href="{#kuaifan getlink='a'#}&amp;a=xitong">返回列表</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}
	会员组:<br/>
	{#form set="列表框|名称:'groupid{#$TIME2#}'" list=$grouplist#}<br/>
	标 题:<br/>
	{#form set="输入框|名称:'subject{#$TIME2#}'"#}<br/>
	内 容:<br/>
	{#form set="输入框|名称:'content{#$TIME2#}'" vs="1"#}
	{#form set="文本框|名称:'content{#$TIME2#}'" notvs="1"#}<br/>

{#kuaifan vs="1" set="
	<anchor>确定群发
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='groupid' value='$(groupid{#$TIME2#})'/>
	<postfield name='subject' value='$(subject{#$TIME2#})'/>
	<postfield name='content' value='$(content{#$TIME2#})'/>
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:确定群发" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>



{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
