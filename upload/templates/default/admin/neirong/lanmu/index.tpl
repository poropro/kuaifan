{#include file="common/header.tpl" title_top="1" title="栏目管理"#}

[栏目管理中心]<br/>
<a href="{#kuaifan getlink='add'#}&amp;add=true">添加栏目</a><br/>
-------------<br/>
ID.名称|所属模型<br/> 
{#$categorys#}

-------------<br/>
转到<a href="{#$admin_indexurl#}&amp;c=neirong">内容管理</a><br/> 

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
