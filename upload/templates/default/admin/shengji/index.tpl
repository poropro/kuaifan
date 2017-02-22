{#include file="common/header.tpl" title_top="1" title="在线升级"#}

	<a href="{#get_link('a')#}&amp;a=peizhi">自动检测新版本</a><br/>
	当前版本:{#$system_info.version#}<br/>
	更新日期:{#$system_info.release#}<br/>
	-------------<br/>
	{#if $pathlist#}
		待升级版本列表<br/>
		[升级前_升级后](更新日期)<br/>
	{#else#}
		<b><u>*程序已经是最新版本。</u></b><br/>
	{#/if#}
	
	{#foreach from=$pathlist item=list#}
        {#$list.setting = string2array($list.setting)#}
		{#$list._n#}.<a href="{#$yuanchengurl#}{#$list.setting.diffpath#}"><b>[{#$list.from_version#}({#$list.from_release#})_{#$list.to_version#}({#$list.to_release#})]</b></a>({#date("Y-m-d H:i:s",$list.indate)#})<br/>
		{#if $list.version_description#}<small>{#$list.version_description#}</small><br/>{#/if#}
	{#/foreach#}

    {#if $allpathlist.totalpage > 1#}
        {#$allpathlist.pagelist#} <br/>
        -------------<br/>
    {#/if#}
	{#if $pathlist#}
		<a href="{#get_link('dosubmit')#}&amp;dosubmit=1"><img src="{#$smarty.const.IMG_PATH#}shengji-anniu.png"/></a><br/>
	{#/if#}
	
	注意：升级程序有可能覆盖模版文件，请注意备份！linux服务器需检查文件所有者权限和组权限，确保WEB SERVER用户有文件写入权限<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
