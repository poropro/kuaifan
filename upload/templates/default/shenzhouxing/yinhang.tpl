{#include file="common/header.tpl" title="站长资料"#}

[站长资料]<br/>
<a href="{#kuaifan getlink='c|pp'#}&amp;c=index">返回系统管理</a><br/>
------------- <br/>
{#if $htmlarr.zhanghao#}
	<a href="{#kuaifan getlink='c|pp'#}&amp;c=yinhang">点此刷新资料</a>
	<br/>手机:{#$htmlarr.shouji#}
	<br/>QQ:{#$htmlarr.qq#}
	<br/>开户银行:{#$htmlarr.yinhang#}
	<br/>银行帐号:{#$htmlarr.zhanghao#}
	<br/>开户名:{#$htmlarr.kaihuming#}
	<br/>开户行地址:{#$htmlarr.kaihuhang#}
	<br/><b>站长银行资料不允许自行修改,请联系QQ342210020。</b>
{#else#}
	{#form set="头" notvs="1"#}
	手机:<br/>
	{#form set="输入框|名称:'shouji{#$TIME2#}'" data_value=$htmlarr.shouji#}<br/>
	QQ:<br/>
	{#form set="输入框|名称:'qq{#$TIME2#}'" data_value=$htmlarr.qq#}<br/>
	开户银行:<br/>
	{#form set="输入框|名称:'yinhang{#$TIME2#}'" data_value=$htmlarr.yinhang#}<br/>
	银行帐号:<br/>
	{#form set="输入框|名称:'zhanghao{#$TIME2#}'" data_value=$htmlarr.zhanghao#}<br/>
	开户名:<br/>
	{#form set="输入框|名称:'kaihuming{#$TIME2#}'" data_value=$htmlarr.kaihuming#}<br/>
	开户行地址:<br/>
	{#form set="输入框|名称:'kaihuhang{#$TIME2#}'" data_value=$htmlarr.kaihuhang#}<br/>

	{#kuaifan vs="1" set="
		<anchor>提交保存
		<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
		<postfield name='shouji' value='$(shouji{#$TIME2#})'/>
		<postfield name='qq' value='$(qq{#$TIME2#})'/>
		<postfield name='yinhang' value='$(yinhang{#$TIME2#})'/>
		<postfield name='zhanghao' value='$(zhanghao{#$TIME2#})'/>
		<postfield name='kaihuming' value='$(kaihuming{#$TIME2#})'/>
		<postfield name='kaihuhang' value='$(kaihuhang{#$TIME2#})'/>
		<postfield name='dosubmit' value='1'/>
		</go> </anchor>
	"#}
	{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/><b>注意:站长银行资料保存后不允许自行修改,请尽量填写真实</b>。
{#/if#}
<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
