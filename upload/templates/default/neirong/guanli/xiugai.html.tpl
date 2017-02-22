{#include file="common/header.html.tpl" title="修改稿件" title_top="1"#}

<div style="margin:5px 10px;">
	{#if $smarty.get.go_url#}
		<a href="{#$smarty.get.go_url|goto_url#}&amp;sid={#$SID#}">返回来源地址</a><br/>
	{#else#}
		<a href="{#kuaifan getlink='checkid|m|c|a'#}&amp;m=huiyuan&amp;c=index">会员中心</a>&gt;<a href="{#kuaifan getlink='checkid|a'#}">稿件</a>&gt;修改<br/>
	{#/if#}
	-------------<br/>
	<a href="{#kuaifan getlink='a'#}&amp;a=shanchu"><b>!删除此内容</b></a><br/>

	{#form set="头|enctype:'multipart/form-data'"#}
	栏目:{#$fabu.lanmu.title#}<br/>
	{#if $categorys#}移至栏目:<select name="yidonglanmuid"><option value='0'>保持不变(建议)</option>{#$categorys#}</select><br/>{#/if#}

	{#foreach from=$ziduandata item=ziduan#}
		{#kuaifan_nr_form($ziduan.type,$ziduan,"<br/>", 2, $fabu.id, $fabu.catid)#}
	{#/foreach#}

	{#if $fabu.status_y!=1#}
		发布到草稿箱:{#form set="列表框|名称:caogao" list="否:0,是:1" default=$fabu.status __vs=2#}<br/>
	{#/if#}

	{#form set="按钮|名称:dosubmit,值:提交修改"#}
	{#form set="尾"#}
	<br/>

	{#if $fabu.status_y!=1#}
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
		</b><br/>
	{#/if#}
</div>


<div style="padding: 3px 5px 3px;background: #e4e4e4;font-size: 18px;">
	<a href="{#kf_url('index')#}">返回网站首页</a>
</div>
{#include file="common/footer.html.tpl"#}