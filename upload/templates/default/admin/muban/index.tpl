{#include file="common/header.tpl" title_top="1" title="模板管理"#}

[模板管理]<br/>
<a href="{#kuaifan getlink='a|f'#}&amp;a=xinjian&amp;f={#urlencode($smarty.get.f)#}">新建文件</a><br/>
-------------<br/>
{#if $folderpath#}目录:{#$folderpath#}<br/>{#/if#}

{#form set="头" notvs=1#}

{#if $readmedata#}
	<a href="{#kuaifan getlink='a|f'#}&amp;f={#urlencode($readmedata)#}"><img src="{#$smarty.const.IMG_PATH#}msg-04.png"/>说明文件</a><br/>
{#/if#}
{#foreach from=$listarr item=list#}
	<a href="{#kuaifan getlink='a|f'#}&amp;a={#$list.a#}{#$list.vsname#}&amp;f={#urlencode($list.f)#}">{#$list.url#}</a>
	{#$list.input#}<br/>
{#/foreach#}

{#if $VS==1#}
	<anchor>[更新名称]
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	{#foreach from=$listarr item=list#}
	<postfield name='con_{#inputname($list.title)#}' value='$({#inputname($list.title)#}{#$TIME2#})'/>
	{#/foreach#}
	<postfield name='dosubmit' value='1'/>
	</go> </anchor> 
{#/if#}

{#form set="按钮|名称:dosubmit,值:'更新名称'" notvs=1#}
{#form set="尾" notvs=1#}
<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}