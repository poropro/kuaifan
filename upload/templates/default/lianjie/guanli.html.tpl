{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="自助管理友链"#}

<div class="daohang">
<a href="{#kf_url('index')#}">首页</a>&gt;<a href="{#kuaifan getlink='id|c'#}&amp;c=index">友链中心</a>&gt;申请加入
</div>

<div class="m_title">
<a href="{#kuaifan getlink='c'#}&amp;c=shenqing">自助申请友链</a><br/>
</div>

<div class="pnpage">

{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:lianjie,排序:id DESC" where="userid={#$smarty.const.US_USERID#}"#}
序号.名称(分类)|进|出<br/>
{#$url = get_link('c|id')#}
{#foreach from=$lists item=list#}
	{#if $list.type=='0'#}<b>[审]</b>{#/if#}{#if $list.type=='2'#}<b>[黑]</b>{#/if#}<a href="{#$url#}&amp;c=xiangqing&amp;id={#$list.id#}">{#$list._n#}.{#$list.title#}</a>({#$list.catid_cn#})|{#$list.from#}|{#$list.read#}<br/>
	回链:<u>{#kuaifan getlinks='m'#}&amp;id={#$list.id#}</u><br/>
{#foreachelse#}
	<u>没有任何友情链接</u><br/>
{#/foreach#}

{#$pagelist#}
</div>


{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
