{#include file="common/header.html.tpl" title_top="1" title="文件回复贴"#}

<div style="margin: 10px;">
	[文件回复贴]<br/>

	-------------<br/>

	{#form set="头"#}
	<input type="text" name="upi" value="{#$smarty.post.upi#}" size="3" />
	{#form set="隐藏|名称:nextsubmit,值:1"#}
	{#form set="按钮|名称:dosubnum,值:提交上传数量"#}
	{#form set="尾"#}<br/> 
	-------------<br/>

	{#form set="头|enctype:'multipart/form-data'"#}
	{#$__input#} 
	回复内容*：<br/>
	{#form set="文本框|名称:pl,style:'width\:100%;height\:80px;'"#}<br/>
	{#form set="按钮|名称:dosubmit,值:提交上传"#}
	{#form set="尾"#}<br/> 

	{#if $fudatasetting.upload_allowext#}<u>支持上传类型:{#$fudatasetting.upload_allowext#}</u><br/> {#/if#}
	{#if $fudatasetting.upload_number#}<u>最多可上传{#$fudatasetting.upload_number#}个文件</u><br/> {#/if#}
	{#if $fudatasettingone#}<u>单个文件最大支持:{#$fudatasettingone#}</u><br/> {#/if#}
</div>

<div style="padding: 3px 5px 3px 10px;background: #e4e4e4;font-size: 18px;">
	<a href="{#kf_url('neirongreply')#}">回评论列表</a><br/>
	<a href="{#$V.url#}">返回内容页面</a><br/>
	<a href="{#kf_url('index')#}">返回网站首页</a>
</div>

{#include file="common/footer.html.tpl"#}