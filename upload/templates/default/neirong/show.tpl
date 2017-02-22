{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

{#get_pos($M.id)#}
<br/>

{#$V.title#}({#nocache#}{#$V.read#}{#/nocache#})
<br/>
{#if !$V.sysadd#}
	作者:<a href="{#kuaifan getlinks='sid|vs'#}&amp;m=huiyuan&amp;c=ziliao&amp;username={#$V.username#}">{#$V.username|colorname#}</a>
	<br/>
{#/if#}

{#$V.inputtime|date_format:"%Y-%m-%d %H:%M"#}
<br/>

{#if $V.pagenumber > 1#}
	{#if $smarty.get.l=='a'#}
		<a href="{#kuaifan getlink='l'#}">分页模式</a>
	{#else#}
		<a href="{#kuaifan getlink='l'#}&amp;l=a">全文模式 (共{#$V.pagenumber#}页)</a>
	{#/if#}
	<br/>
{#/if#}


{#if $V.thumb && $smarty.get.p<2#}
{#wap_img($V.thumb[0],128,0)#}<br/>
{#/if#}

{#$content#}
<br/>

{#if $smarty.get.p<2#}
	{#$F=wenjian('downfile')#}
	{#if $F#}
		-------------<br/>
		附件列表({#$F.count#}个)：<br/>
		{#foreach from=$F.list item=list#}
			<a href="{#$list.downurl#}">{#$list.name#}</a>({#$list.size#})<br/>
			{#if $list.body#}说明:{#$list.body#}<br/>{#/if#}
		{#/foreach#}
		-------------<br/>
	{#/if#}
{#/if#}


{#$V.pagelink#}
<br/>

{#if $Q#}
	-------------<br/>
	{#foreach from=$Q item=list#}
		<a href="{#kuaifan getlink='xinqing'#}&amp;xinqing={#$list.k#}"><img src="{#$smarty.const.IMG_PATH#}{#$list.pic#}" alt="{#$list.name#}"/></a>({#$list.num#})
	{#/foreach#}
	<br/>
{#/if#}

{#if $K#}
	相关搜索:
	{#foreach from=$K item=list#}
		<a href="{#kuaifan getlinks='sid|vs'#}&amp;m=sousuo&amp;key={#$list|urlencode#}">{#$list#}</a>
	{#/foreach#}
	<br/>
{#/if#}

<a href="{#kf_url('neirongreply')#}">网友评论({#$V.reply#})</a>.<a href="{#kuaifan getlink='c'#}&amp;c=pingluntop">评论排行榜&gt;&gt;</a>
<br/>
{#form set="头" notvs="1"#}
{#form set="输入框|名称:pl{#$TIME2#}"#}
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
{#if $M.setting.pinglun_format_num#}<a href="{#kuaifan getlink='c|a'#}&amp;c=pinglun&amp;a=upfile" class="none">附件</a>{#/if#}
<br/>
<a href="{#kuaifan getlink='shoucang'#}&amp;shoucang=1">添加到收藏夹</a>
<br/>
{#get_pos($M.id)#}
<br/>
【活跃推荐】<br/>
{#kuaifan_neirong set="列表名:lists,显示数目:8,模型:{#$M.module#},分类:{#$M.id#},状态:1,标题长度:15,填补字符:...,排序:readtime DESC" where="id!={#$V.id#}"#}
{#foreach from=$lists item=list#}
	<a href="{#$list.url#}">{#$list.title#}</a><br/>
{#foreachelse#}
	暂无任何推荐<br/>
{#/foreach#}

{#kuaifan tongji="阅读" get=$getarr#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}