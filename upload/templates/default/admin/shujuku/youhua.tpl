{#include file="common/header.tpl" title_top="1" title="数据库工具"#}


[数据库碎片清理]<br/>

	{#form set="头" notvs="1"#}
	<small>选择/数据表/类型/记录数/数据大小/碎片byte</small><br/>
	{#foreach from=$list_arr item=list#}
		{#form set="列表框|名称:'tablename_{#$list.Name#}',值:'{#$list.Name#}'" list="选中:'{#$list.Name#}',不选:''"#}{#$list.Name#}/{#$list.Engine#}/{#$list.Rows#}/{#$list.Data_length#}/{#$list.Data_free#}<br/>
	{#/foreach#}

	{#if !$list_arr#}
		<b>*数据表没有碎片，不需要再优化。</b>
	{#else#}
		{#if $VS==1#}
			<anchor title="优化选中表">[优化选中表]
			<go href="{#get_link()#}" method="post" accept-charset="utf-8">
			{#foreach from=$list_arr item=list#}
				<postfield name="tablename_{#$list.Name#}" value="$(tablename_{#$list.Name#})"/>
			{#/foreach#}
			<postfield name="dosubmit" value="1"/>
			</go> </anchor>
		{#/if#}
		{#form set="按钮|名称:dosubmit,值:优化选中表" notvs="1"#}
	{#/if#}



	{#form set="尾" notvs="1"#}
	<br/>提示:数据表优化可以去除数据文件中的碎片，使记录排列紧密，提高读写速度。
	<br/>-------------<br/>

	功能:<a href="{#kuaifan getlink='a'#}">备份</a>-<a href="{#kuaifan getlink='a'#}&amp;a=huanyuan">还原</a>-优化-<a href="{#kuaifan getlink='a'#}&amp;a=xiufu">修复</a><br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
