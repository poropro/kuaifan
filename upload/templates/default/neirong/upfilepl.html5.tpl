{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_huiyuan.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_bqbox.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_fabu.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}bqbox.js'></script>
"#}
{#include file="common/header.html5.tpl" title="文件回复贴"#}

<div class="text-nav top_newsbg">
	<a class="textn-left" href="{#$V.url#}"></a>
	文件回复贴
	<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>
<div class="fabuhead">
	<a href="{#kf_url('neirongreply')#}">返回评论列表</a><br/>
</div>

<div style="margin: 8px;">

	<form  method="post" action="{#get_link()#}">
		<input type="text" name="upi" value="{#$smarty.post.upi#}" size="3" />
		<input type="hidden" name="nextsubmit" value="1" />
		<input class="ipt-btn" type="submit" name="dosubnum" value="提交上传数量" />
	</form>
	
	<div class="form_fjbq">
		<form enctype="multipart/form-data"  method="post" action="{#get_link()#}">
			{#$__input#} 
			回复内容*：
			<textarea class="textarea" id="commentTextarea" name="pl" style="width:100%;height:80px;" ></textarea>
			<input class="ipt_text_submit" type="submit" name="dosubmit" value="提交上传"/>
			<div id="bqico" class="bqico" onClick="bqico('commentTextarea',this.id);"></div>
		</form>
	</div>
	
	{#if $fudatasetting.upload_allowext#}<u>支持上传类型:{#$fudatasetting.upload_allowext#}</u><br/> {#/if#}
	{#if $fudatasetting.upload_number#}<u>最多可上传{#$fudatasetting.upload_number#}个文件</u><br/> {#/if#}
	{#if $fudatasettingone#}<u>单个文件最大支持:{#$fudatasettingone#}</u><br/> {#/if#}
</div>
<div class="tishi"></div>

{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}
