{#include file="common/header.tpl" title_top="1" title="充值方式"#}


<a href="{#$admin_indexurl#}&amp;c=chongzhi">在线充值</a>&gt;充值方式<br/>
------------- <br/>

{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:zhifu,排序:pid DESC"#}

【充值方式】<br/>
{#$url = get_link('a|id')#}
{#foreach from=$lists item=list#}
	<a href="{#$url#}&amp;a=peizhi&amp;id={#$list.id#}">{#$list.title#}</a>|{#if $list.open==1#}启用{#else#}停用{#/if#}<br/>功能版本:{#$list.v#}<br/>
	-----<br/>
{#foreachelse#}
	<u>没有任何充值方式。</u><br/>
{#/foreach#}

{#$pagelist#}<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}