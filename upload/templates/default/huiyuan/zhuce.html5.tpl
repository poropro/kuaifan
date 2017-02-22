{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_huiyuan.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

<div class="text-nav top_newsbg">
<a class="textn-left" href="{#kuaifan getlink='c'#}&amp;c=denglu"></a>
注册会员
<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>

{#if $smarty.get.a=='duanxin' || ($smarty.get.a=='' && $sms.zhuce_sms=='1')#}
	<div class="webduanxin">
	<a href="{#kuaifan getlink='a'#}&amp;a=index">快速注册</a>｜短信注册
	</div>
	
	<div class="duanxin">
	{#if $sms.zhuce_open#}
		{#ubb($sms.zhuce)#}<br/>		
	{#else#}
		<b>*系统未开通短信注册功能！</b><br/>
	{#/if#}
	</div>
	
{#else#}
	<div class="webduanxin">
	快速注册｜<a href="{#kuaifan getlink='a'#}&amp;a=duanxin">短信注册</a>
	</div>

	<div class="denglu">
		{#form set="头"#}
		{#if $modelarr#}
			会员模型:<br/>
			{#form set="列表框|名称:'modelid'" list=$modelarr#}
		{#/if#}
		{#form set="输入框|名称:'mobile',placeholder:'手机号码'"#}
		{#form set="输入框|名称:'email',placeholder:'邮箱地址'"#}
		{#form set="输入框|名称:'username',placeholder:'用户名'"#}
		{#form set="输入框|名称:'nickname',placeholder:'昵称'"#}
		{#form set="输入框|名称:'userpass',placeholder:'密码(6-10位)'"#}
		
		{#if $yzmpeizhi.zhuce#}
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
		{#form set="按钮|名称:dosubmit,值:确认注册"#}
		{#form set="尾"#}
	</div>

{#/if#}
{#if $peizhiarr.showregprotocol#}
	<div class="tiaokuan"><span class="yigeyue-a"></span>我接受<a href="{#kuaifan getlink='c|a'#}&amp;c=tiaokuan">服务条款</a></div>
{#/if#}


{#kuaifan tongji="正在" title="注册会员"#}
{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}
