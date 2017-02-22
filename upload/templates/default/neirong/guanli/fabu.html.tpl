{#include file="common/header.html.tpl" title="在线投稿" title_top="1"#}

<div style="margin:5px 10px;">

	<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>

	{#form set="头"#}
	请选择栏目:<br/>
	<select name="catid" onchange="check();">{#$categorys#}</select>
	{#form set="隐藏|名称:nextsubmit,值:1"#}
	{#form set="按钮|名称:dosubmit,值:确定"#}
	{#form set="尾"#}
	<span id="catid_txt"></span>

	{#$fabu_head#}

	{#form set="头|enctype:'multipart/form-data'"#}

	{#foreach from=$ziduandata item=ziduan#}
		{#kuaifan_nr_form($ziduan.type,$ziduan,"<br/>", 2)#}
	{#/foreach#}
	发布到草稿箱:{#form set="列表框|名称:caogao" list="否:0,是:1" default="0"#}<br/>
	
	{#if $yzmpeizhi.fabu#}
		<img id="yzm_img" src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma"/>
		<a id="yzm_a" href="{#kuaifan getlink='yanzhengma'#}">换一张</a><br/>
		{#form set="输入框|名称:'yanzhengma'"#}<br/>
		{#literal#}<script type="text/javascript">
			function set_refresh_code(id1,id2){if(document.getElementById(id1)){var temp_src=document.getElementById(id1).src;temp_src=temp_src.replace("&amp;","&");document.getElementById(id2).onclick=function(){if(temp_src.indexOf("?")){document.getElementById(id1).src=temp_src+"&_refresh="+Math.random()}else{document.getElementById(id1).src=temp_src+"?_refresh="+Math.random()};return false}}}
			set_refresh_code('yzm_img', 'yzm_img');
			set_refresh_code('yzm_img', 'yzm_a');
		</script>{#/literal#}
	{#/if#}

	{#form set="按钮|名称:dosubmit,值:提交发布"#}
	{#form set="尾"#}
	<br/>

	<b>
	{#if !$catarr.setting.shenhehou#}
		{#if $allowpostverify#}
			提示:你当前投稿无须审核,投稿成功后将无法修改内容,如需修改请选择发布到草稿箱。
		{#else#}
			提示:选择发布到草稿箱后可进行修改内容或添加附件。
		{#/if#}
	{#else#}
		提示:可选择发布到草稿箱后再进行修改内容或添加附件。
	{#/if#}
	</b>
</div>

<script language=javascript>
function check(){#
	document.getElementById("catid_txt").innerHTML="<font color='#ff0000' size='1'>请点击确定选择栏目</font>";
#}
</script>

<div style="padding: 3px 5px 3px;background: #e4e4e4;font-size: 18px;">
	{#include file="common/footerb.html.tpl"#}
</div>
{#include file="common/footer.html.tpl"#}