{#include file="common/header.html.tpl" title_top="1" title="修改头像"#}

[修改头像]<br/>
<a href="{#get_link('a')#}&amp;a=xiangqing">详情页</a> &gt; <a href="{#get_link('a')#}&amp;a=xiugai">修改页</a><br/>
-------------<br/>
{#if $avatar.90#}
<img src="{#$avatar.90#}?t={#$TIME2#}" alt="."/><br/>
<a href="{#get_link('dosubmit')#}&amp;dosubmit=1">!删除头像</a><br/>
{#/if#}
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

{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
