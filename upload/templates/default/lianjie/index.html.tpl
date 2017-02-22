{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="友情链接"#}

<div class="daohang">
<a href="{#kf_url('index')#}">首页</a>&gt;<a href="{#kuaifan getlink='id|c'#}&amp;c=index">友链中心</a>
</div>


<div class="pnpage">
{#kuaifan_pc set="列表名:lists,显示数目:100,分页显示:1,数据表:lianjie_fenlei"#}
{#foreach from=$lists item=list#}
	{#if $list._n > 1#}
		{#if ($list._n-1)%5 == 0#}<br/>{#else#}.{#/if#}
	{#/if#}
	<a href="{#kuaifan getlink='catid'#}&amp;catid={#$list.catid#}">{#$list.title#}</a>
{#foreachelse#}
	没有友链分类
{#/foreach#}
<br/>
</div>

<div class="m_title">
{#$orderlink#}
</div>


<div class="pnpage">
{#$url = get_link('c|id')#}
{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:lianjie,排序:{#$ordersql#}" where="{#$wheresql#}"#}
{#foreach from=$lists item=list#}
	<a href="{#$url#}&amp;c=xiangqing&amp;id={#$list.id#}">{#$list._n#}.{#$list.title#}</a>({#$list.catid_cn#})<br/>
{#foreachelse#}
	<u>没有任何友情链接</u><br/>
{#/foreach#}

{#$pagelist#}
</div>


<div class="pnpage">
<a href="{#kuaifan getlink='c'#}&amp;c=shenqing">自助申请友链</a><br/>
<a href="{#kuaifan getlink='c'#}&amp;c=guanli">自助管理友链</a><br/>
</div>


{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
