{#include file="common/header.html.tpl" title_top="1" title="修改头像"#}

<a href="{#get_link('c|t')#}&amp;c=index">会员中心</a> &gt; 修改头像<br/>
-------------<br/>

{#form set="头|enctype:'multipart/form-data'"#}

上传：<br/>
{#form set="文件|名称:shangchuan"#}<br/>
(或)网络获取:<br/>
{#form set="输入框|名称:wangluo"#}<br/>

{#form set="按钮|名称:dosubmit,值:提交保存"#}
{#form set="尾"#}
<br/>
注:上传至允许jpg/gif/jpeg/png格式；网络获取只允许后缀名为jpg/gif/jpeg/png格式的文件。<br/>
*如果两者都填写默认使用上传的。<br/>

{#if $huiyuan.avatar#}
	-------------<br/>
	<a href="{#get_link('dosubmit')#}&amp;dosubmit=1">!删除头像</a><br/>
	<b>头像浏览</b><br/>
	{#$avatar = getavatar($huiyuan.userid)#}
	<img src="{#$avatar.180#}?t={#$TIME2#}"/><br/>
	大头像<br/>
	<img src="{#$avatar.90#}?t={#$TIME2#}"/><br/>
	中头像<br/>
	<img src="{#$avatar.45#}?t={#$TIME2#}"/><br/>
	小头像<br/>
	<img src="{#$avatar.30#}?t={#$TIME2#}"/><br/>
	微头像<br/>
{#/if#}

<div style="padding: 3px 5px 3px;background: #e4e4e4;font-size: 18px;">
	<a href="{#kf_url('index')#}">返回网站首页</a> <br/>
</div>


{#kuaifan tongji="正在" title="修改个人头像"#}
{#include file="common/footer.html.tpl"#}
