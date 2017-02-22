{#kuaifan_paiban_cache set="列表名:time_paiban,页面ID:{#$pageid#}"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

{#if $time_paiban#}
	{#foreach from=$time_paiban item=pblist#}
		{#if $pblist.type_en=='muban'#}
			{#paiban_index($pblist)#}
			{#include file="index/caches_paiban/{#$pblist.id#}/{#$pblist.lasttime#}.caches"#}
		{#else#}
			{#if $pblist.nocache#}{#paiban_index($pblist)#}{#else#}{#insert name="paiban" id=$pblist.id#}{#/if#}
		{#/if#}
	{#/foreach#}
{#else#}
	{#if $pageid>0#}
		页面正在设计中...<br/>
		<a href="{#kf_url('index')#}">返回网站首页</a>
	{#else#}
		网站正在建设中...
	{#/if#}<br/>
{#/if#}


{#kuaifan tongji="正在访问"#}
{#include file="common/footer.tpl"#}