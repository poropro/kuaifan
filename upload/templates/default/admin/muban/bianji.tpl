{#include file="common/header.html.tpl" title_top="1" title="修改模板文件"#}

<a href="{#$gotoupurl#}">返回列表</a><br/>
-------------<br/>
{#if $folderpath#}文件:{#$folderpath#}<br/>{#/if#}

{#form set="头|action:'{#str_replace(':', '\:', get_link('f'))#}&amp;f={#urlencode($smarty.get.f)#}',enctype:'multipart/form-data'"#}

{#form set="文本框|名称:filedata,style:'width\:100%; height\:300px'" data_value="{#$filedata#}" htmlspecialchars="1"#}<br/>

{#form set="按钮|名称:dosubmit,值:提交修改"#}
{#form set="尾"#}
<br/>
1.<a href="{#kuaifan getlink='beifen|f'#}&amp;beifen=1&amp;f={#urlencode($smarty.get.f)#}">点击备份此文件</a><br/>
{#if $bakarrlist#}
	{#form set="头|action:'{#str_replace(':', '\:', get_link('f'))#}&amp;f={#urlencode($smarty.get.f)#}'"#}
	2.{#form set="列表框|名称:reduction" list=$bakarrlist#}
	{#form set="按钮|名称:dosubmit,值:选择还原"#}
	{#form set="尾"#}<br/>
	3.<a href="{#kuaifan getlink='beifen|f'#}&amp;beifen=2&amp;f={#urlencode($smarty.get.f)#}">删除此文件的备份文件</a>
{#else#}
	2.备份后可进行还原
{#/if#}
<br/>
*注: 修改之前请做好备份！<br/>

{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
