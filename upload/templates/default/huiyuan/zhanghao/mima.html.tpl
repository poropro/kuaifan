{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="修改邮箱/密码"#}

<div class="daohang">
<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a>
</div>

<div class="pnpage">
{#form set="头" notvs="1"#}
邮箱:<br/>
{#form set="输入框|名称:'email{#$TIME2#}'" data_value=$huiyuan.email#}<br/>
原密码:<br/>
{#form set="密码框|名称:'password{#$TIME2#}'"#}<br/>
新密码:<br/>
{#form set="密码框|名称:'newpassword{#$TIME2#}'"#}<br/>
重复新密码:<br/>
{#form set="密码框|名称:'renewpassword{#$TIME2#}'"#}<br/>

{#kuaifan vs="1" set="
	<anchor>提交修改
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='email' value='$(email{#$TIME2#})'/>
	<postfield name='password' value='$(password{#$TIME2#})'/>
	<postfield name='newpassword' value='$(newpassword{#$TIME2#})'/>
	<postfield name='renewpassword' value='$(renewpassword{#$TIME2#})'/>
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
注意:密码应该为6-20位之间。<br/>
<a href="{#kuaifan getlink='a'#}&amp;a=zhifumima">进入支付密码管理</a>
</div>


{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
