{#include file="common/header.html.tpl" title_top="1" title="修改内容"#}

[修改内容]<br/>
<a href="{#kuaifan getlink='edit'#}">返回.{#$lanmudb.title#}</a><br/>
-------------<br/>
<a href="{#kuaifan getlink='edit|del'#}&amp;del={#$smarty.get.edit#}"><b>!删除此内容</b></a><br/>
{#if $neirongdb.username && $neirongdb.sysadd=='0'#}
	发布会员:<a href="{#kuaifan getlinks='vs|allow|m'#}&amp;c=huiyuan&amp;username={#$neirongdb.username#}">{#$neirongdb.username#}</a><br/>

{#if $neirongdb.status=='1'#}
	审核状态:通过<br/>
{#elseif $neirongdb.status=='98'#}
	审核状态:草稿<br/>
{#elseif $neirongdb.status=='99'#}
	审核状态:审核中<br/>
{#elseif $neirongdb.status=='0'#}
	审核状态:退稿<br/>
{#/if#}	
修改状态:<a href="{#kuaifan getlink='status'#}&amp;status=1">通过</a>|<a href="{#kuaifan getlink='status'#}&amp;status=0">退稿</a>|<a href="{#kuaifan getlink='status'#}&amp;status=98">草稿</a>|<a href="{#kuaifan getlink='status'#}&amp;status=99">审核中</a><br/>

	
	
{#/if#}

{#form set="头|enctype:'multipart/form-data'"#}

移至栏目:<select name="yidonglanmuid"><option value='0'>保持不变(建议)</option>{#$categorys#}</select><br/>

{#foreach from=$ziduandata item=ziduan#}
	{#kuaifan_nr_form($ziduan.type,$ziduan,"<br/>", 2, $smarty.get.edit, $smarty.get.catid)#}
{#/foreach#}

{#if $neirongdb.username && $neirongdb.sysadd=='0'#}
	<div style="border-bottom:1px solid #DAD6FC; margin:3px 0px;"></div>
	修改理由:(留空不通知)<br/>
	{#form set="输入框|名称:'sys_editwhy{#$TIME2#}'"#}<br/>
{#/if#}

{#form set="按钮|名称:dosubmit,值:提交修改"#}
{#form set="尾"#}
<br/>


{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
