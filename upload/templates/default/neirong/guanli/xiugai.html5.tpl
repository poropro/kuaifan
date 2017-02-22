{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_huiyuan.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_fabu.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.html5.tpl" title="{#$fabu.lanmu.title#} - 修改稿件"#}

<div class="text-nav top_newsbg">
	<a class="textn-left" href="{#if $smarty.get.go_url#}{#$smarty.get.go_url|goto_url#}&amp;sid={#$SID#}{#else#}{#kuaifan getlink='checkid|a'#}{#/if#}"></a>
	在线投稿
	<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>


{#form set="头|enctype:'multipart/form-data'"#}

	<div class="fabuhead m-t-10">
		<a href="{#kuaifan getlink='a'#}&amp;a=shanchu"><b>!删除此内容</b></a><br/>
		栏目:{#$fabu.lanmu.title#}<br/>
		{#if $categorys#}移至栏目:<select name="yidonglanmuid"><option value='0'>保持不变(建议)</option>{#$categorys#}</select><br/>{#/if#}
	</div>


	{#foreach from=$ziduandata item=ziduan#}
		<div class="ziduan">{#kuaifan_nr_form($ziduan.type,$ziduan,"", 2, $fabu.id, $fabu.catid)#}</div>
	{#/foreach#}

	{#if $fabu.status_y!=1#}
		<div class="ziduan">
			<div style="border-bottom:1px solid #DAD6FC; margin:3px 0px;"></div>
			发布到草稿箱:{#form set="列表框|名称:caogao" list="否:0,是:1" default=$fabu.status __vs=2#}<br/>
		</div>
	{#/if#}

	<div class="ziduan">
		{#form set="按钮|名称:dosubmit,值:提交修改"#}
	</div>
	
{#form set="尾"#}

{#if $fabu.status_y!=1#}
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
{#else#}
	<div class="tishi m-t-15"></div>
{#/if#}


{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}