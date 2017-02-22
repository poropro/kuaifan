{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_list.css' />
"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

<div class="daohang" id="top">
{#get_pos($M.id)#}
</div>


<div class="pltit"><a href="{#$V.url#}">{#$V.title#}</a></div>


<div class="review">
{#form set="头" notvs="1"#}
发表评论<br/>
{#form set="文本框|名称:pl{#$TIME2#}"#}<br/>
{#kuaifan vs="1" set="
	  <anchor>发言
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
      <postfield name='pl' value='$(pl{#$TIME2#})'/>
	  <postfield name='go_url' value='{#get_url()|urlencode#}'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
{#form set="按钮|名称:dosubmit,值:发言" notvs="1"#}
{#form set="隐藏|名称:go_url,值:'{#get_url()|urlencode#}'" notvs="1"#}
{#form set="尾" notvs="1"#}
{#if $M.setting.pinglun_format_num#}<a href="{#kuaifan getlink='c|a'#}&amp;c=pinglun&amp;a=upfile" class="none">附件回复</a>{#/if#}
</div>

<div class="pnpage">
{#if $smarty.get.support#}
	<a href="{#kf_url('neirongreply')#}">最新评论</a> | 最热评论
{#else#}
	最新评论 | <a href="{#kf_url('neirongreply')#}&amp;support=support">最热评论</a>
{#/if#}
</div>


{#kuaifan_neirong_pinglun set="列表名:lists,显示数目:15,状态:1,排序:GET[support],标题长度:500,分页显示:1,分页名:pagelist,分页变量名:page,原文标题:title" where="commentid='neirong_{#$V.catid#}_{#$V.id#}_{#$KUAIFAN.site#}'"#}



<div class="listpls">
<ul>
{#foreach from=$lists item=list#}
    <li{#if ($list._n+1)%2==0#} class="bgblue"{#/if#}> <span>{#$list._n#}.{#if $list.userid>0#}<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=ziliao&amp;userid={#$list.userid#}">{#$list.username|colorname#}</a>{#else#}{#$list.username#}{#/if#} {#$list.creat_at|date_format:"%Y-%m-%d %H:%M:%S"#}</span><br>
      {#$list.content#}
	  {#if $type.0=="xuan" && !$type.1.3 && $smarty.const.US_USERNAME == $V.username#}
		  <a href="{#kuaifan getlink='c'#}&amp;c=zuijia&amp;rid={#$list.id#}">(设为最佳回复)</a>
	  {#/if#}
	  {#if $M.setting.pinglun_guest_del && $list.userid>0 && ($list.userid==$smarty.const.US_USERID || in_array($smarty.const.US_USERID,$bbs_bzarr))#}
	  	  <a href="{#kuaifan getlink='c'#}&amp;c=huifu&amp;del=1&amp;rid={#$list.id#}">[删除]</a>
	  {#/if#}
      <p class="tright"><a href="{#kuaifan getlink='upport'#}&amp;upport={#$list.id#}">推荐({#$list.support#})</a>　<a href="{#kuaifan getlink='c'#}&amp;c=huifu&amp;rid={#$list.id#}">回复</a></p>
    </li>
{#foreachelse#}
	<li>没有任何评论。</li>
{#/foreach#}
</ul>
</div>


<div class="pnpage">
{#$pagelist#}
</div>


<div class="pnpage bottombor">
<a href="{#kuaifan getlink='c'#}&amp;c=pingluntop">评论排行榜&gt;&gt;</a>
</div>

<div class="review">
{#form set="头" notvs="1"#}
发表评论<br/>
{#form set="文本框|名称:pl{#$TIME2#}"#}<br/>
{#kuaifan vs="1" set="
	  <anchor>发言
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
      <postfield name='pl' value='$(pl{#$TIME2#})'/>
	  <postfield name='go_url' value='{#get_url()|urlencode#}'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
{#form set="按钮|名称:dosubmit,值:发言" notvs="1"#}
{#form set="隐藏|名称:go_url,值:'{#get_url()|urlencode#}'" notvs="1"#}
{#form set="尾" notvs="1"#}
{#if $M.setting.pinglun_format_num#}<a href="{#kuaifan getlink='c|a'#}&amp;c=pinglun&amp;a=upfile" class="none">附件回复</a>{#/if#}
</div>

<div class="daohang">
{#get_pos($M.id)#}
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
	

{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}