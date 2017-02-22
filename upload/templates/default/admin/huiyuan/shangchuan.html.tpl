{#include file="common/header.html.tpl" title_top="1" title="会员分组"#}

[上传用户组图标]<br/>
<a href="{#kuaifan getlink='a'#}&amp;a=fenzu">返回</a><br/>


{#form set="头|enctype:'multipart/form-data'"#}
{#form set="文件|名称:icon"#}<br/> 
{#form set="按钮|名称:dosubmit,值:提交上传"#}
{#form set="尾"#}<br/> 

{#if $fenzu.icon#}
	-------------<br/>
	<img src="{#$fenzu.icon#}"/><br/>
	<a href="{#kuaifan getlink='delicon'#}&amp;delicon=1">删除图标</a><br/>
{#/if#}

{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
