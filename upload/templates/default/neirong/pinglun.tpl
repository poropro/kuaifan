{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

{#get_pos($M.id)#}
<br/>

返回:<a href="{#$V.url#}">{#$V.title#}</a>({#nocache#}{#$V.read#}{#/nocache#})
<br/>

{#if $smarty.get.support#}
	<a href="{#kf_url('neirongreply')#}">最新评论</a> | 最热评论
{#else#}
	最新评论 | <a href="{#kf_url('neirongreply')#}&amp;support=support">最热评论</a>
{#/if#}<br/>

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
{#form set="按钮|名称:dosubmit,值:发言" notvs="1"#}
{#form set="隐藏|名称:go_url,值:'{#get_url()|urlencode#}'" notvs="1"#}
{#form set="尾" notvs="1"#}
{#if $M.setting.pinglun_format_num#}<a href="{#kuaifan getlink='c|a'#}&amp;c=pinglun&amp;a=upfile" class="none">附件</a>{#/if#}
<br/>

{#kuaifan_neirong_pinglun set="列表名:lists,显示数目:15,状态:1,排序:GET[support],标题长度:500,分页显示:1,分页名:pagelist,分页变量名:page,原文标题:title" where="commentid='neirong_{#$V.catid#}_{#$V.id#}_{#$KUAIFAN.site#}'"#}

{#foreach from=$lists item=list#}
	<small>{#$list._n#}.{#if $list.userid>0#}<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=ziliao&amp;userid={#$list.userid#}">{#$list.username|colorname#}</a>{#else#}{#$list.username#}{#/if#} {#$list.creat_at|date_format:"%Y-%m-%d %H:%M:%S"#}</small><br/>
	{#strip_tags($list.content,"<br/>")#}
	{#if $type.0=="xuan" && !$type.1.3 && $smarty.const.US_USERNAME == $V.username#}
		<a href="{#kuaifan getlink='c'#}&amp;c=zuijia&amp;rid={#$list.id#}">(设为最佳回复)</a>
	{#/if#}
	{#if $M.setting.pinglun_guest_del && $list.userid>0 && ($list.userid==$smarty.const.US_USERID || in_array($smarty.const.US_USERID,$bbs_bzarr))#}
		<a href="{#kuaifan getlink='c'#}&amp;c=huifu&amp;del=1&amp;rid={#$list.id#}">[删除]</a>
	{#/if#}
	<br/>
	<a href="{#kuaifan getlink='upport'#}&amp;upport={#$list.id#}">推荐({#$list.support#})</a>　<a href="{#kuaifan getlink='c'#}&amp;c=huifu&amp;rid={#$list.id#}">回复</a><br/>
{#foreachelse#}
	没有任何评论。<br/>
{#/foreach#}


-------------<br/>
{#$pagelist#}<br/>
-------------<br/>





<a href="{#kuaifan getlink='c'#}&amp;c=pingluntop">评论排行榜&gt;&gt;</a>
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
{#get_pos($M.id)#}
<br/>
返回:<a href="{#$V.url#}">{#$V.title#}</a>
<br/>
【活跃推荐】<br/>
{#kuaifan_neirong set="列表名:lists,显示数目:8,模型:{#$M.module#},分类:{#$M.id#},状态:1,标题长度:15,填补字符:...,排序:readtime DESC" where="id!={#$V.id#}"#}
{#foreach from=$lists item=list#}
	<a href="{#$list.url#}">{#$list.title#}</a><br/>
{#foreachelse#}
	暂无任何推荐<br/>
{#/foreach#}

{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}