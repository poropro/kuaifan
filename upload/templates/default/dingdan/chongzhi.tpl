{#include file="common/header.tpl" title="账号充值"#}


<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>
------------- <br/>

{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:zhifu,排序:pid DESC" where="open=1"#}

【充值方式】<br/>
{#$url = get_link('vs|sid','',1)#}
{#foreach from=$lists item=list#}
	<a href="{#$url#}&amp;m=chongzhi&amp;c={#$list.path#}">{#$list.title#}</a><br/>
	-----<br/>
{#foreachelse#}
	<u>没有任何充值方式。</u><br/>
{#/foreach#}

{#$pagelist#}<br/>





{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
