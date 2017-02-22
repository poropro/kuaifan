{#include file="common/header.tpl" title_top="1" title="木马查杀"#}


	{#if $VS==1#}[木马查杀]<br/>-------------<br/>{#/if#}
	
	
	{#form set="头" notvs="1"#}


	{#foreach from=$listarr item=list#}
		{#form set="列表框|名称:'mumaname_{#str_replace('.','',$list.title)#}',值:'{#$list.d#}'" list="未选:'',选中:'{#$list.title#}'"#}{#$list.url#}<br/>
	{#/foreach#}
	
	文件类型:<br/>
	{#form set="输入框|名称:file_type{#$TIME2#}" data_value=$scanarr.file_type#}<br/>
	
	特征函数:<br/>
	{#form set="输入框|名称:func{#$TIME2#}" data_value=$scanarr.func#}<br/>
	
	特征代码:<br/>
	{#form set="输入框|名称:code{#$TIME2#}" data_value=$scanarr.code#}<br/>
	
	MD5校验镜像:{#$md5_file#}<br/>
	
	{#if $VS==1#}
		<anchor title="提交查杀 ">[提交查杀]
		<go href="{#get_link()#}" method="post" accept-charset="utf-8">
		<postfield name="file_type" value="$(file_type{#$TIME2#})"/>
		<postfield name="func" value="$(func{#$TIME2#})"/>
		<postfield name="code" value="$(code{#$TIME2#})"/>
		<postfield name="md5_file" value="$(md5_file{#$TIME2#})"/>
		{#foreach from=$listarr item=list#}
			<postfield name="mumaname_{#str_replace('.','',$list.title)#}" value="$(mumaname_{#str_replace('.','',$list.title)#})"/>
		{#/foreach#}
		<postfield name="dosubmit" value="1"/>
		</go> </anchor>
	{#/if#}
	

	{#form set="按钮|名称:dosubmit,值:提交查杀" notvs="1"#}
	{#form set="尾" notvs="1"#}
	
<br/>-------------<br/>
	
	功能:木马查杀-<a href="{#kuaifan getlink='report'#}&amp;report=1">查杀报告</a>-<a href="{#kuaifan getlink='md5creat'#}&amp;md5creat=1">生成md5</a><br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
