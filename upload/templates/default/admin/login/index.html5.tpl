{#$__seo_head="
	<style>
	body {
		font-size: 16px;
		background: #eee;
	}
	#web_login {
		width: 290px;
		margin: 0 auto;
		display: block;
	}
	.head {
		text-align: center;
		height: 70px;
		line-height: 70px;
		color: #246183;
		font-weight: 900;
		font-size: 24px;
	}
	.glist {
		background: #fff;
		height: 89px;
		border-radius: 4px;
	}
	.glisty {
		height: 180px;
	}
	.glisty img {
		padding-left: 5px;
		padding-top: 5px;
	}
	.glisty .g_p {
		border-bottom: 1px solid #eaeaea;
	}
	.glisty .yanz {
		line-height: 22px;
		height: 40px;
		overflow:hidden;
	}
	.glisty .g_y {
		border-top: 1px solid #eaeaea;
	}
	.inputstyle {
		-webkit-tap-highlight-color: rgba(255,255,255,0);
		width: 273px;
		height: 44px;
		color: #000;
		border: 0;
		background: 0;
		padding-left: 15px;
		font-size: 16px;
		-webkit-appearance: none;
	}
	.g_u {
		border-bottom: 1px solid #eaeaea;
	}
	.weak {
		width: 290px;
		line-height: 42px;
		background: #146fdf;
		border-radius: 4px;
		font-size: 16px;
		text-align: center;
		margin-top: 15px;
		display: block;
		background-color: #146fdf;
		color: #ffffff;
		border: 1px solid #146fdf;
		height: 42px;
	}
	.weak2 {
		background-color: #e7e7e7;
		color: #146fdf;
		border: 1px solid #9abbe3;
	}
	.zc_feedback {
		width: 290px;
		position: relative;
		margin-top: 10px;
		overflow: hidden;
	}
	.zindex {
		display:black;
		float: right;
		margin-right: -10px;
		color: #246183;
		line-height: 14px;
		font-size: 14px;
		padding: 15px 10px;
	}
	.zweb{
		float: left;
		margin-right:0;
		margin-left: -10px;
	}
	</style>
"#}
{#include file="common/header.html.tpl" title="管理后台登录"#}

<div id="web_login">
	<div class="head">登录管理后台(触屏版)</div>

	<form action='{#get_link()#}' method='post'>
		<div class="glist" id="glist">
			<input id="username" class="inputstyle g_u" name="username" autocomplete="off" type="text" placeholder="请输入帐号"/>
			<input id="userpass" class="inputstyle g_p" name="userpass" autocorrect="off" type="password" placeholder="请输入密码"/>
			{#if $yzmpeizhi.houtai#}
				<div class="yanz">
					<img id="yzm_img" src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma"/>
					<a id="yzm_a" href="{#kuaifan getlink='yanzhengma'#}">换一张</a>
				</div>
				<input id="yanzhengma" class="inputstyle g_y" name="yanzhengma" autocorrect="off" type="password" placeholder="验证码"/>
				{#literal#}<script type="text/javascript">
					addClass(document.getElementById("glist"), "glisty");
					function hasClass(obj, cls) {return obj.className.match(new RegExp('(\s|^)' + cls + '(\s|$)'));}
					function addClass(obj, cls) {if (!this.hasClass(obj, cls)) obj.className += " " + cls;}
					function set_refresh_code(id1,id2){if(document.getElementById(id1)){var temp_src=document.getElementById(id1).src;temp_src=temp_src.replace("&amp;","&");document.getElementById(id2).onclick=function(){if(temp_src.indexOf("?")){document.getElementById(id1).src=temp_src+"&_refresh="+Math.random()}else{document.getElementById(id1).src=temp_src+"?_refresh="+Math.random()};return false{}}
					set_refresh_code('yzm_img', 'yzm_img');
					set_refresh_code('yzm_img', 'yzm_a');
				</script>{#/literal#}
			{#/if#}

			
		</div>
		<input class="weak" type="submit" name="dosubmit" value="登录" />
	</form>

	<div class="weak weak2">欢迎使用快范CMS建站系统</div>
	<div class="weak weak2"><a href="http://wap.kuaifan.net/">KuaiFan©2014</a></div>
	<div class="zc_feedback">
		<a href="index.php" class="zindex">网站首页</a>
		<a href="{#kuaifan getlink='vs'#}&amp;vs=5" class="zindex zweb">电脑后台</a>
	</div>

</div>
{#include file="common/footer.html.tpl"#}
