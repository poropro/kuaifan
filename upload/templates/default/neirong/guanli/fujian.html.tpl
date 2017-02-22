{#include file="common/header.html.tpl" title_top="1" title="上传文件管理"#}

[上传:{#$fudata.name#}]<br/>
<a href="{#kuaifan getlink='edit|upfile|upi|upisubmit'#}">返回修改页面</a><br/>

-------------<br/>

{#form set="头"#}
<input type="text" name="upi" value="{#$smarty.post.upi#}" size="3" />
{#form set="隐藏|名称:nextsubmit,值:1"#}
{#form set="按钮|名称:dosubmit,值:提交上传数量"#}
{#form set="尾"#}<br/> 
-------------<br/>

{#form set="头|action:'{#str_replace(':', '\:', get_link('upi|upisubmit'))#}',enctype:'multipart/form-data'"#}
{#$__input#} 
{#form set="按钮|名称:dosubmit,值:提交上传"#}
{#form set="尾"#}<br/> 

{#if $fudatasetting.upload_allowext#}<u>支持上传类型:{#$fudatasetting.upload_allowext#}</u><br/> {#/if#}
{#if $fudatasetting.upload_number#}<u>最多可上传{#$fudatasetting.upload_number#}个文件</u><br/> {#/if#}
{#if $fudatasettingone#}<u>单个文件最大支持:{#$fudatasettingone#}</u><br/> {#/if#}
-------------<br/>

已传列表({#count($fujian)#}个)<br/> 
{#foreach from=$fujian item=list#}
	[<a href="{#kuaifan getlink='fd'#}&amp;fd={#$list.id#}">删</a>.<a href="{#kuaifan getlink='fd'#}&amp;fe={#$list.id#}">编</a>]<a href="{#$list.allurl#}" target="_blank">{#$list.name#}</a>{#if $list.body#}({#$list.body|nl2br|htmlspecialchars#}){#/if#}<br/>
{#/foreach#}

<div style="padding: 3px 5px 3px;background: #e4e4e4;font-size: 18px;">
	<a href="{#kf_url('index')#}">返回网站首页</a>
</div>

{#include file="common/footer.html.tpl"#}