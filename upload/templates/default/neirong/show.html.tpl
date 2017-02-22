{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_list.css' />
"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

<div class="daohang" id="top">
{#get_pos($M.id)#}
</div>

<div class="titpics">
	<h1>{#$V.title#}({#nocache#}{#$V.read#}{#/nocache#})</h1>
	<div class="tm">
	{#if !$V.sysadd#}
		作者: <a href="{#kuaifan getlinks='sid|vs'#}&amp;m=huiyuan&amp;c=ziliao&amp;username={#$V.username#}">{#$V.username|colorname#}</a>
	{#/if#}
	日期: {#$V.inputtime|date_format:"%Y-%m-%d %H:%M"#}
	</div>
</div>
		
<div class="content">	
	{#if $V.pagenumber > 1#}
		<p class="allpage">
		{#if $smarty.get.l=='a'#}
			<a href="{#kuaifan getlink='l'#}">分页模式</a>
		{#else#}
			<a href="{#kuaifan getlink='l'#}&amp;l=a">全文模式 (共{#$V.pagenumber#}页)</a>
		{#/if#}
		</p>
	{#/if#}
	{#if $V.thumb && $smarty.get.p<2#}
	{#wap_img($V.thumb[0],128,0)#}<br/>
	{#/if#}

	{#$content#}

	{#if $smarty.get.p<2#}
		{#$F=wenjian('downfile')#}
		{#if $F#}
			<div class="fj_list">
				<p class="down-title">附件列表({#$F.count#}个)：</p>
				{#foreach from=$F.list item=list#}
					<p><a href="{#$list.downurl#}">{#$list.name#}</a>({#$list.size#})</p>
					{#if $list.body#}<p class="down-body">{#$list.body#}</p>{#/if#}
				{#/foreach#}
			</div>
		{#/if#}
	{#/if#}
</div>	
<div class="pnpage bottombor">
	{#$V.pagelink#}
</div>	

<div class="pnpage bottombor">
	{#if $Q#}
		{#foreach from=$Q item=list#}
			<a href="{#kuaifan getlink='xinqing'#}&amp;xinqing={#$list.k#}"><img src="{#$smarty.const.IMG_PATH#}{#$list.pic#}" alt="{#$list.name#}"/></a>({#$list.num#})
		{#/foreach#}
		<br/>
	{#/if#}
</div>

<div class="pnpage">
{#if $K#}
	相关搜索:
	{#foreach from=$K item=list#}
		<a href="{#kuaifan getlinks='sid|vs'#}&amp;m=sousuo&amp;key={#$list|urlencode#}">{#$list#}</a>
	{#/foreach#}
	<br/>
{#/if#}
</div>

<div class="pnpage">
	<a href="{#kf_url('neirongreply')#}">网友评论({#$V.reply#})</a>.<a href="{#kuaifan getlink='c'#}&amp;c=pingluntop">评论排行榜&gt;&gt;</a>
</div>
<div class="pnpage">
	{#form set="头" notvs="1"#}
	{#form set="文本框|名称:pl{#$TIME2#},style:'width\:98%;'"#} <br/>
	{#kuaifan vs="1" set="
		  <anchor>发言
		  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
		  <postfield name='pl' value='$(pl{#$TIME2#})'/>
		  <postfield name='go_url' value='{#get_url()|urlencode#}'/>
		  <postfield name='dosubmit' value='1'/>
		  </go> </anchor>
		"#}
	{#form set="按钮|名称:dosubmit,值:提交发言" notvs="1"#}
	{#form set="隐藏|名称:go_url,值:'{#get_url()|urlencode#}'" notvs="1"#}
	{#form set="尾" notvs="1"#}
	{#if $M.setting.pinglun_format_num#}<a href="{#kuaifan getlink='c|a'#}&amp;c=pinglun&amp;a=upfile" class="none">附件回复</a>{#/if#}
	<br/>
	<a href="{#kuaifan getlink='shoucang'#}&amp;shoucang=1">添加到收藏夹</a>
</div>

<div class="cbnav">
	<div class="cbtit">活跃推荐</div>
</div>
<div class="adpubpic">
	{#kuaifan_neirong set="列表名:lists,显示数目:8,模型:{#$M.module#},分类:{#$M.id#},状态:1,标题长度:15,填补字符:...,排序:readtime DESC" where="id!={#$V.id#}"#}
	{#foreach from=$lists item=list#}
		<p><a href="{#$list.url#}">{#$list.title#}</a></p>
	{#foreachelse#}
		<p>暂无任何推荐</p>
	{#/foreach#}
</div>
	
<div class="daohang">
{#get_pos($M.id)#}
</div>

{#kuaifan tongji="阅读" get=$getarr#}
{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}