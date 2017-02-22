{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="欢迎登录"#}

<div class="daohang">
	首次登录网站需要设置用户名
</div>


<div class="pnpage">
	{#form set="头" notvs="1"#}
	用户名:<br/>
	{#form set="输入框|名称:'username{#$TIME2#}'" data_value=$huiyuan_val.username#}<br/>
	{#kuaifan vs="1" set="
		<anchor>保存设置
		<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
		<postfield name='username' value='$(username{#$TIME2#})'/>
		<postfield name='dosubmit' value='1'/>
		</go> </anchor>
	"#}
	{#form set="按钮|名称:dosubmit,值:保存设置" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	注意:用户名是唯一标识保存设置了以后就不可以再修改。		

</div>


{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
