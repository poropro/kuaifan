{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_huiyuan.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.tpl" title="找回登录密码"#}

<div class="text-nav top_newsbg">
<a class="textn-left" href="{#kuaifan getlink='c'#}&amp;c=denglu"></a>
找回登录密码
<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>


{#if $sms.zhaohui_open#}
	<div class="duanxin">
		{#ubb($sms.zhaohui)#}
	</div>
{#/if#}

{#if $mail.mail_set_zhaohui#}
	<div class="denglu">
		<div class="webduanxin b-15">通过邮箱找回密码</div>
		{#form set="头" notvs="1"#}
		{#form set="输入框|名称:'username',placeholder:'用户名或手机号码'"#}
		{#form set="输入框|名称:'email',placeholder:'注册时设置的邮箱'"#}
		
		{#if $yzmpeizhi.zhaohui#}
			<img id="yzm_img" src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma"/>
			<a id="yzm_a" href="{#kuaifan getlink='yanzhengma'#}">换一张</a><br/>
			{#form set="输入框|名称:'yanzhengma',placeholder:'验证码'"#}<br/>
			{#literal#}<script type="text/javascript">
				function set_refresh_code(id1,id2){if(document.getElementById(id1)){var temp_src=document.getElementById(id1).src;temp_src=temp_src.replace("&amp;","&");document.getElementById(id2).onclick=function(){if(temp_src.indexOf("?")){document.getElementById(id1).src=temp_src+"&_refresh="+Math.random()}else{document.getElementById(id1).src=temp_src+"?_refresh="+Math.random()};return false}}}
				set_refresh_code('yzm_img', 'yzm_img');
				set_refresh_code('yzm_img', 'yzm_a');
			</script>{#/literal#}
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
		
		<div class="tishi">
			<div class="tst">*温馨提示*</div>
			<div class="tsc">找回密码功能系统将自动生成一个新密码发送到您注册时设置的邮箱。</div>
		</div>
	</div>
{#/if#}
{#if !$sms.zhaohui_open && !$mail.mail_set_zhaohui#}
	<div class="wuzhaohui">
		<b>*系统未开通找回密码功能！</b>
	</div>
{#/if#}

<div class="zhzc">
	<a href="{#kuaifan getlink='c'#}&amp;c=denglu">登录会员</a>
	<a href="{#kuaifan getlink='c'#}&amp;c=zhuce">注册新用户</a>
</div>
<div class="clear"></div>


{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}
