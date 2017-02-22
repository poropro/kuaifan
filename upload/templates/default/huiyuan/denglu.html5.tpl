{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_huiyuan.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

<div class="text-nav top_newsbg">
<a class="textn-left" href="{#if $smarty.get.go_url#}{#$smarty.get.go_url|goto_url#}{#else#}{#kf_url('index')#}{#/if#}"></a>
登录
<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>


<div class="denglu">
	{#form set="头"#}
	{#form set="输入框|名称:'username',id:'username',placeholder:'ID/用户名/手机号码/邮箱'"#}
	{#form set="密码框|名称:'userpass',id:'userpass',placeholder:'请输入密码'"#}
	
	{#if $yzmpeizhi.denglu#}
		<img id="yzm_img" src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma"/>
		<a id="yzm_a" href="{#kuaifan getlink='yanzhengma'#}">换一张</a><br/>
		{#form set="输入框|名称:'yanzhengma',placeholder:'验证码'"#}<br/>
		{#literal#}<script type="text/javascript">
			function set_refresh_code(id1,id2){if(document.getElementById(id1)){var temp_src=document.getElementById(id1).src;temp_src=temp_src.replace("&amp;","&");document.getElementById(id2).onclick=function(){if(temp_src.indexOf("?")){document.getElementById(id1).src=temp_src+"&_refresh="+Math.random()}else{document.getElementById(id1).src=temp_src+"?_refresh="+Math.random()};return false}}}
			set_refresh_code('yzm_img', 'yzm_img');
			set_refresh_code('yzm_img', 'yzm_a');
		</script>{#/literal#}
	{#/if#}
	
	{#form set="隐藏|名称:'miandenglu',id:'miandenglu',值:30"#}
	<a class="yigeyue" href="javascript:void(0);"><span class="yigeyue-a" onclick="remeber()"></span>一个月内免登录</a>
	{#form set="按钮|名称:dosubmit,值:登录"#}
	{#form set="尾"#}
</div>

<div class="m-wjzc">
	<a href="{#kuaifan getlink='c'#}&amp;c=zhaohui">忘记密码?</a><a href="{#kuaifan getlink='c'#}&amp;c=zhuce">注册新用户</a>
</div>
<div class="clear"></div>

<div class="zx-list">
	<div class="bti">最新注册用户：</div>
	{#foreach from=$zuixin item=list#}
		<p>{#$list.title#}</p>
	{#foreachelse#}
		<p>暂无会员注册信息。</p>
	{#/foreach#}
</div>
<script type="text/javascript" >
	function remeber(){
		if($('.yigeyue').find('span').hasClass('on')){
			$('.yigeyue').find('span').removeClass('on');
			$('#miandenglu').val("30");
		}else{
			$('.yigeyue').find('span').addClass('on');
			$('#miandenglu').val("0");
		}
	}
</script>


{#kuaifan tongji="正在" title="登录会员"#}
{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}