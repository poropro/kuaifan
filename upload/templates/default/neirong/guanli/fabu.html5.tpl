{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_huiyuan.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_fabu.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.html5.tpl" title="{#$catarr.title#} - 在线投稿"#}

<div class="text-nav top_newsbg">
	<a class="textn-left" href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index"></a>
	在线投稿
	<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>


<div class="search">
    <div class="txt">
		<span class="sortSelect">
			<select name="sortSelect" onchange="check(this);">
				{#$categorys#}
			</select>
		</span>
    </div>
</div>

{#if $fabu_head#}
	<div class="fabuhead">
		{#$fabu_head#}
	</div>
{#/if#}

{#form set="头|enctype:'multipart/form-data'"#}

{#foreach from=$ziduandata item=ziduan#}
	<div class="ziduan">{#kuaifan_nr_form($ziduan.type,$ziduan,"", 3)#}</div>
{#/foreach#}

<div class="ziduan">
	<div style="border-bottom:1px solid #DAD6FC; margin:3px 0px;"></div>
	发布到草稿箱:{#form set="列表框|名称:caogao" list="否:0,是:1" default="0"#}
</div>

{#if $yzmpeizhi.fabu#}
	<div class="ziduan">
	<div style="border-bottom:1px solid #DAD6FC; margin:3px 0px;"></div>
		<img id="yzm_img" src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma"/>
		<a id="yzm_a" href="{#kuaifan getlink='yanzhengma'#}">换一张</a><br/>
		{#form set="输入框|名称:'yanzhengma',placeholder:'验证码'"#}<br/>
		{#literal#}<script type="text/javascript">
			function set_refresh_code(id1,id2){if(document.getElementById(id1)){var temp_src=document.getElementById(id1).src;temp_src=temp_src.replace("&amp;","&");document.getElementById(id2).onclick=function(){if(temp_src.indexOf("?")){document.getElementById(id1).src=temp_src+"&_refresh="+Math.random()}else{document.getElementById(id1).src=temp_src+"?_refresh="+Math.random()};return false}}}
			set_refresh_code('yzm_img', 'yzm_img');
			set_refresh_code('yzm_img', 'yzm_a');
		</script>{#/literal#}
	</div>
{#/if#}

<div class="ziduan">
	{#form set="按钮|名称:dosubmit,值:提交发布"#}
</div>
{#form set="尾"#}

<div class="ziduan">
	{#if !$catarr.setting.shenhehou#}
		{#if $allowpostverify#}
			提示:你当前投稿无须审核,投稿成功后将无法修改内容,如需修改请选择发布到草稿箱。
		{#else#}
			提示:选择发布到草稿箱后可进行修改内容或添加附件。
		{#/if#}
	{#else#}
		提示:可选择发布到草稿箱后再进行修改内容或添加附件。
	{#/if#}
</div>
<div class="tishi"></div>

<script language="javascript">
	var fabuurl = '{#kuaifan getlink="catid"#}&amp;catid=';
	function check(sobj){
		var docurl =sobj.options[sobj.selectedIndex].value;
		if (docurl > 0){
			var tmpurl = fabuurl + docurl;
			tmpurl = tmpurl.replace(/&amp;/g, "&");
			window.location = tmpurl;
		}
	}
</script>

{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}
