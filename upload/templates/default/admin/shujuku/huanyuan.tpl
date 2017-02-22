{#include file="common/header.tpl" title_top="1" title="数据库工具"#}


[数据库恢复]<br/>
-------------<br/>
文件名称(卷数)<br/>.<br/>
{#foreach from=$infos item=info#}
	{#rtrim($info.pre,'_')#}({#$info.number#})<br/>
	({#round($info.filesize,2)#} M) 备份时间:{#$info.maketime#}<br/>
	<a href="{#kuaifan getlink='pre|dosubmit'#}&amp;pre={#$info.pre#}&amp;dosubmit=1">数据恢复</a>.<a href="{#kuaifan getlink='a'#}&amp;a=del&amp;pre={#$info.pre#}">删除备份</a><br/>
	-------------<br/>
{#foreachelse#}
	没有任何备份,<a href="{#kuaifan getlink='a'#}">点击备份</a><br/>-------------<br/>
{#/foreach#}




	
	功能:<a href="{#kuaifan getlink='a'#}">备份</a>-还原-<a href="{#kuaifan getlink='a'#}&amp;a=youhua">优化</a>-<a href="{#kuaifan getlink='a'#}&amp;a=xiufu">修复</a><br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
