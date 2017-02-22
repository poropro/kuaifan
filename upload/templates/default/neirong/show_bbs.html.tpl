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
		{#$louzhu = get_to_username($V.username)#}
		楼主: <a href="{#kuaifan getlinks='sid|vs'#}&amp;m=huiyuan&amp;c=ziliao&amp;username={#$V.username#}">{#$louzhu|colorname#}</a>
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

	{#if $smarty.get.p<2#}
		{#$F=wenjian('tupian',1)#}
		{#if $F#}
			<p class="center">
				{#foreach from=$F.list item=list#}
					{#wap_img($list.allurl,128,0)#}<br/>
				{#/foreach#}
				{#$F.pagelist#} ({#$F.page#}/{#$F.allpage#})
			</p>
		{#else#}
			{#if $V.thumb#}
				<p class="center">
					{#wap_img($V.thumb[0],128,0)#}
				</p>
			{#/if#}
		{#/if#}
	{#/if#}

	{#$content#}<br/>
	
	{#if $type.0=="yin"#}
		<b>[隐藏部分]</b><br/>
		{#get_yin($V.id, $V.catid, $V.username, $type.1.0)#}<br/>
	{#/if#}
	{#if $type.0=="pai"#}
		<b>[派币贴]</b><br/>
		总派发:{#$type.1.1#}{#$type.1.0|money_type#}, 回复奖励:{#$type.1.2#}, 剩余:{#$type.1.1-$type.1.3#}。<br/>
	{#/if#}
	{#if $type.0=="xuan"#}
		<b>[悬赏贴]</b><br/>
		悬赏:{#$type.1.1#}{#$type.1.0|money_type#}, 有效期:{#$type.1.2#}天。<br/>
		{#if $type.1.3#}
			最佳答案:{#$type.1.3|get_pl#}<br/>
		{#/if#}
	{#/if#}
	{#if $type.0=="mai"#}
		<b>[收费内容]</b><br/>
		{#get_mai($type.1, $V.username)#}<br/>
	{#/if#}

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


{#if ($M.setting.bbs_banzhu > 0 && in_array($smarty.const.US_USERID,$bbs_bzarr)) || $V.username==$smarty.const.US_USERNAME#}
	<div class="pnpage bottombor">
		<a href="{#kuaifan getlink='c'#}&amp;c=bbsgl">【管理帖子】</a><br/>
	</div>	
{#/if#}


<div class="articleshare bottombor">
	{#if $louzhu.qianming#}
		<div class="qianming">
			签名:{#$louzhu|qianming#}
		</div>	
	{#/if#}
	<div class="fenxiang">
		{#$fxurl = get_link('sid|vs','&')|urlencode#}
		{#$fxtitle = $V.title|urlencode#}
		分享: <a href="http://v.t.qq.com/share/share.php?url={#$fxurl#}&amp;appkey=801cf76d3cfc44ada52ec13114e84a96&amp;site=&amp;pic=&amp;title={#$fxtitle#}&amp;from=kuaifan">腾讯微博</a>
		<a href="http://v.t.sina.com.cn/share/share.php?url={#$fxurl#}&amp;title={#$fxtitle#}&amp;netType=html">新浪微博</a>
		<a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url={#$fxurl#}&amp;site=&amp;title={#$fxtitle#}&amp;pics=&amp;from=kuaifan">QQ空间</a>
	</div>	
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
<div class="pnpage bottombor">
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
	{#form set="按钮|名称:dosubmit,值:提交回帖" notvs="1"#}
	{#form set="隐藏|名称:go_url,值:'{#get_url()|urlencode#}'" notvs="1"#}
	{#form set="尾" notvs="1"#}
	{#if $M.setting.pinglun_format_num#}<a href="{#kuaifan getlink='c|a'#}&amp;c=pinglun&amp;a=upfile" class="none">附件回复</a>{#/if#}
	<br/>
	<a href="{#kuaifan getlink='shoucang'#}&amp;shoucang=1">添加到收藏夹</a>
</div>

<div class="pnpage">
	{#kuaifan_neirong_pinglun set="列表名:lists,显示数目:5,状态:1,标题长度:500" where="commentid='neirong_{#$V.catid#}_{#$V.id#}_{#$KUAIFAN.site#}'"#}
	{#foreach from=$lists item=list#}
		<b>{#lou($V.reply-$list._n)#}.</b>
		{#if $list.userid>0#}
			<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=ziliao&amp;userid={#$list.userid#}">{#$list.username|colorname#}</a>
		{#else#}
			{#$list.username#}
		{#/if#}
		({#$list.creat_at|date_format:"%Y-%m-%d %H:%M:%S"#})
		<br/>
		{#$list.content#}
		{#if $type.0=="xuan" && !$type.1.3 && $smarty.const.US_USERNAME == $V.username#}
			<a href="{#kuaifan getlink='c'#}&amp;c=zuijia&amp;rid={#$list.id#}">(设为最佳回复)</a> 
		{#/if#}
		{#if $M.setting.pinglun_guest_del && $list.userid>0 && ($list.userid==$smarty.const.US_USERID || in_array($smarty.const.US_USERID,$bbs_bzarr))#}
			<a href="{#kuaifan getlink='c'#}&amp;c=huifu&amp;del=1&amp;rid={#$list.id#}">[删除]</a>
		{#/if#}
		<a href="{#kuaifan getlink='upport'#}&amp;upport={#$list.id#}">推荐({#$list.support#})</a>
		<a href="{#kuaifan getlink='c'#}&amp;c=huifu&amp;rid={#$list.id#}">回复</a>
		<br/>
	{#foreachelse#}
		此贴暂无评论。<br/>
	{#/foreach#}
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