{#include file="common/header.tpl" title="会员动态"#}

<a href="{#kf_url('index')#}">首页</a>&gt;会员动态<br/>
-------------<br/>

{#kuaifan_tongji set="列表名:lists,ID列表:idlist,会员ID:GET[id],显示数目:15,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}
{#$url = get_link('sid|vs','',1)#}
{#foreach from=$lists item=list#}
	<small>{#$list._n#}.{#if $list.userid>0#}<a href="{#$url#}&amp;m=huiyuan&amp;c=ziliao&amp;userid={#$list.userid#}">{#$list.username_#}</a>{#else#}{#$list.username_#}{#/if#}({#$list.time|date_format:"%m-%d %H:%M:%S"#})</small><br/>
	{#$list.type#}<a href="{#$list.url#}&amp;sid={#$SID#}&amp;vs={#$VS#}">{#$list.title#}</a><br/>-----<br/>
{#foreachelse#}
	没有任何统计记录。<br/>
{#/foreach#}

{#$pagelist#}
<br/>


{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
