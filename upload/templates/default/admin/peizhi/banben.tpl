{#include file="common/header.tpl" title_top="1" title="版本高级规则配置"#}

	<a href="{#kuaifan getlink='a'#}">返回</a><br/>
	-------------<br/>
	
	{#form set="头" notvs="1"#}


	【跳转默认规则】<br/>
	简版转到:{#form set="列表框|名称:isvs1{#$TIME2#}" list="不转:0,彩版:2,触屏版:3,平板版:4,电脑版:5" default="0" data_value=$banben.isvs1#}<br/>
	彩版转到:{#form set="列表框|名称:isvs2{#$TIME2#}" list="不转:0,简版:1,触屏版:3,平板版:4,电脑版:5" default="0" data_value=$banben.isvs2#}<br/>
	触屏版转到:{#form set="列表框|名称:isvs3{#$TIME2#}" list="不转:0,简版:1,彩版:2,平板版:4,电脑版:5" default="0" data_value=$banben.isvs3#}<br/>
	平板版转到:{#form set="列表框|名称:isvs4{#$TIME2#}" list="不转:0,简版:1,彩版:2,触屏版:3,电脑版:5" default="0" data_value=$banben.isvs4#}<br/>
	电脑版转到:{#form set="列表框|名称:isvs5{#$TIME2#}" list="不转:0,简版:1,彩版:2,触屏版:3,平板版:4" default="0" data_value=$banben.isvs5#}<br/>
	注明: 此功能将强制指定的版本转到指定的版本。<br/>
	比如简版转到彩版，那就是用户访问简版时自动转到彩版(简版将无法访问)。<br/>
	--------<br/>
	
	
	【自定义版本规则】<br/>
	开启自定义:{#form set="列表框|名称:isAuto{#$TIME2#}" list="关闭:0,使用:1" default="0" data_value=$banben.isAuto#}(关闭时使用系统默认规则)<br/>
	.<br/>
	
	1.[手机访问默认]<br/>
	{#$lis = "简版:1,彩版:2,触屏版:3,平板版:4,电脑版:5"#}
	{#$liarr = array('isAndroidOS','isBlackBerryOS','isPalmOS','isSymbianOS','isWindowsMobileOS','isWindowsPhoneOS','isiOS','isMeeGoOS','isMaemoOS','isJavaOS','iswebOS','isbadaOS','isBREWOS')#}
	{#foreach from=$liarr item=lists#}
		{#$lists#}:{#form set="列表框|名称:{#$lists#}{#$TIME2#}" list=$lis default="0" data_value=$banben[{#$lists#}]#}<br/>
	{#/foreach#}
	isOther(其它):{#form set="列表框|名称:isOther{#$TIME2#}" list=$lis default="0" data_value=$banben.isOther#}<br/>
	.<br/>
	
	2.[平板访问默认]<br/>
	isTablet:{#form set="列表框|名称:isTablet{#$TIME2#}" list=$lis default="0" data_value=$banben.isTablet#}<br/>
	.<br/>

	
	3.[电脑访问默认]<br/>
	{#$liarrweb = array('isIE','isFirefox','isChrome','isSafari','isOpera')#}
	{#foreach from=$liarrweb item=lists#}
		{#$lists#}:{#form set="列表框|名称:{#$lists#}{#$TIME2#}" list=$lis default="0" data_value=$banben[{#$lists#}]#}<br/>
	{#/foreach#}
	iswebOther(其它):{#form set="列表框|名称:iswebOther{#$TIME2#}" list=$lis default="0" data_value=$banben.iswebOther#}<br/>
	注明: 此功能在用户没有设定浏览版本时有效。<br/>

	
	{#if $smarty.get.vs == '1'#}
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='isvs1' value='$(isvs1{#$TIME2#})'/>
	  <postfield name='isvs2' value='$(isvs2{#$TIME2#})'/>
	  <postfield name='isvs3' value='$(isvs3{#$TIME2#})'/>
	  <postfield name='isvs4' value='$(isvs4{#$TIME2#})'/>
	  <postfield name='isvs5' value='$(isvs5{#$TIME2#})'/>
	  
	  <postfield name='isAuto' value='$(isAuto{#$TIME2#})'/>
	  
	  {#foreach from=$liarr item=lists#}
		  <postfield name='{#$lists#}' value='$({#$lists#}{#$TIME2#})'/>
	  {#/foreach#}
	  
	  <postfield name='isOther' value='$(isOther{#$TIME2#})'/>
	  <postfield name='isTablet' value='$(isTablet{#$TIME2#})'/>
	  
	  {#foreach from=$liarrweb item=lists#}
		  <postfield name='{#$lists#}' value='$({#$lists#}{#$TIME2#})'/>
	  {#/foreach#}
	  
	  <postfield name='iswebOther' value='$(iswebOther{#$TIME2#})'/>

	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	{#/if#}
	
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	
	
	
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
