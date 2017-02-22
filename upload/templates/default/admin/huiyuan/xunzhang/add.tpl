{#include file="common/header.html.tpl" title_top="1" title=$title#}

[{#$title#}]<br/>
<a href="{#$URL#}">返回</a><br/>
{#if $row.id#}
	<a href="{#$URL#}&amp;z=del&amp;id={#$row.id#}">删除此勋章</a><br/>
{#/if#}
-------------<br/>
<form  method="post" action="{#get_link()#}" enctype="multipart/form-data"/>
	勋章名称:<br/>
	<input class="ipt-text" type="text" name="title" value="{#$row.title#}"/><br/>
	勋章分类:<br/>
	{#kuaifan_pc set="列表名:lists,显示数目:1000,数据表:huiyuan_xunzhang,排序:intime DESC" where="type='fenlei'"#}
	{#if $lists#}
		<select name="catid">
		{#foreach from=$lists item=list#}
			<option value="{#$list.id#}"{#if $row.catid==$list.id#} selected="selected"{#/if#}>{#$list.title#}</option>
		{#/foreach#}
		</select>
	{#else#}
		<u>无,请先<a href="{#$URL#}&amp;z=addfl">添加分类</a></u>
	{#/if#}<br/>

	勋章图片:<br/>
	{#if $row.img#}
		<img src="{#$row.img#}"/> [<a href="{#$URL#}&amp;z=delfile&amp;id={#$row.id#}">删除</a>]<br/>
	{#/if#}
	<input class="ipt-text" type="file" name="xunzhang" /><br/>
	描述:<br/>
	<textarea name="body" >{#$row.setting.body#}</textarea><br/>
	<input class="ipt-btn" type="submit" name="dosubmit" value="提交" />
</form>
<br/>

{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
